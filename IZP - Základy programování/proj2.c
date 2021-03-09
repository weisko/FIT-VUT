#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

#define EPS 0.1e-10
#define STATIC_C 1.5

double taylor_tan(double x, unsigned int n);
double cfrac_tan(double x, unsigned int n);
double epsilon (double x);
int range_err(double a);
int c_range_err (double a);

int main(int argc, char *argv[])
{

    if (strcmp("--help", argv[1]) == 0)
    {
        printf("--tan porovna presnosti vypoctu tangens uhla A (v radianoch) medzi volanim tan z matematickej knihovne, a s vypoctom tangens pomocou Taylorovho polynomu a zretazenych zlomkov. ");
        printf("Argumenty N a M udavaju, v ktorych iteraciach iteracneho vypoctu ma porovnanie prebiehat. 0 < N <= M < 14\n");
        printf("=>./proj2 --tan A N M <=\n");
        printf("-m vypocita a zmeria vzdialenosti.\n");
        printf("\tUhol ALFA je dani argumentom A v radianoch. Program vypocita a vypise vzdialenost meraneho objektu. 0 < A <= 1.4 < PI/2.\n");
        printf("\tAk je zadany, uhol BETA udava argument B v radianoch. Program vypocita a vypise aj vysku meraneho objektu. 0 < B <= 1.4 < PI/2\n");
        printf("\tArgument -c nastavuje vysku meracieho pristroja c pre vypocet. Vyska c je dana argumentom X (0 < X <= 100). Argument je volitelny - implicitna vyska je 1.5 metra.\n");
        printf("=>./proj2 [-c X] -m A [B]<=\n");
    }

    if (strcmp("-m", argv[1]) == 0)
    {
        double alpha = 0, dlzka_a;
        alpha = strtof(argv[2], NULL);
        if (range_err(alpha) == 1)              //error na rozsah uhla ALFA
        {
            return 1;
        }
        dlzka_a = STATIC_C / epsilon(alpha);
        printf("%.10e\n", dlzka_a);
        if(argc >= 4)
        {
            double beta = 0, vyska_b;
            beta = strtof(argv[3], NULL);
            if (range_err(beta) == 1)            //error na rozsah uhla BETA
            {
                return 1;
            }
            vyska_b = (epsilon(beta) * dlzka_a) + STATIC_C;
            printf("%.10e\n",vyska_b);
        }
    }

    if (strcmp("-c", argv[1]) == 0)
    {
        double cecko = strtof(argv[2], NULL);
        if (c_range_err(cecko) == 1)
        {
            return 1;
        }
        double alpha = 0, dlzka_a;
        alpha = strtof(argv[4], NULL);
        if (range_err(alpha) == 1)              //error na rozsah uhla ALFA
        {
            return 1;
        }
        dlzka_a = strtof(argv[2], NULL) / epsilon(alpha);
        printf("%.10e\n", dlzka_a);
        if(argc >= 6)
        {
            double beta = 0, vyska_b;
            beta = strtof(argv[5], NULL);
            if (range_err(beta) == 1)            //error na rozsah uhla BETA
            {
                return 1;
            }
            vyska_b = (epsilon(beta) * dlzka_a) + strtof(argv[2], NULL);
            printf("%.10e\n",vyska_b);
        }
    }

    if (strcmp("--tan", argv[1]) == 0)
    {
        double x = strtof(argv[2], NULL);
        printf("epsilon tang: %.30e\nhodnota math: %.30e\n", epsilon(x), tan(x));
        for (int n = strtol(argv[3], NULL, 10); n <= strtol(argv[4], NULL, 10); n++)
        {
       // double math_func = tan(x);
     //   printf("%d %e %e %e %e %e\n",n, math_func,taylor_tan(x,n),math_func-taylor_tan(x,n),cfrac_tan(x,n),math_func-cfrac_tan(x,n));
        }
    }

}

double taylor_tan(double x, unsigned int n)
{
    double citatel[] = {1, 1, 2, 17, 62, 1382, 21844, 929569, 6404582, 443861162, 18888466084, 113927491862, 58870668456604};
    double menovatel[] = {1, 3, 15, 315, 2835, 155925, 6081075, 638512875, 10854718875, 1856156927625, 194896477400625, 49308808782358125, 3698160658676859375};
    double xp, a , b , tangens = 0;

    xp = x;
    for(unsigned int i = 0; i < n; i++)
    {
        a = citatel[i];
        b = menovatel[i];
        tangens = tangens + (xp*a)/b;
        xp = xp * x * x;
    }
    return tangens;
}

double cfrac_tan(double x, unsigned int n)
{
    double cf = 0;
    double a;
    double b2 = x*x;
    for(double i = n-1; i > 0; i--)
    {
        a = i*2 -1;
        cf = b2 / ((a+2)-cf);
    }
    return x / (1.0-cf);
}

double epsilon (double x)
{
    double n = 1, y = 0, yp = 0;
	do
	{
	    yp = y;
	    y = cfrac_tan(x,n);
		n++;
	} while (fabs(y - yp) > EPS);
    return y;
}
/*
double dlzka_d(double x)
{

    double dlzka;
    dlzka = STATIC_C / epsilon(x);
    return dlzka;
}

double vyska_v(double x, double dlzka)
{
    double vyska;
    vyska = epsilon(x) * dlzka_d()

    return vyska;
}
*/
int range_err (double a)
{
    if (a < 0 || a > 1.4)
    {
        fprintf(stderr,"ERROR: Uhol je v zlom intervale!\n");
        return 1;
    }
    else
        return 0;
}

int c_range_err (double a)
{
    if (a < 0 || a > 100 )
    {
        fprintf(stderr,"ERROR: Vyska je v zlom intervale!\n");
        return 1;
    }
    else
        return 0;
}






