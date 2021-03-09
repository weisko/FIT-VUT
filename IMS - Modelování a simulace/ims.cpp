// Authors: Richard Borbely (xborbe00) & Daniel Weis (xweisd00), VUT FIT BRNO

#include <string.h>
#include <iostream>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <ctype.h>
#include <locale>

using namespace std;

#define SlovakYearly 466
#define CzechYearly 150
#define GermanYearly 24
#define EuropeYearly 200
#define USYearly 425

#define SlovakDaily 1.2767
#define CzechDaily 0.411
#define GermanDaily 0.06575
#define EuropeDaily 0.548
#define USDaily 1.1644

#define SinglePaperBagEmissions 0.08        // kg
#define SinglePlasticBagEmissions 0.04      // kg

#define SinglePaperBagEnergyUsage 2.622     // MJ
#define SinglePlasticBagEnergyUsage 0.763   // MJ

#define SinglePaperBagWaterUsage 0.264      // l
#define SinglePlasticBagWaterUsage 0.015    // l

#define SinglePlasticBagCost 0.085          // €
#define SinglePaperBagCost 0.26             // €

#define PlasticBagWeight 0.084              // kg
#define PaperBagWeight 0.124                // kg

#define PlasticFossilFuelUse 0.0149         // kg
#define PaperFossilFuelUse 0.0232           // kg

#define PlasticMunicipalWaste 0.007         // kg
#define PaperMunicipalWaste 0.0339          // kg

// ******** RECYCLING *********
#define RplasticClimatic 8.2e-2
#define RplasticOzone 5.6e-9
#define RplasticEutrofization 7.9e-7
#define RplasticSeaEutrofization 3.3e-5
#define RplasticEnergy 1.3e+0
#define RplasticWater 8.6e-2

#define RpaperClimatic 1.1e-1
#define RpaperOzone 1.9e-8
#define RpaperEutrofization 1.8e-5
#define RpaperSeaEutrofization 1.6e-4
#define RpaperEnergy 2.2e+0
#define RpaperWater 1.6e+0

// ******* INCINERATION *********
#define IplasticClimatic 1.1e-1
#define IplasticOzone 1.2e-9
#define IplasticEutrofization -5.6e-7
#define IplasticSeaEutrofization 2.3e-5
#define IplasticEnergy 1.7e+0
#define IplasticWater 4.4e-2

#define IpaperClimatic 6.0e-2
#define IpaperOzone 1.2e-8
#define IpaperEutrofization 1.7e-5
#define IpaperSeaEutrofization 1.4e-4
#define IpaperEnergy 1.2e+0
#define IpaperWater 3.4e-1

string days;
string country;
string factor;
bool additional;

bool isNumber(const std::string& s)
{
    std::string::const_iterator it = s.begin();
    while (it != s.end() && std::isdigit(*it)) ++it;
    return !s.empty() && it == s.end();
}

int parseArguments(int argc, char *argv[]) {
        int opt;
        bool factorSet;

        while((opt = getopt(argc, argv, "d:c:f:a")) != -1)
        {
            switch(opt)
            {
                case 'd':
                    days = optarg;
                    break;   
                case 'c':
                    country = optarg;
                    break;
                case 'f':
                    factor = optarg;
                    factorSet = true;
                    break;      
                case 'a':
                    additional = true;
                    break;  
            }
        }

        if(!isNumber(days)) {
            std::cerr << "You have to enter a number to specify the number of days." <<  std::endl;
            exit(1);
        }

        if((factor != "emissions" && factor != "energy" && factor != "water" && factor != "cost" && factor != "weight" && factor != "fuel" && factor != "waste") && additional == false) {
            std::cerr << "You have to enter a valid factor or the parameter to show the additional info." <<  std::endl;
            exit(1);
        }

        if(country != "eu" && country != "sk" && country != "cz" && country != "de" && country != "usa") {
            std::cerr << "You have to enter a country which is available." <<  std::endl;
            exit(1);
        }

        if((factorSet == true) && (additional == true)){
            std::cerr << "You can not specify a factor in the additional information." <<  std::endl;
            exit(1);
        }

        if(days == "0") {
            std::cerr << "You can not enter 0 for simulation." <<  std::endl;
            exit(1);
        }

        return 0;
    }

int main(int argc, char *argv[])  
{ 
    double emissions = 0;
    double energyUsage = 0;
    double waterUsage = 0;
    double cost = 0;
    double weight = 0;
    double bagsNumber = 0;
    double fossilFuel = 0;
    double municipal = 0;

    double paperEmissions = 0;
    double paperEnergyUsage = 0;
    double paperWaterUsage = 0;
    double paperCost = 0;
    double paperWeight = 0;
    double paperBagsNumber = 0;
    double paperFossilFuel = 0;
    double paperMunicipal = 0;

    // ******** RECYCLING *********
    double RRplasticClimatic = 0;
    double RRplasticOzone = 0;
    double RRplasticEutrofization = 0;
    double RRplasticSeaEutrofization = 0;
    double RRplasticEnergy = 0;
    double RRplasticWater = 0;

    double RRpaperClimatic = 0;
    double RRpaperOzone = 0;
    double RRpaperEutrofization = 0;
    double RRpaperSeaEutrofization = 0;
    double RRpaperEnergy = 0;
    double RRpaperWater = 0;

    // ******* INCINERATION *********
    double IIplasticClimatic = 0;
    double IIplasticOzone = 0;
    double IIplasticEutrofization = 0;
    double IIplasticSeaEutrofization = 0;
    double IIplasticEnergy = 0;
    double IIplasticWater = 0;

    double IIpaperClimatic = 0;
    double IIpaperOzone = 0;
    double IIpaperEutrofization = 0;
    double IIpaperSeaEutrofization = 0;
    double IIpaperEnergy = 0;
    double IIpaperWater = 0;

    parseArguments(argc, argv);

    int nDays = std::stoi(days);

    double dailyUsage, n;

    if(country == "sk") {
        dailyUsage = SlovakDaily;
        n = SlovakYearly / (53 * 1.6);
    }
    else if(country == "cz") {
        dailyUsage = CzechDaily;
        n = CzechYearly / (53 * 1.6);
    }
    else if(country == "de") {
        dailyUsage = GermanDaily;
        n = GermanYearly / (53 * 1.6);
    }
    else if(country == "eu") {
        dailyUsage = EuropeDaily;
        n = EuropeYearly / (53 * 1.6);
    } 
    else if(country == "usa") {
        dailyUsage = USDaily;
        n = USYearly / (53 * 1.6);
    } 

    for(int i = 0; i < nDays; i++) {

        bagsNumber += dailyUsage;
        emissions += SinglePlasticBagEmissions * dailyUsage;
        energyUsage += SinglePlasticBagEnergyUsage * dailyUsage;
        waterUsage += SinglePlasticBagWaterUsage * dailyUsage;
        cost += SinglePlasticBagCost * dailyUsage;
        weight += PlasticBagWeight * dailyUsage;
        fossilFuel += PlasticFossilFuelUse * dailyUsage;
        municipal += PlasticMunicipalWaste * dailyUsage;

        RRplasticClimatic += RplasticClimatic * dailyUsage;
        RRplasticOzone += RplasticOzone * dailyUsage;
        RRplasticEutrofization += RplasticSeaEutrofization * dailyUsage;
        RRplasticSeaEutrofization += RplasticSeaEutrofization * dailyUsage;
        RRplasticEnergy += RplasticEnergy * dailyUsage;
        RRplasticWater += RplasticWater * dailyUsage;

        IIplasticClimatic += IplasticClimatic * dailyUsage;
        IIplasticOzone += IplasticOzone * dailyUsage;
        IIplasticEutrofization += IplasticSeaEutrofization * dailyUsage;
        IIplasticSeaEutrofization += IplasticSeaEutrofization * dailyUsage;
        IIplasticEnergy += IplasticEnergy * dailyUsage;
        IIplasticWater += IplasticWater * dailyUsage;
    }

    double target;
    if(factor == "emissions") target = emissions;
    else if(factor == "energy") target = energyUsage;
    else if(factor == "water") target = waterUsage;
    else if(factor == "cost") target = cost;
    else if(factor == "weight") target = weight;
    else if(factor == "fuel") target = fossilFuel;
    else if(factor == "waste") target = municipal;

    for(int i = 0; i < nDays; i++) {

        paperEmissions += SinglePaperBagEmissions * dailyUsage;
        paperEnergyUsage += SinglePaperBagEnergyUsage * dailyUsage;
        paperWaterUsage += SinglePaperBagWaterUsage * dailyUsage;
        paperCost += SinglePaperBagCost * dailyUsage;
        paperWeight += PaperBagWeight * dailyUsage;
        paperBagsNumber += dailyUsage;
        paperFossilFuel += PaperFossilFuelUse * dailyUsage;
        paperMunicipal += PaperMunicipalWaste * dailyUsage;

        RRpaperClimatic += RpaperClimatic * dailyUsage;
        RRpaperOzone += RpaperOzone * dailyUsage;
        RRpaperEutrofization += RpaperSeaEutrofization * dailyUsage;
        RRpaperSeaEutrofization += RpaperSeaEutrofization * dailyUsage;
        RRpaperEnergy += RpaperEnergy * dailyUsage;
        RRpaperWater += RpaperWater * dailyUsage;

        IIpaperClimatic += IpaperClimatic * dailyUsage;
        IIpaperOzone += IpaperOzone * dailyUsage;
        IIpaperEutrofization += IpaperSeaEutrofization * dailyUsage;
        IIpaperSeaEutrofization += IpaperSeaEutrofization * dailyUsage;
        IIpaperEnergy += IpaperEnergy * dailyUsage;
        IIpaperWater += IpaperWater * dailyUsage;

        if(!additional) {
            if(factor == "emissions") {
                if(paperEmissions >= target) break;
            }
            if(factor == "energy") {
                if(paperEnergyUsage >= target) break;
            }
            if(factor == "water") {
                if(paperWaterUsage >= target) break;
            }
            if(factor == "cost") {
                if(paperCost >= target) break;
            }
            if(factor == "weight") {
                if(paperWeight >= target) break;
            }
            if(factor == "fuel") {
                if(paperFossilFuel >= target) break;
            }
            if(factor == "waste") {
                if(paperMunicipal >= target) break;
            }
        }
    }

    double x = n / (n*(paperBagsNumber/bagsNumber));
    x = x + 0.5 - (x < 0);
    int y = (int)x;

    if(!additional) {
        cout << "\n***************** Information about plastic bags ******************\n" << endl;
        cout << "Daily plastic bag usage for citizen of " << country << ": "<< dailyUsage << " pieces" << endl;
        cout << "Number of used bags: " << bagsNumber << " pieces" << endl;
        cout << "Number of used plastic bags per a single shopping: " << n << endl;
        cout << "Emmissions: " << emissions << " kg of CO2" << endl;
        cout << "Energy Usage: " << energyUsage << " MJ" << endl;
        cout << "Fossil fuel usage: " << fossilFuel << " kg" << endl;
        cout << "Municipal solid waste: " << municipal << " kg" << endl;
        cout << "Water Usage: " << waterUsage << " l" << endl;
        cout << "Manufacturing cost: " << cost << " €" << endl;
        cout << "Total weight: " << weight << " kg\n" << endl;
        cout << "*******************************************************************\n" << endl;
        cout << "If we want to keep the amount of " << factor << " of paper bags on the \n" 
        << "plastic level, we have to reduce the number of used bags to " << paperBagsNumber/bagsNumber*100 << "%.\n" << endl;
        cout << "*******************************************************************\n" << endl;
        cout << "************* Details about alternative - paper bags **************\n" << endl;
        cout << "Number of used paper bags: " << paperBagsNumber << " pieces" << endl;
        cout << "Emmissions: " << paperEmissions << " kg of CO2" << endl;
        cout << "Energy Usage: " << paperEnergyUsage << " MJ" << endl;
        cout << "Fossil fuel usage: " << paperFossilFuel << " kg" << endl;
        cout << "Municipal solid waste: " << paperMunicipal << " kg" << endl;
        cout << "Water Usage: " << paperWaterUsage << " l" << endl;
        cout << "Manufacturing cost: " << paperCost << " €" << endl;
        cout << "Total weight: " << paperWeight << " kg\n" << endl;
        cout << "*******************************************************************\n" << endl;
        cout << " - In this case we have to reduce the number \n" 
        << "    of new bags per shopping to " << n*(paperBagsNumber/bagsNumber) << endl;
        cout << " - That means that every person should reuse\n" 
        << "    a single bought bag for approximatly " << y << " times\n" << endl;
        cout << "*******************************************************************\n" << endl;
    }

    else {
        double z = bagsNumber;
        z = z + 0.5 - (z < 0);
        int roundBagsNumber = (int)z;
        
        cout << "\n************ An average citizen of " << country << " uses " << roundBagsNumber << " plastic bags in " << nDays << " days *************" << endl;

        cout << "\n*********************** Recycling data of " << roundBagsNumber << " plastic bags: ************************\n" << endl;
        cout << "Climatic changes: " << RRplasticClimatic << " kg of C02" << endl;
        cout << "Ozone hole: " << RRplasticOzone << "kg of CFC11" << endl;
        cout << "Fresh Water Eutrofization: " << RRplasticEutrofization << " kg of P" << endl;
        cout << "Sea Water Eutrofization: " << RRplasticSeaEutrofization << " kg of N" << endl;
        cout << "Used energy: " << RRplasticEnergy << " MJ" << endl;
        cout << "Used water: " << RRplasticWater << " l" << endl;
 
        cout << "\n********************* Incineration data of " << roundBagsNumber << " plastic bags: ***********************\n" <<endl;
        cout << "Climatic changes: " << IIplasticClimatic << " kg of C02" << endl;
        cout << "Ozone hole: " << IIplasticOzone << "kg of CFC11" << endl;
        cout << "Fresh Water Eutrofization: " << IIplasticEutrofization << " kg of P" << endl;
        cout << "Sea Water Eutrofization: " << IIplasticSeaEutrofization << " kg of N" << endl;
        cout << "Used energy: " << IIplasticEnergy << " MJ" << endl;
        cout << "Used water: " << IIplasticWater << " l" << endl;

        cout << "\n************************ Recycling data of " << roundBagsNumber << " paper bags: *************************\n" << endl;
        cout << "Climatic changes: " << RRpaperClimatic << " kg of C02" << endl;
        cout << "Ozone hole: " << RRpaperOzone << "kg of CFC11" << endl;
        cout << "Fresh Water Eutrofization: " << RRpaperEutrofization << " kg of P" << endl;
        cout << "Sea Water Eutrofization: " << RRpaperSeaEutrofization << " kg of N" << endl;
        cout << "Used energy: " << RRpaperEnergy << " MJ" << endl;
        cout << "Used water: " << RRpaperWater << " l" << endl;

        cout << "\n********************** Incineration data of " << roundBagsNumber << " paper bags: ************************\n" <<endl;
        cout << "Climatic changes: " << IIpaperClimatic << " kg of C02" << endl;
        cout << "Ozone hole: " << IIpaperOzone << "kg of CFC11" << endl;
        cout << "Fresh Water Eutrofization: " << IIpaperEutrofization << " kg of P" << endl;
        cout << "Sea Water Eutrofization: " << IIpaperSeaEutrofization << " kg of N" << endl;
        cout << "Used energy: " << IIpaperEnergy << " MJ" << endl;
        cout << "Used water: " << IIpaperWater << " l" << endl;
        cout << "\n**********************************************************************************\n" << endl;
    }

    return 0;
}