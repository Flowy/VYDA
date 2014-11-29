INSERT INTO Pedagogove (kod_pedagoga, jmeno, prijmeni, tituly_pred_jmenem, tituly_za_jmenem, heslo)
  VALUES
    ('pedagog', 'jmeno', 'prijmeni', 'prefix', 'suffix', 'heslo'),
    ('profesor', 'jmeno', 'prijmeni', 'prefix', 'suffix', 'heslo');

INSERT INTO Predmety (zkratka_predmetu, nazev, pocet_kreditu, pocet_hodin_prednasek, pocet_hodin_cviceni, ukonceni, anotace)
  VALUES ('VYDA', 'PHP', 3, 20, 20, 'Z', 'Webove aplikacie v PHP');
INSERT INTO Studenti (kod_studenta, jmeno, prijmeni, heslo)
  VALUES ('student', 'jmeno', 'prijmeni', 'heslo');
INSERT INTO Pedagogove_predmety (kod_pedagoga, zkratka_predmetu)
  VALUES
    ('pedagog', 'VYDA'),
    ('profesor', 'VYDA');
INSERT INTO Studenti_predmety (zkratka_predmetu, kod_studenta)
    VALUES ('VYDA', 'student');
