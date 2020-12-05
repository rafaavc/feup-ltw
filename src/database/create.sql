PRAGMA foreign_keys = ON;

CREATE TABLE User (
    id INTEGER PRIMARY KEY, -- Used for photos on usersProfilePictures folder
    name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    birthdate DATE NOT NULL,
    mail TEXT NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE List (
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    public BOOLEAN NOT NULL,
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE
);

CREATE TABLE Pet (
    id INTEGER PRIMARY KEY, -- Used for profilePhoto on petProfilePictures folder
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE, -- Listed for adoption
    name TEXT,   -- may not have a name
    birthdate DATE NOT NULL,
    specie INTEGER REFERENCES PetRace ON DELETE SET NULL, -- it has either PetRace or a PetSpecie, because the race is associated with the specie. The PetSpecie is only for pets without race
    race INTEGER REFERENCES PetRace ON DELETE SET NULL,
    size INTEGER NOT NULL REFERENCES PetSize ON DELETE SET NULL,
    color INTEGER NOT NULL REFERENCES PetColor ON DELETE SET NULL,
    location TEXT NOT NULL,
    profilePhoto INTEGER REFERENCES PetPhoto ON DELETE SET NULL
);

CREATE TABLE PetColor (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL
);
CREATE TABLE PetSize (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL
);
CREATE TABLE PetSpecie (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL
);
CREATE TABLE PetRace (
    id INTEGER PRIMARY KEY,
    specieId INTEGER NOT NULL REFERENCES PetSpecie ON DELETE CASCADE,
    name TEXT NOT NULL
);

CREATE TABLE PetPhoto (
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    photoId INTEGER PRIMARY KEY
);

CREATE TABLE ListPet (
    listId INTEGER NOT NULL REFERENCES List ON DELETE CASCADE,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    PRIMARY KEY(listId, petId)
);

CREATE TABLE ProposedToAdopt (
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE
);

CREATE TABLE Adopted (
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE UNIQUE -- pet can only be adopted by one person
);

CREATE TABLE Post (
    id INTEGER PRIMARY KEY,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE,
    question TEXT NOT NULL,
    description TEXT NOT NULL,
    postDate DATE NOT NULL,
    answerToPostID INTEGER REFERENCES Post ON DELETE SET NULL -- If answerToPostID is NULL, the post is not an answer. It it is != NULL, the post is an answer to the referenced post, and should be represented accordingly.
);


INSERT INTO PetSpecie(name) VALUES("Dog");
INSERT INTO PetSpecie(name) VALUES("Cat");
INSERT INTO PetSpecie(name) VALUES("Turtle");


INSERT INTO PetRace(specieId, name) VALUES(1, "Rottweiler");
INSERT INTO PetRace(specieId, name) VALUES(1, "Husky");
INSERT INTO PetRace(specieId, name) VALUES(1, "Golden Retriever");
INSERT INTO PetRace(specieId, name) VALUES(2, "Sphynx");

INSERT INTO PetSize(name) VALUES("Small");
INSERT INTO PetSize(name) VALUES("Medium");
INSERT INTO PetSize(name) VALUES("Large");

INSERT INTO PetColor(name) VALUES("White");
INSERT INTO PetColor(name) VALUES("Brown");
INSERT INTO PetColor(name) VALUES("Black");
INSERT INTO PetColor(name) VALUES("Black and White");
INSERT INTO PetColor(name) VALUES("Black and Brown");
INSERT INTO PetColor(name) VALUES("Brown and White");
INSERT INTO PetColor(name) VALUES("Gold");

INSERT INTO User(name, username, password, birthdate, mail) VALUES("John Lewis", "johnalewis", "supersecure", DATE("1998-08-10"), "johnalewis@placeholder.com");
INSERT INTO User(name, username, password, birthdate, mail, description) VALUES("Rafael Cristino", "rafaavc", "mypassword", DATE("2000-08-28"), "rafaavc@mail.com",
    "A humble man who is 'carregating' its LTW group project.");
INSERT INTO User(name, username, password, birthdate, mail, description) VALUES("Xavier Pisco", "xamas", "safest", DATE("2000-10-29"), "xamas@mail.com",
    "A bad group teammate who just wanna be 'carregated'.");
INSERT INTO User(name, username, password, birthdate, mail, description) VALUES("João Diogo Romão", "TsarkFC", "marktsubasa", DATE("2000-06-22"), "tsarkfc@mail.com",
    "This one doesn't even know there is a project");

INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location) VALUES(1, "Boby", DATE("2019-01-20"), NULL, 1, 2, 3, "Amsterdam");
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location) VALUES(1, "Snoop", DATE("2013-06-13"), NULL, 3, 3, 7, "Amsterdam");
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location) VALUES(3, "Malaquias", DATE("2020-08-25"), NULL, 2, 1, 4, "Moita do Boi");
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location) VALUES(2, "Garfield", DATE("2017-12-14"), 2, NULL, 2, 7, "Cucujães");
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location) VALUES(4, "Bicho", DATE("2000-1-30"), NULL, 4, 2, 4, "Repeses");

INSERT INTO PetPhoto(petId, photoId) VALUES(1, 1);
UPDATE Pet SET profilePhoto = 1 WHERE id = 1;


