
/* c206.c **********************************************************}
{* Téma: Dvousměrně vázaný lineární seznam
**
**                   Návrh a referenční implementace: Bohuslav Křena, říjen 2001
**                            Přepracované do jazyka C: Martin Tuček, říjen 2004
**                                            Úpravy: Kamil Jeřábek, září 2018
**
** Implementujte abstraktní datový typ dvousměrně vázaný lineární seznam.
** Užitečným obsahem prvku seznamu je hodnota typu int.
** Seznam bude jako datová abstrakce reprezentován proměnnou
** typu tDLList (DL znamená Double-Linked a slouží pro odlišení
** jmen konstant, typů a funkcí od jmen u jednosměrně vázaného lineárního
** seznamu). Definici konstant a typů naleznete v hlavičkovém souboru c206.h.
**
** Vaším úkolem je implementovat následující operace, které spolu
** s výše uvedenou datovou částí abstrakce tvoří abstraktní datový typ
** obousměrně vázaný lineární seznam:
**
**      DLInitList ...... inicializace seznamu před prvním použitím,
**      DLDisposeList ... zrušení všech prvků seznamu,
**      DLInsertFirst ... vložení prvku na začátek seznamu,
**      DLInsertLast .... vložení prvku na konec seznamu,
**      DLFirst ......... nastavení aktivity na první prvek,
**      DLLast .......... nastavení aktivity na poslední prvek,
**      DLCopyFirst ..... vrací hodnotu prvního prvku,
**      DLCopyLast ...... vrací hodnotu posledního prvku,
**      DLDeleteFirst ... zruší první prvek seznamu,
**      DLDeleteLast .... zruší poslední prvek seznamu,
**      DLPostDelete .... ruší prvek za aktivním prvkem,
**      DLPreDelete ..... ruší prvek před aktivním prvkem,
**      DLPostInsert .... vloží nový prvek za aktivní prvek seznamu,
**      DLPreInsert ..... vloží nový prvek před aktivní prvek seznamu,
**      DLCopy .......... vrací hodnotu aktivního prvku,
**      DLActualize ..... přepíše obsah aktivního prvku novou hodnotou,
**      DLSucc .......... posune aktivitu na další prvek seznamu,
**      DLPred .......... posune aktivitu na předchozí prvek seznamu,
**      DLActive ........ zjišťuje aktivitu seznamu.
**
** Při implementaci jednotlivých funkcí nevolejte žádnou z funkcí
** implementovaných v rámci tohoto příkladu, není-li u funkce
** explicitně uvedeno něco jiného.
**
** Nemusíte ošetřovat situaci, kdy místo legálního ukazatele na seznam 
** předá někdo jako parametr hodnotu NULL.
**
** Svou implementaci vhodně komentujte!
**
** Terminologická poznámka: Jazyk C nepoužívá pojem procedura.
** Proto zde používáme pojem funkce i pro operace, které by byly
** v algoritmickém jazyce Pascalovského typu implemenovány jako
** procedury (v jazyce C procedurám odpovídají funkce vracející typ void).
**/

#include "c206.h"

int errflg;
int solved;

void DLError() {
/*
** Vytiskne upozornění na to, že došlo k chybě.
** Tato funkce bude volána z některých dále implementovaných operací.
**/	
    printf ("*ERROR* The program has performed an illegal operation.\n");
    errflg = TRUE;             /* globální proměnná -- příznak ošetření chyby */
    return;
}

void DLInitList (tDLList *L) {
/*
** Provede inicializaci seznamu L před jeho prvním použitím (tzn. žádná
** z následujících funkcí nebude volána nad neinicializovaným seznamem).
** Tato inicializace se nikdy nebude provádět nad již inicializovaným
** seznamem, a proto tuto možnost neošetřujte. Vždy předpokládejte,
** že neinicializované proměnné mají nedefinovanou hodnotu.
**/
    L->First = NULL;
	L->Act = NULL;
	L->Last = NULL;
	
}

void DLDisposeList (tDLList *L) {
/*
** Zruší všechny prvky seznamu L a uvede seznam do stavu, v jakém
** se nacházel po inicializaci. Rušené prvky seznamu budou korektně
** uvolněny voláním operace free. 
**/

	tDLElemPtr helper;					//pomocna promenna
    while (L->First != NULL){
    	helper = L->First;
    	L->First = L->First->rptr;  	//nasledujuci prvok v zozname sa stane prvy a bude sa cyklicky prepisovat
   		free(helper);					//uvolnenie
  }
  L->Last = NULL;
  L->Act = NULL;
}

void DLInsertFirst (tDLList *L, int val) {
/*
** Vloží nový prvek na začátek seznamu L.
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/
	tDLElemPtr helper = malloc(sizeof(struct tDLElem));
	if(helper == NULL){			//chyba alokacie
		DLError();
	}
	else{
	helper->data = val;				//nahranie dat
	helper->lptr = NULL; 			//predchadzajuci prvok na NULL
	helper->rptr = L->First; 		//nasledujuci prvok na prvy prvok
	if (L->First != NULL){ 			//ak zoznam uz mal prvy prvok
		L->First->lptr = helper; 	
	}
	else{ 							//ak este nemal prvy prvok
		L->Last = helper;			//posledny prvok bude nas vlozeny prvok
	}
	L->First = helper;				//zaciatok na novy prvok
	}
}	

void DLInsertLast(tDLList *L, int val) {
/*
** Vloží nový prvek na konec seznamu L (symetrická operace k DLInsertFirst).
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/ 	
	tDLElemPtr helper = malloc(sizeof(struct tDLElem));
	if(helper == NULL){			//chyba alokacie
		DLError();
	}
	else{
	helper->data = val;				//nahranie dat
	helper->rptr = NULL; 			//nasledujuci prvok na NULL
	helper->lptr = L->Last; 		//predchadzajuci prvok na posledny prvok
	if (L->First != NULL){ 			//ak zoznam uz mal prvy prvok
		L->Last->rptr = helper; 	//nasledujuci prvok od posledneho bude helper (co bude poslednym prvok)
	}
	else{ 							//ak este nemal prvy prvok
		L->First = helper;			//prvy bude nas prvok (helper)
	}
	L->Last = helper;				//helper je poslednym prvokm
	}
	
}

void DLFirst (tDLList *L) {
/*
** Nastaví aktivitu na první prvek seznamu L.
** Funkci implementujte jako jediný příkaz (nepočítáme-li return),
** aniž byste testovali, zda je seznam L prázdný.
**/
	L->Act=L->First;	//Prvy prvok je aktivny,
}

void DLLast (tDLList *L) {
/*
** Nastaví aktivitu na poslední prvek seznamu L.
** Funkci implementujte jako jediný příkaz (nepočítáme-li return),
** aniž byste testovali, zda je seznam L prázdný.
**/
	L->Act=L->Last;		//Posledny prvok je aktivnym
}

void DLCopyFirst (tDLList *L, int *val) {
/*
** Prostřednictvím parametru val vrátí hodnotu prvního prvku seznamu L.
** Pokud je seznam L prázdný, volá funkci DLError().
**/
	if(L->First != NULL){		//ak nieje prazdny ulozi do val hodnotu
		*val = L->First->data; 	
	}
	else{						//ak je prazdny nastane chyba
		 DLError();	
	}
}

void DLCopyLast (tDLList *L, int *val) {
/*
** Prostřednictvím parametru val vrátí hodnotu posledního prvku seznamu L.
** Pokud je seznam L prázdný, volá funkci DLError().
**/
	if(L->First != NULL){		//ak nieje prazdny ulozi do val hodnotu
		*val = L->Last->data; 	
	}
	else{						//ak je prazdny nastane chyba
		 DLError();	
	}
}

void DLDeleteFirst (tDLList *L) {
/*
** Zruší první prvek seznamu L. Pokud byl první prvek aktivní, aktivita 
** se ztrácí. Pokud byl seznam L prázdný, nic se neděje.
**/
	tDLElemPtr helper;
	if (L->First != NULL){				//ak nieje zoznam prazdny
		helper = L->First;				//prvy prvok do pomocnej premennej
		if (L->Act == L->First){ 		//ak je prvy aktivnym
			L->Act = NULL;				//ruší se aktivita
		}
		if (L->First == L->Last){		//ak mal zoznam len jeden prvok zrusi sa
			L->First = NULL;			
			L->Last = NULL;
		}
		else {
			L->First = L->First->rptr;	//druhy prvok sa stane prvym prvokm
			L->First->lptr = NULL; 		//pointer na predchadzajuci prvok na NULL
		}
		free(helper);
	}
}	

void DLDeleteLast (tDLList *L) {
/*
** Zruší poslední prvek seznamu L. Pokud byl poslední prvek aktivní,
** aktivita seznamu se ztrácí. Pokud byl seznam L prázdný, nic se neděje.
**/ 	
	tDLElemPtr helper;
	if (L->First != NULL){				//ak nieje zoznam prazdny
		helper = L->Last;				//posledny prvok do pomocnej premennej
		if (L->Act == L->First){ 		//ak je prvy aktivnym
			L->Act = NULL;				//ruší se aktivita
		}
		if (L->First == L->Last){		//ak mal zoznam len jeden prvok zrusi sa
			L->First = NULL;			
			L->Last = NULL;
		}
		else {
			L->Last = L->Last->lptr;	//posledny prvok sa stane prvok pred poslednym (predposledny bude posledny)
			L->Last->rptr = NULL; 		//pointer na predchadzajuci prvok na NULL
		}
		free(helper);
	}
}

void DLPostDelete (tDLList *L) {
/*
** Zruší prvek seznamu L za aktivním prvkem.
** Pokud je seznam L neaktivní nebo pokud je aktivní prvek
** posledním prvkem seznamu, nic se neděje.
**/
	if (L->Act != NULL){						//ak je zoznam aktivnym
		if (L->Act != L->Last){					//ak aktivny nieje posledny
			tDLElemPtr helper;					//pomocna premenna
			helper = L->Act->rptr;				//ulozime do neho nasledujuci prvok za aktivnym
			L->Act->rptr = helper->rptr;		//preskocenie ruseneho prvkou
			if (helper == L->Last){				//ak je ruseny prvok poslednym
				L->Last = L->Act;				//posledny bude aktivny
			}
			else{								//ak nieje posledny
				helper->rptr->lptr = L->Act; 	//prvok za zrysenym ukazuje dolava v zozname na aktualny	
			}
			free(helper);
		}
	} 
}

void DLPreDelete (tDLList *L) {
/*
** Zruší prvek před aktivním prvkem seznamu L .
** Pokud je seznam L neaktivní nebo pokud je aktivní prvek
** prvním prvkem seznamu, nic se neděje.
**/
	if (L->Act != NULL){						//ak je zoznam aktivnym
		if (L->Act != L->First){				//ak aktivny nieje prvy
			tDLElemPtr helper;					//pomocna premenna
			helper = L->Act->lptr;				//ulozime do neho predchadzajuci prvok pred aktivnym
			L->Act->lptr = helper->lptr;		//preskocenie ruseneho prvkou
			if (helper == L->First){			//ak je ruseny prvok prvym
				L->First = L->Act;				//prvy bude aktivny
			}
			else{								//ak nieje prvy
				helper->lptr->rptr = L->Act;	//prvok pred zrysenym ukazuje doprava v zozname na aktualny
			}
			free(helper);
		}
	} 
			
 
}

void DLPostInsert (tDLList *L, int val) {
/*
** Vloží prvek za aktivní prvek seznamu L.
** Pokud nebyl seznam L aktivní, nic se neděje.
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/
	if (L->Act != NULL){										//ak je zoznam aktivny
		tDLElemPtr helper = malloc(sizeof(struct tDLElem));
		if (helper == NULL){									//nedostatok pamate = error
			DLError();
		}														//pamate je dost
		helper->data = val;
		helper->rptr = L->Act->rptr;
		helper->lptr = L->Act;
		L->Act->rptr = helper;		
		if (L->Act == L->Last){ 								//ak je aktivny prvok poslednym
			L->Last = helper; 									//korekcia konca
		}
		else{ 													//ak nieje poslednym
			helper->rptr->lptr = helper;						//naviazanie dalsieho prvku na vlozeny prvok
		}
	} 		 
}

void DLPreInsert (tDLList *L, int val) {
/*
** Vloží prvek před aktivní prvek seznamu L.
** Pokud nebyl seznam L aktivní, nic se neděje.
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/
	if (L->Act != NULL){										//ak je zoznam aktivny
		tDLElemPtr helper = malloc(sizeof(struct tDLElem));
		if (helper == NULL){									//nedostatok pamate = error
			DLError();
		}														//pamate je dost
		helper->data = val;
		helper->lptr = L->Act->lptr;
		helper->rptr = L->Act;
		L->Act->lptr = helper;		
		if (L->Act == L->First){ 								//ak je aktivny prvok prvym
			L->First = helper; 									//korekcia konca
		}
		else{ 													//ak nieje prvym
			helper->lptr->rptr = helper;						//naviazanie predosleho prvku na vlozeny prvok
		}
	} 		 
}

void DLCopy (tDLList *L, int *val) {
/*
** Prostřednictvím parametru val vrátí hodnotu aktivního prvku seznamu L.
** Pokud seznam L není aktivní, volá funkci DLError ().
**/
	if(L->Act != NULL){			//ak je aktivny
		*val = L->Act->data;	//val vracia hodnotu aktivneho prvku
	}		
	else{						//ak nieje aktivny
		DLError();				//volanie erroru
	}
}

void DLActualize (tDLList *L, int val) {
/*
** Přepíše obsah aktivního prvku seznamu L.
** Pokud seznam L není aktivní, nedělá nic.
**/
	if(L->Act != NULL){
		L->Act->data = val;
	}	
}

void DLSucc (tDLList *L) {
/*
** Posune aktivitu na následující prvek seznamu L.
** Není-li seznam aktivní, nedělá nic.
** Všimněte si, že při aktivitě na posledním prvku se seznam stane neaktivním.
**/
	if(L->Act != NULL){ 			//ak je zoznam aktivny
		if(L->Act->rptr != NULL){	//ak dlasie prvok za aktivnym nieje prazdne miesto
			L->Act = L->Act->rptr; 	//posunie aktivitu
		}
		else{
			L->Act = NULL; 			//ak bol aktivny posledny, aktivita sa straca
		}
	}	
}


void DLPred (tDLList *L) {
/*
** Posune aktivitu na předchozí prvek seznamu L.
** Není-li seznam aktivní, nedělá nic.
** Všimněte si, že při aktivitě na prvním prvku se seznam stane neaktivním.
**/
	if(L->Act != NULL){ 			//ak je zoznam aktivny
		if(L->Act->lptr != NULL){	//ak predchadzajuci prvok v zozname nieje prazdne miesto
			L->Act = L->Act->lptr; 	//posunie aktivitu
		}
		else{
			L->Act = NULL; 			//ak bol aktivny posledny, aktivita sa straca
		}
	}	
}

int DLActive (tDLList *L) {
/*
** Je-li seznam L aktivní, vrací nenulovou hodnotu, jinak vrací 0.
** Funkci je vhodné implementovat jedním příkazem return.
**/
 	return ((L->Act != NULL) ? 1 : 0);
}

/* Konec c206.c*/
