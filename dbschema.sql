CREATE TABLE Mistnosti
(
    zkratka_mistnosti VARCHAR(5) PRIMARY KEY NOT NULL,
    popis VARCHAR(30),
    kapacita SMALLINT
);
CREATE TABLE Pedagogove
(
    kod_pedagoga VARCHAR(10) PRIMARY KEY NOT NULL,
    jmeno VARCHAR(30),
    prijmeni VARCHAR(30),
    tituly_pred_jmenem VARCHAR(20),
    tituly_za_jmenem VARCHAR(30),
    heslo VARCHAR(20)
);
CREATE TABLE Pedagogove_predmety
(
    kod_pedagoga VARCHAR(10) NOT NULL,
    zkratka_predmetu VARCHAR(5) NOT NULL,
    PRIMARY KEY (kod_pedagoga, zkratka_predmetu)
);
CREATE TABLE Predmety
(
    zkratka_predmetu VARCHAR(5) PRIMARY KEY NOT NULL,
    nazev VARCHAR(50),
    pocet_kreditu SMALLINT,
    pocet_hodin_prednasek SMALLINT,
    pocet_hodin_cviceni SMALLINT,
    ukonceni CHAR(1),
    anotace LONGTEXT
);
CREATE TABLE Studenti
(
    kod_studenta VARCHAR(10) PRIMARY KEY NOT NULL,
    jmeno VARCHAR(30),
    prijmeni VARCHAR(30),
    heslo VARCHAR(20)
);
CREATE TABLE Studenti_predmety
(
    zkratka_predmetu VARCHAR(5) NOT NULL,
    kod_studenta VARCHAR(10) NOT NULL,
    PRIMARY KEY (zkratka_predmetu, kod_studenta)
);
CREATE TABLE Vypsane_terminy
(
    id_terminu INT PRIMARY KEY NOT NULL,
    datum_cas DATE,
    max_pocet_prihlasenych SMALLINT,
    poznamka VARCHAR(200),
    kod_pedagoga VARCHAR(10),
    zkratka_predmetu VARCHAR(5),
    zkratka_mistnosti VARCHAR(5)
);
CREATE TABLE Vysledky
(
    id_vysledku INT PRIMARY KEY NOT NULL,
    popis VARCHAR(20)
);
CREATE TABLE Zapsane_terminy
(
    id_terminu INT NOT NULL,
    kod_studenta VARCHAR(10) NOT NULL,
    id_vysledku INT,
    PRIMARY KEY (id_terminu, kod_studenta)
);
ALTER TABLE Pedagogove_predmety ADD FOREIGN KEY (kod_pedagoga) REFERENCES Pedagogove (kod_pedagoga);
ALTER TABLE Pedagogove_predmety ADD FOREIGN KEY (zkratka_predmetu) REFERENCES Predmety (zkratka_predmetu);
CREATE INDEX fk_Pedagogove_predmety_Predmety1_idx ON Pedagogove_predmety (zkratka_predmetu);
ALTER TABLE Studenti_predmety ADD FOREIGN KEY (zkratka_predmetu) REFERENCES Predmety (zkratka_predmetu);
ALTER TABLE Studenti_predmety ADD FOREIGN KEY (kod_studenta) REFERENCES Studenti (kod_studenta);
CREATE INDEX Studenti1_idx ON Studenti_predmety (kod_studenta);
ALTER TABLE Vypsane_terminy ADD FOREIGN KEY (zkratka_mistnosti) REFERENCES Mistnosti (zkratka_mistnosti);
ALTER TABLE Vypsane_terminy ADD FOREIGN KEY (zkratka_predmetu) REFERENCES Predmety (zkratka_predmetu);
ALTER TABLE Vypsane_terminy ADD FOREIGN KEY (kod_pedagoga) REFERENCES Pedagogove (kod_pedagoga);
CREATE INDEX fk_Vypsane_terminy_Pedagogove1_idx ON Vypsane_terminy (kod_pedagoga);
CREATE INDEX Mistnosti1_idx ON Vypsane_terminy (zkratka_mistnosti);
CREATE INDEX Predmety1_idx ON Vypsane_terminy (zkratka_predmetu);
ALTER TABLE Zapsane_terminy ADD FOREIGN KEY (kod_studenta) REFERENCES Studenti (kod_studenta);
ALTER TABLE Zapsane_terminy ADD FOREIGN KEY (id_terminu) REFERENCES Vypsane_terminy (id_terminu);
ALTER TABLE Zapsane_terminy ADD FOREIGN KEY (id_vysledku) REFERENCES Vysledky (id_vysledku);
CREATE INDEX Studenti2_idx ON Zapsane_terminy (kod_studenta);
CREATE INDEX Vysledky1_idx ON Zapsane_terminy (id_vysledku);
