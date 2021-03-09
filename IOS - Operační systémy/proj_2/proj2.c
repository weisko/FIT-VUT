/*###################################
	autor: Daniel Weis - xweisd00   #
#####################################*/

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <semaphore.h>
#include <fcntl.h>
#include <sys/shm.h>
#include <time.h>
#include <signal.h>

#define FILE_OUT "proj2.out"
#define EXIT_ARG 1
#define EXIT_SEM 1
#define EXIT_OK 0
#define LOCKED 0
#define UNLOCKED 1

#define SEMAPHORE1_NAME "/xweisd00_semaphore_1"
#define SEMAPHORE2_NAME "/xweisd00_semaphore_2"
#define SEMAPHORE3_NAME "/xweisd00_semaphore_3"
#define SEMAPHORE4_NAME "/xweisd00_semaphore_4"
#define SEMAPHORE5_NAME "/xweisd00_semaphore_5"

sem_t *mutex = NULL;
sem_t *multiplex = NULL;
sem_t *boarding = NULL;
sem_t *unboarding = NULL;
sem_t *writing = NULL;

int *sh_counter = NULL;
int sh_counter_ID = 1;
int *CR_riders = NULL;
int CR_riders_ID = 1;
int *depart_counter = NULL;
int depart_counter_ID = 1;
int *boarding_cnt = NULL;
int boarding_cnt_ID = 1;
int *passenger = NULL;
int passenger_ID = 1;

int set_resources();
void clear_resources();
int set_semaphores();
void clear_semaphores();
void print_bus(char *word);
void print_bus_boarding(char *word, int CR);
void print_riders(int cislo, char *word);
void print_riders_boarding(int cislo, char *word, int counter);

FILE *file = NULL;

int main (int argc, char  *argv[])
{
	set_resources();
	srand((unsigned)time(NULL));
	setbuf(stdout, NULL);   
    setbuf(stderr, NULL);

	if(set_semaphores()!=0)
    {
        fprintf(stderr, "Error-Semaphores are not set properly\n");
        clear_resources();
        clear_semaphores();
        return EXIT_SEM;
    }

    if(set_resources()!=0)
    {
        fprintf(stderr, "Error-Resources are not set properly\n");
        clear_resources();
        clear_semaphores();
        return EXIT_SEM;
    }

	if (argc != 5)
	{
		fprintf(stderr, "ERROR-invalid number of arguments\n");
		return EXIT_ARG;
	} 

	if ((file = fopen("proj2.out", "w")) == NULL)
	{
		fprintf(stderr, "ERROR-cannot open the file");
		return EXIT_ARG;
	}

	char *first_err = NULL;
	char *second_err = NULL;
	char *third_err = NULL;
	char *fourth_err = NULL;

	int R = 0, C = 0, ART = 0, ABT = 0;
	
	R = strtol(argv[1], &first_err, 10);
	C = strtol(argv[2], &second_err, 10);
	ART = strtol(argv[3], &third_err, 10);
	ABT = strtol(argv[4], &fourth_err, 10);

	if (*first_err || *second_err || *third_err || *fourth_err)
	{
		fprintf(stderr, "ERROR-argument is not a number\n");
		return EXIT_ARG;
	}

	if (R <= 0 || C <= 0 || ART < 0 || ART > 1000 || ABT < 0 || ABT > 1000)
	{
		fprintf(stderr, "ERROR-arguments out of intervals\n");
		return EXIT_ARG;
	}
	

	//..........................>BUS<.....................
	int x = 0;
	pid_t bus = fork();
	if (bus == 0)
	{	
		sem_wait(writing);
		print_bus("start");
		sem_post(writing);
		for (int i = 0; i < R; i++)
		{	
			sem_wait(writing);
			print_bus("arrival");
			sem_post(writing);
			sem_wait(mutex);

			if (*passenger < C)
            {
                x = *passenger;
            }
            else
            {
                x = C;
            }

            for (int i = 0; i < x; i++)
            {
            	if (i==0)
            	{
            		sem_wait(writing);
            		print_bus_boarding("start boarding", *CR_riders);
            		sem_post(writing);
            	}
            	sem_post(unboarding);
            	sem_wait(boarding);
            	(*CR_riders)--;
            	if ((i+1) == x)
            	{
            		sem_wait(writing);
            		print_bus_boarding("end boarding", *CR_riders);
            		sem_post(writing);
            	}
            }
            if ((*passenger-C) > 0)
            {
            	*passenger = (*passenger - C);
            }
            else
            {
            	*passenger = 0;
            }
			sem_post(mutex);
			sem_wait(writing);
			print_bus("depart");
			sem_post(writing);
			

			usleep(rand()%(ABT+1)*1000);
			
			sem_wait(writing);
			print_bus("end");
			sem_post(writing);

			while(*depart_counter)
			{
				sem_post(multiplex);
				(*depart_counter)--;
			}

			if(*boarding_cnt == R)
			{
				break;
			}
		}
		sem_wait(writing);
		print_bus("finish");
		sem_post(writing);

		if (bus < 0)
		{
			fprintf(stderr, "ERROR-fork error\n");
			clear_semaphores();
			clear_resources();
			exit(1);
		}

		clear_resources();
		clear_semaphores();
		exit(0);
	}

	//.........................>RIDERS<.......................
	int I = 0;
	pid_t riders;
	pid_t riders_Master = fork();
	if (riders_Master == 0)
	{
		for (int i = 0; i < R; i++)
		{
			I++;
			usleep(rand()%(ART+1)*1000);
			riders =  fork();
			if (riders == 0)
			{
				sem_wait(mutex);
				(*passenger)++;
				sem_wait(writing);
				print_riders(I, "start");
				sem_post(writing);
				sem_post(mutex);
				
				sem_wait(writing);
				(*CR_riders)++;
				print_riders_boarding(I, "enter", *CR_riders);
				sem_post(writing);
				

				sem_wait(unboarding);
				sem_wait(writing);
				print_riders(I, "boarding");
				(*boarding_cnt)++;
				(*depart_counter)++;
				sem_post(writing);
				sem_post(boarding);

				sem_wait(multiplex);
				sem_wait(writing);
				print_riders(I, "finish");
				sem_post(writing);
				//semaphore
				
				clear_resources();
				clear_semaphores();
				exit(0);
			}
			if (riders < 0)
			{
				fprintf(stderr, "ERROR-fork error\n");
				clear_semaphores();
				clear_resources();
				exit(1);
			}

		}

		for (int i = 0; i < R; i++)
		{
			waitpid(riders, NULL,0);
		}

		if (riders > 0)
		{
			while(wait(NULL) > 0);
		}
		clear_semaphores();
		clear_resources();
		exit(0);
	}
	if (riders_Master < 0)
	{
		fprintf(stderr, "ERROR-fork error\n");
		clear_semaphores();
		clear_resources();
		exit(1);
	}
	waitpid(bus, NULL, 0);
    waitpid(riders_Master, NULL, 0);
	clear_semaphores();
	clear_resources();
	return 0;
}

int set_resources()
{
	if ((sh_counter_ID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1)
    {
        return 2;
    }
    if ((sh_counter = shmat(sh_counter_ID, NULL, 0)) == NULL)
    {
        return 2;
    }
    if ((CR_riders_ID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1)
    {
        return 2;
    }
    if ((CR_riders = shmat(CR_riders_ID, NULL, 0)) == NULL)
    {
        return 2;
    }
    if ((depart_counter_ID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1)
    {
        return 2;
    }
    if ((depart_counter = shmat(depart_counter_ID, NULL, 0)) == NULL)
    {
        return 2;
    }
    if ((boarding_cnt_ID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1)
    {
        return 2;
    }
    if ((boarding_cnt = shmat(boarding_cnt_ID, NULL, 0)) == NULL)
    {
        return 2;
    }
    if ((passenger_ID = shmget(IPC_PRIVATE, sizeof(int), IPC_CREAT | 0666)) == -1)
    {
        return 2;
    }
    if ((passenger = shmat(passenger_ID, NULL, 0)) == NULL)
    {
        return 2;
    }

    *sh_counter = 1;
    return 0;
    
}

void clear_resources()
{
	shmctl(sh_counter_ID, IPC_RMID, NULL);
	shmctl(CR_riders_ID, IPC_RMID, NULL);
	shmctl(depart_counter_ID, IPC_RMID, NULL);
	shmctl(boarding_cnt_ID, IPC_RMID, NULL);
	shmctl(passenger_ID, IPC_RMID, NULL);

}

int set_semaphores()
{
	if ((mutex = sem_open(SEMAPHORE1_NAME, O_CREAT | O_EXCL, 0666, UNLOCKED)) == SEM_FAILED)
    {
        return 2;
    }
    if ((multiplex = sem_open(SEMAPHORE2_NAME, O_CREAT | O_EXCL, 0666, LOCKED)) == SEM_FAILED)
    {
        return 2;
    }
    if ((boarding = sem_open(SEMAPHORE3_NAME, O_CREAT | O_EXCL, 0666, LOCKED)) == SEM_FAILED)
    {
        return 2;
    }
    if ((unboarding = sem_open(SEMAPHORE4_NAME, O_CREAT | O_EXCL, 0666, LOCKED)) == SEM_FAILED)
    {
        return 2;
    }
    if ((writing = sem_open(SEMAPHORE5_NAME, O_CREAT | O_EXCL, 0666, UNLOCKED)) == SEM_FAILED)
    {
        return 2;
    }

  	return 0;

}

void clear_semaphores()
{
	sem_close(mutex);
    sem_unlink(SEMAPHORE1_NAME);

    sem_close(multiplex);
    sem_unlink(SEMAPHORE2_NAME);

    sem_close(boarding);
    sem_unlink(SEMAPHORE3_NAME);

    sem_close(unboarding);
    sem_unlink(SEMAPHORE4_NAME);

    sem_close(writing);
    sem_unlink(SEMAPHORE5_NAME);
}


void print_bus(char*word)
{
	fprintf(file, "%d\t: BUS\t: %s \n", (*sh_counter)++, word);
	fflush(file);
}

void print_bus_boarding(char *word, int CR)
{
	fprintf(file, "%d\t: BUS\t: %s: %d\n",(*sh_counter)++, word, CR);
	fflush(file);
}

void print_riders(int cislo, char *word)
{
	fprintf(file, "%d\t: RID %d\t: %s\n",(*sh_counter)++, cislo++, word);
	fflush(file);
}

void print_riders_boarding(int cislo, char *word, int counter)
{
	fprintf(file, "%d\t: RID %d\t: %s: %d\n",(*sh_counter)++, cislo++, word, counter);
	fflush(file);
}	