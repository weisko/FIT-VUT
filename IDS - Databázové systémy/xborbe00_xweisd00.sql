SET SERVEROUTPUT ON;
DROP SEQUENCE id_hraca_sequence;
DROP SEQUENCE id_zapasu_sequence;
DROP SEQUENCE id_rozhodca_sequence;
DROP SEQUENCE id_udalosti_sequence;
DROP SEQUENCE id_striedania_sequence;
DROP SEQUENCE id_udalosti_hraca_sequence;
DROP SEQUENCE id_udalosti_zapas_sequence;

DROP TABLE Hrac CASCADE CONSTRAINTS;
DROP TABLE Team CASCADE CONSTRAINTS;
DROP TABLE Zapas CASCADE CONSTRAINTS;
DROP TABLE Rozhodca CASCADE CONSTRAINTS;
DROP TABLE Striedanie CASCADE CONSTRAINTS;
DROP TABLE Udalost CASCADE CONSTRAINTS;
DROP TABLE Hrac_vykonal_Udalost CASCADE CONSTRAINTS;
DROP TABLE Udalost_v_Zapase CASCADE CONSTRAINTS;

CREATE TABLE Hrac (
 id_hraca NUMBER NOT NULL,
 cislo_dresu NUMBER NOT NULL,
 meno_hraca VARCHAR2(50) NOT NULL,
 datum_narodenia DATE NOT NULL,
 pozicia VARCHAR2(3) NOT NULL,
 narodnost_hraca VARCHAR2(50) NOT NULL,
 domovsky_klub VARCHAR2(50) NOT NULL,
 --štatistkiky
 pocet_strelenych_golov NUMBER,
 pocet_asistencii NUMBER,
 pocet_zltych_kariet NUMBER,
 pocet_cervenych_kariet NUMBER,
 nabehane_kilomentre NUMBER,
 ----
 CHECK (id_hraca >= 1),
 CHECK (cislo_dresu >= 1 AND cislo_dresu <= 99),
 CHECK (pocet_strelenych_golov >= 0),
 CHECK (pocet_asistencii >= 0),
 CHECK (pocet_zltych_kariet >= 0),
 CHECK (pocet_cervenych_kariet >= 0),
 CHECK (nabehane_kilomentre >= 0),
 ----
 CONSTRAINT HRAC_PK PRIMARY KEY (id_hraca)
);

CREATE SEQUENCE id_hraca_sequence;

CREATE OR REPLACE TRIGGER id_hraca_trigger
  BEFORE INSERT On Hrac
  FOR EACH ROW
  WHEN (new.id_hraca is NULL)
  BEGIN
   SELECT id_hraca_sequence.nextval
   INTO : new.id_hraca
   FROM dual;
  end;
  /
  
CREATE TABLE Team (
 narodnost_teamu VARCHAR2(20) NOT NULL,
 formacia NUMBER NOT NULL,
 trener VARCHAR2(50) NOT NULL,
 asistenti VARCHAR2(80) NOT NULL,
 manazer VARCHAR2(20) NOT NULL,
 skupina VARCHAR2(1) NOT NULL,
 --štatistiky
 pocet_strelenych_golov NUMBER,
 skore VARCHAR2(12) NOT NULL,
 pocet_priestupkov NUMBER,
 ----
 CONSTRAINT TEAM_PK PRIMARY KEY (narodnost_teamu)
);

CREATE TABLE Zapas (
 id_zapasu NUMBER NOT NULL,
 stadion VARCHAR2(30) NOT NULL,
 mesto VARCHAR2(30) NOT NULL,
 datum_cas DATE,
 vysledok VARCHAR2(5) NOT NULL,
 ucinkujuce_teamy VARCHAR2(30) NOT NULL,
 --Statistiky
 pocet_rohovych_kopov NUMBER,
 pocet_golov NUMBER,
 pocet_ofsajdov NUMBER,
 pocet_faulov NUMBER,
 pocasie VARCHAR2(50),
 ---
 zapas_team_domaci VARCHAR2(50),
 zapas_team_hostia VARCHAR2(50),
 zapas_rozhodca_hlavny NUMBER,
 zapas_rozhodca_asistent1 NUMBER,
 zapas_rozhodca_asistent2 NUMBER,
 zapas_rozhodca_asistent3 NUMBER,
  ---
 CHECK (id_zapasu >= 1),
 CHECK (pocet_rohovych_kopov >= 0),
 CHECK (pocet_golov >= 0),
 CHECK (pocet_ofsajdov >= 0),
 CHECK (pocet_faulov >= 0),
 ---
 CONSTRAINT ZAPAS_PK PRIMARY KEY (id_zapasu)
);

CREATE SEQUENCE id_zapasu_sequence;

CREATE TABLE Rozhodca (
 id_rozhodca NUMBER,
 meno_rozhodcu VARCHAR2(50) NOT NULL,
 typ_rozhodcu VARCHAR2(50) NOT NULL,
 --
 CHECK (id_rozhodca >= 1),
 --
 CONSTRAINT ROZHODCA_PK PRIMARY KEY (id_rozhodca)
);

CREATE SEQUENCE id_rozhodca_sequence;

-------SPECIALNA UDALOST
CREATE TABLE Striedanie (
 id_striedania NUMBER NOT NULL,
 presny_cas_striedania VARCHAR2(20) NOT NULL,
 id_striedajuci_hrac NUMBER,
 id_striedany_hrac NUMBER,
 id_striedanie_zapas NUMBER,
 ---
 CHECK (id_striedania >= 1),
 ---
 CONSTRAINT STRIEDANIE_PK PRIMARY KEY (id_striedania)
);

CREATE SEQUENCE id_striedania_sequence;

CREATE TABLE Udalost (
 id_udalosti NUMBER NOT NULL,
 typ VARCHAR2(20),
 akter VARCHAR2(20) NOT NULL,
 presny_cas_udalosti VARCHAR2(20) NOT NUlL,
 penalizacia VARCHAR2(20),
 ---
 CHECK (id_udalosti >= 1),
 ---
 id_zapasu NUMBER NOT NULL,
 CONSTRAINT UDALOST_PK PRIMARY KEY (id_udalosti)
);

CREATE SEQUENCE id_udalosti_sequence;

CREATE TABLE Hrac_vykonal_Udalost(
  id_hraca NUMBER NOT NULL,
  id_udalosti NUMBER NOT NULL,
  CONSTRAINT HRAC_UDALOST_PK PRIMARY KEY (id_hraca,id_udalosti),
  CONSTRAINT HRAC_VYKONAL_UDALOST_FK FOREIGN KEY (id_hraca) REFERENCES Hrac,
  CONSTRAINT UDALOST_VYKONANA_HRACOM_FK FOREIGN KEY (id_udalosti) REFERENCES Udalost
);

CREATE SEQUENCE id_udalosti_hraca_sequence;

CREATE TABLE Udalost_v_Zapase(
  id_udalosti NUMBER NOT NULL,
  id_zapasu NUMBER NOT NULL,
  CONSTRAINT UDALOST_ZAPAS_PK PRIMARY KEY (id_udalosti,id_zapasu),
  CONSTRAINT UDALOST_V_ZAPASE_FK FOREIGN KEY (id_udalosti) REFERENCES Udalost,
  CONSTRAINT V_ZAPASE_UDALOST_FK FOREIGN KEY (id_zapasu) REFERENCES Zapas
);

CREATE SEQUENCE id_udalosti_zapas_sequence;

------FOREIGN KEYS------
ALTER TABLE Hrac ADD CONSTRAINT NARODNOST_HRACA_FK FOREIGN KEY (narodnost_hraca) REFERENCES Team;
ALTER TABLE Striedanie ADD CONSTRAINT STRIEDAJUCI_HRAC_FK FOREIGN KEY (id_striedajuci_hrac) REFERENCES Hrac;
ALTER TABLE Striedanie ADD CONSTRAINT STREDANY_HRAC_FK FOREIGN KEY (id_striedany_hrac) REFERENCES Hrac;
ALTER TABLE Striedanie ADD CONSTRAINT STRIEDANIE_V_ZAPASE_FK FOREIGN KEY (id_striedanie_zapas) REFERENCES Zapas;
ALTER TABLE Zapas ADD CONSTRAINT ZAPAS_TEAM_DOMACI_FK FOREIGN KEY (zapas_team_domaci) REFERENCES Team;
ALTER TABLE Zapas ADD CONSTRAINT ZAPAS_TEAM_HOSTIA_FK FOREIGN KEY (zapas_team_hostia) REFERENCES Team;
ALTER TABLE Zapas ADD CONSTRAINT ZAPAS_ROZHODCA_FK FOREIGN KEY (zapas_rozhodca_hlavny) REFERENCES Rozhodca;
ALTER TABLE Zapas ADD CONSTRAINT ZAPAS_ROZHODCA_FK1 FOREIGN KEY (zapas_rozhodca_asistent1) REFERENCES Rozhodca;
ALTER TABLE Zapas ADD CONSTRAINT ZAPAS_ROZHODCA_FK2 FOREIGN KEY (zapas_rozhodca_asistent2) REFERENCES Rozhodca;
ALTER TABLE Zapas ADD CONSTRAINT ZAPAS_ROZHODCA_FK3 FOREIGN KEY (zapas_rozhodca_asistent3) REFERENCES Rozhodca;
ALTER TABLE Udalost ADD CONSTRAINT UDALOST_ZAPAS_FK FOREIGN KEY (id_zapasu) REFERENCES Zapas;

------INSERTS------
INSERT INTO Team VALUES ('Spain',433,'Luis Enrique','Roberto Moreno, Jesús Casas, José Manuel Ochotorena, Rafael Pol','Fernando Hierro','B',7,'W1-D2-L0',34);
INSERT INTO Team VALUES ('Portugal',442,'Fernando Santos','Ilídio Vale, Ricardo Santos, Jorge Rosário, Fernando Justino','Fernando Santos','B',6,'W1-D2-L0',55);

------------| Triggery |------------
CREATE OR REPLACE TRIGGER trg
BEFORE INSERT OR UPDATE ON Hrac
FOR EACH ROW
BEGIN
    IF :NEW.datum_narodenia >  ADD_MONTHS(SYSDATE, -(12 * 18))
    then
       RAISE_APPLICATION_ERROR(-20001, 'Hrac je mladsi ako 18 rokov');
    END IF;
END trg;
/

------Hraci zo Spanielska------
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,1, 'D. de Gea', TO_DATE('2005-11-7', 'yyyy-mm-dd'), 'GK', 'Spain', 'Manchester United F.C.',0,0,0,0,12);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,18, 'J. Alba', TO_DATE('1989-3-21', 'yyyy-mm-dd'), 'LB', 'Spain', 'F.C. Barcelona', 0,0,0,0,44);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,15, 'S. Ramos', TO_DATE('1986-3-30', 'yyyy-mm-dd'), 'CB', 'Spain', 'Real Madrid C.F.',0,0,0,0,41);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,3, 'G. Pique', TO_DATE('1987-2-2', 'yyyy-mm-dd'), 'CB', 'Spain', 'F.C. Barcelona', 0,0,1,0,38);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,4, 'Nacho', TO_DATE('1990-1-18', 'yyyy-mm-dd'), 'RB', 'Spain', 'Real Madrid C.F.', 1,0,0,0,16);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,6, 'A. Iniesta', TO_DATE('1984-5-11', 'yyyy-mm-dd'), 'LM', 'Spain', 'F.C. Barcelona', 0,1,0,0,32);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,5, 'S. Busquets', TO_DATE('1988-7-16', 'yyyy-mm-dd'), 'CM', 'Spain', 'F.C. Barcelona', 0,1,1,0,43);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,8, 'Koke', TO_DATE('1992-1-8', 'yyyy-mm-dd'), 'RM', 'Spain', 'Valencia C.F.', 0,2,0,0,29);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,22, 'Isco', TO_DATE('1992-4-21', 'yyyy-mm-dd'), 'LW', 'Spain', 'Real Madrid C.F.', 1,0,0,0,45);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,19, 'D. Costa', TO_DATE('1988-10-7', 'yyyy-mm-dd'), 'ST', 'Spain', 'Atletico Madrid', 3,0,0,0,32);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,21, 'D. Silva', TO_DATE('1986-1-8', 'yyyy-mm-dd'), 'RW', 'Spain', 'Manchester City F.C.', 0,0,0,0,37);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,2, 'D. Carvajal', TO_DATE('1992-1-11', 'yyyy-mm-dd'), 'RB', 'Spain', 'Real Madrid C.F.', 0,0,0,0,37);
------Hraci z Portugalska------
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,1, 'R. Patricio', TO_DATE('1988-2-15', 'yyyy-mm-dd'), 'GK', 'Portugal', 'Wolverhampton Wanderers F.C.',0,0,0,0,16);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,5, 'R. Guerreio', TO_DATE('1993-12-22', 'yyyy-mm-dd'), 'LB', 'Portugal', 'Borussia Dortmund',0,1,1,0,40);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,6, 'J. Fonte', TO_DATE('1983-12-22', 'yyyy-mm-dd'), 'CB', 'Portugal', 'Lille OSC',0,0,0,0,36);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,3, 'Pepe', TO_DATE('1983-2-26', 'yyyy-mm-dd'), 'CB', 'Portugal', 'FC Porto',1,0,0,0,36);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,21, 'Cedric', TO_DATE('1991-8-31', 'yyyy-mm-dd'),'RB', 'Portugal', 'Inter Milan',0,0,1,0,30);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,16, 'B. Fermandes', TO_DATE('1994-9-8', 'yyyy-mm-dd'), 'LM', 'Portugal', 'Sporting CP',0,0,1,0,11);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,14, 'W. Carvalho', TO_DATE('1992-4-7', 'yyyy-mm-dd'), 'CM', 'Portugal', 'Real Betis',0,0,0,0,41);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,8, 'J. Moutinho', TO_DATE('1986-9-8', 'yyyy-mm-dd') , 'CM', 'Portugal', 'Wolverhampton Wanderers F.C.',0,1,0,0,21);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,11, 'B. Silva', TO_DATE('1995-11-6', 'yyyy-mm-dd'), 'RB', 'Portugal', 'Manchester City F.C',0,0,0,0,29);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,7, 'C. Ronaldo', TO_DATE('1985-2-5', 'yyyy-mm-dd'), 'LF', 'Portugal', 'Juventus F.C.',4,0,2,0,35);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,17, 'G. Guedes', TO_DATE('1996-11-29', 'yyyy-mm-dd'), 'RF', 'Portugal', 'Valencia CF',0,1,0,0,27);
INSERT INTO Hrac VALUES (id_hraca_sequence.nextval,20, 'R. Quaresma', TO_DATE('1983-9-26', 'yyyy-mm-dd'), 'RW', 'Portugal', 'Besiktas J.K.',0,1,0,0,27);

------Rozhodcovia------
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Rocchi Gianluca', 'Hlavny rozhodca');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Di Liberatore Elenito', 'Asistent rozhodcu');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Tonolini Mauro', 'Asistent rozhodcu');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Sato Ryuji', 'Stvrty rozhodca');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Irrati Massimiliano', 'Hlavny video rozhodca');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Astroza Carlos', 'Asistent video rozhodcu');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Valeri Paolo', 'Asistent video rozhodcu');
INSERT INTO Rozhodca VALUES (id_rozhodca_sequence.nextval,'Orsato Daniele', 'Asistent video rozhodcu');

------Zapasy------
INSERT INTO Zapas VALUES (id_zapasu_sequence.nextval,'Fisht Olympic Stadium','Sochi', to_date('2018-6-15 21:00:00', 'yyyy-mm-dd HH24:MI:SS'),'3:3','Portugal-Spain',9,6,4,22,
                          '24°C/Prevazne_zamracene/vietor:10km/h','Portugal','Spain',1,2,3,4);
INSERT INTO Zapas VALUES (id_zapasu_sequence.nextval,'Fisht Olympic Stadium','Sochi', to_date('2018-6-22 17:00:00', 'yyyy-mm-dd HH24:MI:SS'),'2:0','Spain-Portugal',6,2,5,28,
                          '20°C/Slnenco/vietor:8km/h','Spain','Portugal',1,2,4,5);

------Udalosti v prvom zapase------
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'gol','C. Ronaldo','35:22',NULL,1);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'asistencia','G. Guedes','35:22',NULL,1);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'faul','B. Fermandes','79:46','zlta karta',1);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'asistencia','C. Ronaldo','44:22',NULL,1);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'asistencia','R. Guerreio','44:22',NULL,1);
------Udalosti v druhom zapase
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'gol','Nacho','88:32',NULL,2);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'asistencia','C. Ronaldo','35:22',NULL,2);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'faul','G. Pique','30:52','zlta karta',2);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'faul','G. Pique','35:22',NULL,2);
INSERT INTO Udalost VALUES (id_udalosti_sequence.nextval,'gol','D. Costa','67:21',NULL,2);

------Striedanie v prvom zapase------
INSERT INTO Striedanie VALUES (id_striedania_sequence.nextval,'78:45',12,5,1);
------Striedanie v druhom zapase------
INSERT INTO Striedanie VALUES (id_striedania_sequence.nextval,'53:29',24,23,2);


INSERT INTO Hrac_vykonal_Udalost VALUES (22,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (23,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (18,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (22,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (14,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (5,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (22,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (4,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (8,id_udalosti_hraca_sequence.nextval);
INSERT INTO Hrac_vykonal_Udalost VALUES (10,id_udalosti_hraca_sequence.nextval);

INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,1);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,1);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,1);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,1);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,1);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,2);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,2);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,2);
INSERT INTO Udalost_v_Zapase VALUES (id_udalosti_zapas_sequence.nextval,2);


-- Pocet strelenych golov v zapase => 2 TABLE
SELECT Hrac.meno_hraca, Hrac.pocet_strelenych_golov, Zapas.id_zapasu
FROM Hrac, Zapas
where Hrac.pocet_strelenych_golov > 0
ORDER BY pocet_strelenych_golov DESC;

-- V ktorych zapasoch rozhodoval Rocchi Gianluca => 2 TABLE
SELECT Zapas.id_zapasu, Rozhodca.meno_rozhodcu, Rozhodca.typ_rozhodcu
FROM Rozhodca, Zapas
WHERE meno_rozhodcu = 'Rocchi Gianluca';

-- ake udalosti sa udiali v prvom zapase a kto v nich hral rolu => 3 TABLE
SELECT Udalost.typ, Udalost.presny_cas_udalosti, Hrac.meno_hraca, Zapas.id_zapasu
FROM Zapas, Hrac, Udalost
where Hrac.meno_hraca = akter AND Zapas.id_zapasu = 1;

-- hrac s najvacsim poctom strelenych golov => GROUP BY
SELECT Hrac.meno_hraca, MAX(Hrac.pocet_strelenych_golov) AS Maximalny_pocet_golov
FROM Hrac
WHERE hrac.pocet_strelenych_golov > 0
GROUP BY Hrac.meno_hraca
ORDER BY MAX(hrac.pocet_strelenych_golov)DESC
FETCH FIRST 1 ROWS ONLY;

-- priemer nabehanych kilometrov za team => GROUP BY
SELECT Hrac.narodnost_hraca, AVG(Hrac.nabehane_kilomentre)
FROM Hrac
WHERE Hrac.narodnost_hraca = 'Portugal' OR Hrac.narodnost_hraca = 'Spain'
GROUP BY Hrac.narodnost_hraca
ORDER BY AVG(Hrac.nabehane_kilomentre) DESC;

-- Vypis vsetkych hracov s Portugalskou narodnostou => IN clausule
SELECT Hrac.meno_hraca
FROM Hrac
WHERE Hrac.narodnost_hraca IN (
  SELECT Hrac.narodnost_hraca
  FROM Hrac
  WHERE Hrac.narodnost_hraca = 'Portugal');

-- Vyber teamu v ktorom sa nachaza hrac s cislom 22
SELECT Hrac.narodnost_hraca AS TEAM, Hrac.meno_hraca
FROM Hrac
WHERE EXISTS(
  SELECT Team.narodnost_teamu
  FROM Team
  WHERE Hrac.cislo_dresu = 22
  AND Hrac.narodnost_hraca = Team.narodnost_teamu);


-- EXPLAIN PLAN s indexom a bez indexu

EXPLAIN PLAN FOR
    SELECT Hrac.meno_hraca, COUNT(*) AS Pocet_faulov
    FROM Udalost, Hrac
    WHERE Hrac.meno_hraca = Udalost.akter AND Udalost.typ = 'faul' AND (udalost.id_zapasu = 1 OR Udalost.id_zapasu = 2)
    GROUP BY Hrac.meno_hraca;
SELECT PLAN_TABLE_OUTPUT FROM TABLE(DBMS_XPLAN.DISPLAY());

CREATE INDEX foul_index ON Udalost (typ, id_zapasu, akter);  
EXPLAIN PLAN FOR
    SELECT Hrac.meno_hraca, COUNT(*) AS Pocet_faulov
    FROM Udalost, Hrac
    WHERE Hrac.meno_hraca = Udalost.akter AND Udalost.typ = 'faul' AND (udalost.id_zapasu = 1 OR Udalost.id_zapasu = 2)
    GROUP BY Hrac.meno_hraca;
SELECT PLAN_TABLE_OUTPUT FROM TABLE(DBMS_XPLAN.DISPLAY());

DROP INDEX foul_index;

-- Procedúra vypíše informacie o hracovi
CREATE OR REPLACE PROCEDURE hrac_statistiky(meno_hraca IN VARCHAR2)
IS
  CURSOR hrac_info IS SELECT * FROM Hrac;
  tmp hrac_info%ROWTYPE;
  stats_goal NUMBER;
  stats_km NUMBER;
  stats_klub VARCHAR2(30);
  stats_pozicia VARCHAR2(3);
  stats_dres NUMBER;
  stats_meno VARCHAR2(10);
  row_count NUMBER;
  exept EXCEPTION;
BEGIN
  SELECT COUNT(*) INTO row_count FROM Hrac WHERE id_hraca > 0;
	IF (row_count = 0)
	THEN
		RAISE exept;
	END IF;
	stats_goal := 0;
	stats_km := 0;
	stats_dres := 0;
  stats_klub := 0;
  stats_pozicia := 0;
  stats_meno := 0;
  OPEN hrac_info;
  LOOP
    FETCH hrac_info into tmp;
    exit when hrac_info%NOTFOUND;

    IF (tmp.meno_hraca = meno_hraca) THEN
      stats_goal := tmp.pocet_strelenych_golov;
      stats_km := tmp.nabehane_kilomentre;
      stats_dres := tmp.cislo_dresu;
      stats_klub := tmp.domovsky_klub;
      stats_pozicia := tmp.pozicia;
      stats_meno := tmp.meno_hraca;
      dbms_output.put_line('Meno hraca: '|| stats_meno);
      dbms_output.put_line('Pocet strelenych golov: '|| stats_goal);
      dbms_output.put_line('Nabehane kilometre: ' || stats_km || 'km');
      dbms_output.put_line('Pozicia hraca: ' || stats_pozicia);
      dbms_output.put_line('Cislo dresu: '|| stats_dres);
      dbms_output.put_line('Domovsky klub: ' || stats_klub);
  END IF;
  END LOOP;
  CLOSE hrac_info;
END;
/

-- Procedúra vypíše priemerný vek v tíme
CREATE OR REPLACE PROCEDURE priemerny_vek(narodnost_hraca VARCHAR2)
IS
  CURSOR hrac IS SELECT * FROM Hrac;
  tmp hrac%ROWTYPE;
  vek NUMBER;
  sum_vek NUMBER;
  avg_vek FLOAT(10);
  stats_meno VARCHAR2(20);
  counter NUMBER;
  row_count NUMBER;
  exept EXCEPTION;
BEGIN
  SELECT COUNT(*) INTO row_count FROM Hrac WHERE id_hraca > 0;
	IF (row_count = 0)
	THEN
		RAISE exept;
	END IF;

	vek := 0;
	stats_meno := 0;
	sum_vek := 0;
	counter := 0;
	avg_vek := 0;
  OPEN hrac;
	LOOP
	  FETCH hrac into tmp;
	  EXIT WHEN hrac%NOTFOUND;

    IF(tmp.narodnost_hraca = narodnost_hraca) THEN
    vek := (trunc(months_between(sysdate,tmp.datum_narodenia)/12));
    sum_vek := sum_vek + vek;
    counter := counter + 1;
    END IF;
  END LOOP;
  avg_vek := sum_vek / counter;
  dbms_output.put_line('priemerny vek: ' || avg_vek || ' rokov');
  CLOSE hrac;
END;
/

-- Volanie procedúr
EXECUTE hrac_statistiky('C. Ronaldo');
EXECUTE priemerny_vek('Spain');

-- Nastavenie práv pre tabulky
GRANT ALL ON Hrac                    TO xweisd00;
GRANT ALL ON Team                    TO xweisd00;
GRANT ALL ON Zapas                   TO xweisd00;
GRANT ALL ON Rozhodca                TO xweisd00;
GRANT ALL ON Striedanie              TO xweisd00;
GRANT ALL ON Udalost                 TO xweisd00;
GRANT ALL ON Hrac_vykonal_udalost    TO xweisd00;
GRANT ALL ON Udalost_v_Zapase        TO xweisd00;

-- Nastavenie práv pre procedury
GRANT EXECUTE ON hrac_statistiky     TO xweisd00;
GRANT EXECUTE ON priemerny_vek       TO xweisd00;

-- Materializovany pohlad a práca s tabulkou
DROP MATERIALIZED VIEW Team_v_Zapase;

CREATE MATERIALIZED VIEW LOG ON Team WITH PRIMARY KEY, ROWID;
CREATE MATERIALIZED VIEW LOG ON Zapas WITH PRIMARY KEY, ROWID;

CREATE MATERIALIZED VIEW Team_v_Zapase
NOLOGGING
CACHE
BUILD IMMEDIATE
REFRESH FAST ON COMMIT
ENABLE QUERY REWRITE
AS
SELECT Team.rowid AS Team_rid, Zapas.rowid AS Zapas_rid,
Team.narodnost_teamu, Zapas.stadion, Zapas.datum_cas
FROM Team JOIN Zapas ON Team.narodnost_teamu = Zapas.zapas_team_domaci;

GRANT ALL ON Team_v_Zapase TO xweisd00;

SELECT narodnost_teamu, stadion, datum_cas
FROM Team_v_Zapase;

INSERT INTO Team VALUES ('Slovakia',433,'Richard Borbely','Daniel Weis, Alex Sporni, 
Adam Abraham, Tomas Zigo','Igor Mjasojedov','B',7,'W1-D2-L0',34);
COMMIT;

DELETE FROM Team WHERE narodnost_teamu = 'Slovakia';

SELECT narodnost_teamu, stadion, datum_cas
FROM Team_v_Zapase;
COMMIT;

SELECT narodnost_teamu, stadion, datum_cas
FROM Team_v_Zapase;