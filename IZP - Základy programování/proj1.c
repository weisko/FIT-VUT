#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define RIADOK 43
#define ZNAKY 101

void bubble_sort(char *vystup)                  //bubblesort na abecedne zoradenie.
{
    int pomocna = strlen(vystup);
    int i = 0, temp = 0, j;

    for (i = 1; i <= (int)strlen(vystup); i++)
        {
            for(j = 0; j <= pomocna-i-1; j++)
            {
                if(vystup[j] > vystup[j+1])
                {
                    temp = vystup[j];
                    vystup[j] = vystup[j+1];
                    vystup[j+1] = temp;
                }
            }
        }
}

int riadok_error (int riadok)
{
    if (riadok > RIADOK+1)
    {
        fprintf(stderr, "prekroceny pocet adries!\n");
        return 1;
    }
}

int main(int argc, char **argv)
{
    int i;
    char vystup[ZNAKY] = {0};
    char pole[RIADOK][ZNAKY] = {0};
    char vstup[ZNAKY] = {0};
    char mesto[ZNAKY] = {0};
    int riadok = 0, stlpec = 0;
    char znak;


    if (argc == 1)                              //podmienka pre nezadany argument.
    {
         while((znak = getchar()) != EOF)
        {
            if (znak == '\n')
            {
                pole[riadok][stlpec] =0;
                riadok++;
                stlpec = 0;
            }
            else
            {
                if(znak >= 'A' && znak <= 'Z')
                {
                    pole[riadok][stlpec] = znak;
                }
                    else
                    {
                        pole[riadok][stlpec] = znak - 'a' + 'A';
                    }
                stlpec++;
            }
        }
        stlpec = 0;
        for (i = 0; i <= riadok; i++ )
        {
            if(0==strncmp(pole[i], vstup, strlen(vstup)))
            {
                if (strchr(vystup,pole[i][strlen(vstup)]) == NULL)
                {
                    vystup[stlpec] = pole[i][strlen(vstup)];
                    stlpec++;
                }
                strcpy(mesto, pole[i]);
            }
        }
        bubble_sort(vystup);
        printf("ENABLE: %s", vystup);

    }
    else
    {
        while((znak = getchar()) != EOF)                            //nacitavanie znakov
        {
            if (znak == '\n')
            {
                pole[riadok][stlpec] =0;
                riadok++;
                stlpec = 0;
            }
            else
            {
                if((znak >= 'A' && znak <= 'Z') || znak == ' ')
                {
                    pole[riadok][stlpec] = znak;
                }
                    else
                    {
                        pole[riadok][stlpec] = znak - 'a' + 'A';
                    }
                stlpec++;
            }
        }

        strcpy (vstup , argv[1]);
        for (i=0; i < (int)strlen(vstup);i++)
        {
            if(vstup[i] >= 'a' && vstup[i] <= 'z')
            {
                vstup[i] = vstup[i]-'a'+'A';
            }
        }

        stlpec = 0;
        for (i = 0; i <= riadok; i++ )
        {
            if(0==strncmp(pole[i], vstup, strlen(vstup)))
            {
                if (strchr(vystup,pole[i][strlen(vstup)]) == NULL)      //odstranenie duplikacii pomocou strchr
                {
                    vystup[stlpec] = pole[i][strlen(vstup)];
                    stlpec++;
                }
                strcpy(mesto, pole[i]);
            }
        }

        riadok_error(riadok);
        bubble_sort(vystup);
        vystup[stlpec] = 0;

        if(stlpec == 0)
            printf("not found\n");
        if(stlpec == 1)
            printf("FOUND %s \n", mesto);
        else
            printf("ENABLE: %s\n", vystup);
    }
        return 0;
}


