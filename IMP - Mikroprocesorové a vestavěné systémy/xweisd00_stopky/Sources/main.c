//       An example for demonstrating basic principles of FITkit3 usage.
//
// It includes GPIO - inputs from button press/release, outputs for LED control,
// timer in output compare mode for generating periodic events (via interrupt
// service routine) and speaker handling (via alternating log. 0/1 through
// GPIO output on a reasonable frequency). Using this as a basis for IMP projects
// as well as for testing basic FITkit3 operation is strongly recommended.
//
//            (c) 2019 Michal Bidlo, BUT FIT, bidlom@fit.vutbr.cz
////////////////////////////////////////////////////////////////////////////
/* Header file with all the essential definitions for a given type of MCU */
#include "MK60D10.h"

/* Macros for bit-level registers manipulation */
#define GPIO_PIN_MASK 0x1Fu
#define GPIO_PIN(x) (((1)<<(x & GPIO_PIN_MASK)))

/* Mappings to specific port pins: */
#define BTN_SW2 0x400     // Port E, bit 10
#define BTN_SW3 0x1000    // Port E, bit 12
#define BTN_SW4 0x8000000 // Port E, bit 27
#define BTN_SW5 0x4000000 // Port E, bit 26
#define BTN_SW6 0x800     // Port E, bit 11

//Vccs
#define Vcc_4 0x100
#define Vcc_3 0x400
#define Vcc_2 0x40
#define Vcc_1 0x800
//GNDs
#define seg_A 0x80			// 0b00000000000000000000000010000000
#define seg_B 0x200			// 0b00000000000000000000001000000000
#define seg_C 0x8000000		// 0b00001000000000000000000000000000
#define seg_D 0x20000000	// 0b00100000000000000000000000000000
#define seg_E 0x4000000		// 0b00000100000000000000000000000000
#define seg_F 0x10000000	// 0b00010000000000000000000000000000
#define seg_G 0x1000000		// 0b00000001000000000000000000000000
#define seg_P 0x2000000		// 0b00000010000000000000000000000000

/* Globals */
int pressed_up = 0, pressed_down = 0, pressed_right = 0, pressed_left = 0;
int miliseconds_cnt = 0; 	// 10ms = 0.01s
int centiseconds_cnt = 0; 	// 10cs = 0.1s
int decisecond_cnt = 0; 	// 10ds = 1s
int seconds_cnt = 0;		// seconds...
uint32_t helper_seg1 = 0;
uint32_t helper_seg2 = 0;
uint32_t helper_seg3 = 0;
uint32_t helper_seg4 = 0;

/* A delay function */
void delay(long long bound) {
	long long i;
	for(i=0;i<bound;i++);
}

/* Initialize the MCU - basic clock settings, turning the watchdog off */
void MCUInit(void)  {
	MCG->C4 |= (MCG_C4_DMX32_MASK | MCG_C4_DRST_DRS(0x01));	//
	SIM_CLKDIV1 |= SIM_CLKDIV1_OUTDIV1(0x00);	// Set system clock division to divide-by-1
	WDOG_STCTRLH &= ~WDOG_STCTRLH_WDOGEN_MASK; // Disable watchdog
}

void PortsInit(void)
{
    /* Turn on all port clocks */
	SIM->SCGC5 = SIM_SCGC5_PORTB_MASK | SIM_SCGC5_PORTE_MASK | SIM_SCGC5_PORTA_MASK;
	/* Turn on PIT clock */
	//SIM->SCGC6 |= SIM_SCGC6_PIT_MASK;

    /* Set corresponding PTB pins (connected to LED's) for GPIO functionality */
    PORTA->PCR[8] = PORT_PCR_MUX(0x01); // 1
    PORTA->PCR[10] = PORT_PCR_MUX(0x01); // 2
    PORTA->PCR[6] = PORT_PCR_MUX(0x01); // 3
    PORTA->PCR[11] = PORT_PCR_MUX(0x01); // 4

    PORTA->PCR[7] = PORT_PCR_MUX(0x01); // A
    PORTA->PCR[9] = PORT_PCR_MUX(0x01); // B
    PORTA->PCR[27] = PORT_PCR_MUX(0x01); // C
    PORTA->PCR[29] = PORT_PCR_MUX(0x01); // D
    PORTA->PCR[26] = PORT_PCR_MUX(0x01); // E
    PORTA->PCR[28] = PORT_PCR_MUX(0x01); // F
    PORTA->PCR[24] = PORT_PCR_MUX(0x01); // G
    PORTA->PCR[25] = PORT_PCR_MUX(0x01); // P

    PORTE->PCR[10] = PORT_PCR_MUX(0x01); // SW2
    PORTE->PCR[12] = PORT_PCR_MUX(0x01); // SW3
    PORTE->PCR[27] = PORT_PCR_MUX(0x01); // SW4
    PORTE->PCR[26] = PORT_PCR_MUX(0x01); // SW5
//    PORTE->PCR[11] = PORT_PCR_MUX(0x01); // SW6

    PTA->PDDR = GPIO_PDDR_PDD(0xD40);
//    PTA->PDOR = GPIO_PDOR_PDO(Vcc_1);
//    PTA->PDOR |= GPIO_PDOR_PDO(Vcc_2);
//    PTA->PDOR |= GPIO_PDOR_PDO(Vcc_3);
//    PTA->PDOR |= GPIO_PDOR_PDO(Vcc_4);
}

void PIT1_IRQHandler(void){
	//PTA->PDOR = GPIO_PDOR_PDO(Vcc_1);
	//PTA->PDDR |= GPIO_PDDR_PDD(0b00001000000000000000001000000000);
	miliseconds_cnt++;
	centiseconds_cnt++;
	decisecond_cnt++;
	seconds_cnt++;
	PIT_TFLG1 |= 0x01;		// clear interrupt flag
}

void PIT_enabler(void){
	SIM->SCGC6 |= SIM_SCGC6_PIT_MASK;	// enable the PIT timer
	PIT_TCTRL1 &= ~0x1;					// TEN disable
	PIT->MCR &= ~0x2;					// MDIS enable
	PIT_LDVAL1 = 0x752ff;				// 0x752FF = 479999 cycles -> 10ms
	PIT_TFLG1 =  0x1;					// clear interrupt flag
	PIT_TCTRL1 |= 0x2;					// TIE enable
	NVIC_EnableIRQ(PIT1_IRQn);			// enable interrupts from PIT1
	PIT_TCTRL1 |=  0x1;					// TEN enable
}
void display_1_ON(void){
	PTA->PDOR = GPIO_PDOR_PDO(Vcc_1);
}
void display_2_ON(void){
	PTA->PDOR |= GPIO_PDOR_PDO(Vcc_2);
}
void display_3_ON(void){
	PTA->PDOR |= GPIO_PDOR_PDO(Vcc_3);
}
void display_4_ON(void){
	PTA->PDOR |= GPIO_PDOR_PDO(Vcc_4);
}

void segment_1_OFF(void){
	PTA->PDOR ^= GPIO_PDOR_PDO(Vcc_1);
}
void segment_2_OFF(void){
	PTA->PDOR ^= GPIO_PDOR_PDO(Vcc_2);
}
void segment_3_OFF(void){
	PTA->PDOR ^= GPIO_PDOR_PDO(Vcc_3);
}
void segment_4_OFF(void){
	PTA->PDOR ^= GPIO_PDOR_PDO(Vcc_4);
}

void draw_1(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00001000000000000000001000000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00001010000000000000001000000000);
}
void draw_2(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00100101000000000000001010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00100111000000000000001010000000);
}
void draw_3(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00101001000000000000001010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00101011000000000000001010000000);
}
void draw_4(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00011001000000000000001000000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00011011000000000000001000000000);
}
void draw_5(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00111001000000000000000010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00111011000000000000000010000000);
}
void draw_6(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00111101000000000000000010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00111111000000000000000010000000);
}
void draw_7(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00001000000000000000001010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00001010000000000000001010000000);
}
void draw_8(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00111101000000000000001010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00111111000000000000001010000000);
}
void draw_9(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00111001000000000000001010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00111011000000000000001010000000);
}
void draw_0(void){
	PTA->PDDR |= GPIO_PDDR_PDD(0b00111100000000000000001010000000);
	//delay(2000000);			 0b00000010000000000000000000000000
	//PTA->PDDR ^= GPIO_PDDR_PDD(0b00111110000000000000001010000000);
}

void number_switch(int num){
	switch (num)
	{
		case 0:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00111100000000000000001010000000); //draw_0();
			break;
		case 1:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00001000000000000000001000000000); //draw_1();
			break;
		case 2:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00100101000000000000001010000000); //draw_2();
			break;
		case 3:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00101001000000000000001010000000); //draw_3();
			break;
		case 4:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00011001000000000000001000000000); //draw_4();
			break;
		case 5:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00111001000000000000000010000000); //draw_5();
			break;
		case 6:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00111101000000000000000010000000); //draw_6();
			break;
		case 7:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00001000000000000000001010000000); //draw_7();
			break;
		case 8:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00111101000000000000001010000000); //draw_8();
			break;
		case 9:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00111001000000000000001010000000); //draw_9();
			break;
		default:
			PTA->PDDR |= GPIO_PDDR_PDD(0b00111100000000000000001010000000); //draw_0();
	}
}

int main(void)
{
    MCUInit();
    PortsInit();

//    LPTMR0Init(compare);



    int a = 0, b = 0, c = 0;

    while(1){
    	display_1_ON();
    	display_2_ON();
    	display_3_ON();
    	display_4_ON();
    	draw_0();
		if(!pressed_up && !(GPIOE_PDIR & BTN_SW5)){
			pressed_up = 1;
			PIT_enabler();
				for (;;a++){
					delay(10000);
					PTA->PDOR = 0; //vynulovanie cisiel
					PTA->PDDR = GPIO_PDDR_PDD(0xD40); //nastavenie Vcc
					if (a == 0){
						display_1_ON();
						if(miliseconds_cnt == 0)
							draw_0();
						if(miliseconds_cnt == 1)
							draw_1();
						if(miliseconds_cnt == 2)
								draw_2();
						if(miliseconds_cnt == 3)
								draw_3();
						if(miliseconds_cnt == 4)
								draw_4();
						if(miliseconds_cnt == 5)
								draw_5();
						if(miliseconds_cnt == 6)
								draw_6();
						if(miliseconds_cnt == 7)
								draw_7();
						if(miliseconds_cnt == 8)
								draw_8();
						if(miliseconds_cnt == 9)
								draw_9();
						if(miliseconds_cnt > 9)
							miliseconds_cnt = 0;

						helper_seg1 = PTA->PDDR;
					}
					if (a == 1){
						display_2_ON();
						if(centiseconds_cnt >= 0 && centiseconds_cnt <= 9)
								draw_0();
						if(centiseconds_cnt >= 10 && centiseconds_cnt <= 19)
								draw_1();
						if(centiseconds_cnt >= 20 && centiseconds_cnt <= 29)
								draw_2();
						if(centiseconds_cnt >= 30 && centiseconds_cnt <= 39)
								draw_3();
						if(centiseconds_cnt >= 40 && centiseconds_cnt <= 49)
								draw_4();
						if(centiseconds_cnt >= 50 && centiseconds_cnt <= 59)
								draw_5();
						if(centiseconds_cnt >= 60 && centiseconds_cnt <= 69)
								draw_6();
						if(centiseconds_cnt >= 70 && centiseconds_cnt <= 79)
								draw_7();
						if(centiseconds_cnt >= 80 && centiseconds_cnt <= 89)
								draw_8();
						if(centiseconds_cnt >= 90 && centiseconds_cnt <= 99)
								draw_9();
						if(centiseconds_cnt > 99)
							centiseconds_cnt = 0;

						helper_seg2 = PTA->PDDR;
					}
					if (a == 2){
						display_3_ON();
						if(decisecond_cnt >= 000 && decisecond_cnt <=99)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00111110000000000000001010000000); //draw_0(); with dot
						if(decisecond_cnt >= 100 && decisecond_cnt <=199)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00001010000000000000001000000000);	//draw_1(); with dot
						if(decisecond_cnt >= 200 && decisecond_cnt <=299)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00100111000000000000001010000000); //draw_2(); with dot
						if(decisecond_cnt >= 300 && decisecond_cnt <=399)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00101011000000000000001010000000);	//draw_3(); with dot
						if(decisecond_cnt >= 400 && decisecond_cnt <=499)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00011011000000000000001000000000);	//draw_4(); with dot
						if(decisecond_cnt >= 500 && decisecond_cnt <=599)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00111011000000000000000010000000);	//draw_5(); with dot
						if(decisecond_cnt >= 600 && decisecond_cnt <=699)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00111111000000000000000010000000);	//draw_6(); with dot
						if(decisecond_cnt >= 700 && decisecond_cnt <=799)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00001010000000000000001010000000);	//draw_7(); with dot
						if(decisecond_cnt >= 800 && decisecond_cnt <=899)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00111111000000000000001010000000);	//draw_8(); with dot
						if(decisecond_cnt >= 900 && decisecond_cnt <=999)
							PTA->PDDR |= GPIO_PDDR_PDD(0b00111011000000000000001010000000);	//draw_9(); with dot
						if(decisecond_cnt > 999)
							decisecond_cnt = 0;

						helper_seg3 = PTA->PDDR;
					}
					if (a == 3){
						display_4_ON();
						if(seconds_cnt >= 0000 && seconds_cnt <=999)
								draw_0();
						if(seconds_cnt >= 1000 && seconds_cnt <=1999)
								draw_1();
						if(seconds_cnt >= 2000 && seconds_cnt <=2999)
								draw_2();
						if(seconds_cnt >= 3000 && seconds_cnt <=3999)
								draw_3();
						if(seconds_cnt >= 4000 && seconds_cnt <=4999)
								draw_4();
						if(seconds_cnt >= 5000 && seconds_cnt <=5999)
								draw_5();
						if(seconds_cnt >= 6000 && seconds_cnt <=6999)
								draw_6();
						if(seconds_cnt >= 7000 && seconds_cnt <=7999)
								draw_7();
						if(seconds_cnt >= 8000 && seconds_cnt <=8999)
								draw_8();
						if(seconds_cnt >= 9000 && seconds_cnt <=9999)
								draw_9();
						if(seconds_cnt > 9999)
							seconds_cnt = 0;

						helper_seg4 = PTA->PDDR;
					}
					if (a > 3){
						a = -1;
					}
					/* Reset */
					if(!pressed_right && !(GPIOE_PDIR & BTN_SW2)){
						pressed_right = 1;
						 for (;;c++){
							 delay(1000);
							 PTA->PDOR = 0; //vynulovanie cisiel
							 PTA->PDDR = GPIO_PDDR_PDD(0xD40); //nastavenie Vcc
							 if (c == 0){
								display_1_ON();
								draw_0();
							 }
							 if (c == 1){
								display_2_ON();
								draw_0();
							 }
							 if (c == 2){
								display_3_ON();
								draw_0();
							 }
							 if (c == 3){
								display_4_ON();
								draw_0();
							 }
							 if (c > 3){
								 c = -1;
							}
						}
					}
					/* Pause */
					if(!pressed_down && !(GPIOE_PDIR & BTN_SW3)){
						 pressed_down = 1;
						 for (;;b++){
							 delay(1000);
							 PTA->PDOR = 0; //vynulovanie cisiel
							 PTA->PDDR = GPIO_PDDR_PDD(0xD40); //nastavenie Vcc
							 if (b == 0){
								display_1_ON();
								PTA->PDDR |= GPIO_PDDR_PDD(helper_seg1);
							 }
							 if (b == 1){
								display_2_ON();
								PTA->PDDR |= GPIO_PDDR_PDD(helper_seg2);
							 }
							 if (b == 2){
								display_3_ON();
								PTA->PDDR |= GPIO_PDDR_PDD(helper_seg3);
							 }
							 if (b == 3){
								display_4_ON();
								PTA->PDDR |= GPIO_PDDR_PDD(helper_seg4);
							 }
							 if (b > 3){
								 b = -1;
							}
							 if(!pressed_left && !(GPIOE_PDIR & BTN_SW4)){
									pressed_left = 1;
									continue;
							 }
						}
					}
//					if(!pressed_left && !(GPIOE_PDIR & BTN_SW4)){
//						pressed_left = 1;
//					}
				}
		}
    }

    return 0;
}
