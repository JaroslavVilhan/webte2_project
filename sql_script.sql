create database webteUloha1;
create database webteUloha2;
use webteUloha1;

CREATE TABLE Predmet (
    PredmetId int NOT NULL AUTO_INCREMENT,
    Nazov varchar(255) NOT NULL,
    Rok varchar(255) NOT NULL,
    PRIMARY KEY (PredmetId)
);