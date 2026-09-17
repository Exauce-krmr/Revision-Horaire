CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    annee_scolaire VARCHAR(9) NOT NULL
);

CREATE TABLE cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL,
    nom VARCHAR(120) NOT NULL
);

CREATE TABLE crenaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classe_id INT NOT NULL,
    cours_id INT NOT NULL,
    jour ENUM('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'),
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    salle VARCHAR(20) NOT NULL,

    CONSTRAINT fk_crenaux_classe
     FOREIGN KEY (classe_id) REFERENCES classes(id)
         ON DELETE RESTRICT
         ON UPDATE CASCADE,

    CONSTRAINT fk_crenaux_cours
     FOREIGN KEY (cours_id) REFERENCES cours(id)
         ON DELETE RESTRICT
         ON UPDATE CASCADE
);

INSERT INTO classes (nom, annee_scolaire) VALUES
    ('I.DA-P3A', '2026-2027'),
    ('I.DA-P1A', '2026-2027');

INSERT INTO cours (code, nom) VALUES
    ('AWEB3', 'Atelier Web 3e année S1'),
    ('BDD1', 'Base de données 1');

INSERT INTO crenaux (classe_id, cours_id, jour, heure_debut, heure_fin, salle) VALUES
    (1, 1, 'jeudi', '08:05', '11:40', 'R104'),
    (2, 2, 'lundi', '13:00', '16:00', 'R201');