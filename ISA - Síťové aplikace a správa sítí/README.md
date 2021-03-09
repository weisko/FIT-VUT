xweisd00

Implementacia MIB modulu a dynamicky nacitatelne rozsirenie SNMP agenta net-snmp.

Funkcionalita:

1) vypis stringu s loginom - za pomoci snmpget:
	snmpget localhost MIB::nstAgentLoginObject.0
2) vypis aktualneho casu v RFC 3339 formate:
	snmpget localhost MIB::nstAgentDateTimeObject.0

Nedostatok:
	Read/write Int32 (.1.3.6.1.3.22.3)
	systemova informacia (.1.3.6.1.3.22.4)

zoznam suborov:
-Makefile
MIB.txt
README.md
xweisd00NetSnmpMIB.c
xweisd00NetSnmpMIB.h

na spustenie je nutne si vytvorit .so a.o cez makefile. Nasledne spusti snmp agenta.
