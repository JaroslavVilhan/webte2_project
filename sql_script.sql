DROP DATABASE IF EXISTS webteUloha1;
DROP DATABASE IF EXISTS webteUloha2;
DROP DATABASE IF EXISTS webteUloha3;

create database webteUloha1;
create database webteUloha2;
create database webteUloha3;

ALTER DATABASE webteUloha1 CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER DATABASE webteUloha2 CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER DATABASE webteUloha3 CHARACTER SET utf8 COLLATE utf8_general_ci;

use webteUloha1;

CREATE TABLE Predmet (
    PredmetId int NOT NULL AUTO_INCREMENT,
    Nazov varchar(255) NOT NULL,
    Rok varchar(255) NOT NULL,
    PRIMARY KEY (PredmetId)
);

use webteUloha2;

CREATE TABLE Predmet (
    PredmetId int NOT NULL AUTO_INCREMENT,
    Nazov varchar(255) NOT NULL,
    Rok varchar(255) NOT NULL,
    PRIMARY KEY (PredmetId)
);

use webteUloha3;

CREATE TABLE Historia (
    Datum datetime,
    Meno varchar(255) NOT NULL,
    Predmet varchar(255) NOT NULL,
    SablonaID int
);

CREATE TABLE Templates (
    id   INT           AUTO_INCREMENT PRIMARY KEY,
    mime VARCHAR (255) NOT NULL,
    data TEXT         NOT NULL
);

INSERT INTO `Templates` (`mime`, `data`) VALUES ('text/html', '<p>Dobrý deň,</p>\n<br>\n<p>na predmete Webové technológie 2 budete mať k dispozícii vlastný virtuálny linux server, ktorý budete používať počas semestra, a na ktorom budete vypracovávať zadania. Prihlasovacie údaje k Vašemu serveru su uvedené nižšie.</p>\n<br>\n<p>ip adresa: {{verejnaIP}}</p>\n<p>prihlasovacie meno: {{login}}</p>\n<p>heslo: {{heslo}}</p>\n<br>\n<p>Vaše web stránky budú dostupné na: http:// {{verejnaIP}}:{{http}}</p>\n<br>\n<p>S pozdravom,</p>\n<br>\n<p>{{sender}}</p>');

INSERT INTO `Templates` (`mime`, `data`) VALUES ('text/html', '<p>Dobrý deň,</p>\n<br>\n<p>Vaše prihlasovacie údaje sú nasledovné:</p>\n<br><br>\n<p>prihlasovacie meno: {{login}}</p>\n<p>heslo: {{heslo}}</p>\n<br>\n<p>S pozdravom,</p>\n<p>{{sender}}</p>');