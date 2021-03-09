#
# Warning: you may need more libraries than are included here on the
# build line.  The agent frequently needs various libraries in order
# to compile pieces of it, but is OS dependent and we can't list all
# the combinations here.  Instead, look at the libraries that were
# used when linking the snmpd master agent and copy those to this
# file.
#

CC=gcc

LOGIN=xweisd00
FILES=xweisd00NetSnmpMIB.c xweisd00NetSnmpMIB.h MIB.txt README.txt Makefile

OBJS=xweisd00NetSnmpMIB.s
TARGETS=xweisd00NetSnmpMIB.so

CFLAGS=-I. `net-snmp-config --cflags`
BUILDLIBS=`net-snmp-config --libs`
BUILDAGENTLIBS=`net-snmp-config --agent-libs`
DLFLAGS=-fPIC -shared

all: $(TARGETS)

clean:
	rm $(OBJS) $(TARGETS)

tar:
	tar -cf $(LOGIN).tar $(FILES)

rmtar:
	rm -f $(LOGIN).tar

xweisd00NetSnmpMIB.so: xweisd00NetSnmpMIB.c Makefile
	$(CC) $(CFLAGS) $(DLFLAGS) -c -o xweisd00NetSnmpMIB.o xweisd00NetSnmpMIB.c
	$(CC) $(CFLAGS) $(DLFLAGS) -o xweisd00NetSnmpMIB.so xweisd00NetSnmpMIB.o
