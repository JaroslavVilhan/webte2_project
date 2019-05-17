DROP DATABASE IF EXISTS webteUloha1;
DROP DATABASE IF EXISTS webteUloha2;

create database webteUloha1;
create database webteUloha2;

ALTER DATABASE webteUloha1 CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER DATABASE webteUloha2 CHARACTER SET utf8 COLLATE utf8_general_ci;

use webteUloha1;

CREATE TABLE Predmet (
    PredmetId int NOT NULL AUTO_INCREMENT,
    Nazov varchar(255) NOT NULL,
    Rok varchar(255) NOT NULL,
    PRIMARY KEY (PredmetId)
);