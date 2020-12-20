PRAGMA foreign_keys = ON;

CREATE TABLE User (
    id INTEGER PRIMARY KEY, -- Used for photos on usersProfilePictures folder
    name TEXT NOT NULL CHECK (length(name) >= 1 AND length(name) <= 40),
    username TEXT NOT NULL UNIQUE CHECK (length(username) >= 5 AND length(username) <= 15),
    password TEXT NOT NULL,
    birthdate DATE NOT NULL,
    mail TEXT NOT NULL UNIQUE CHECK (mail LIKE '%@%'),
    description TEXT CHECK (length(description) <= 300)
);

CREATE TABLE List (
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL CHECK (length(title) >= 1 AND length(title) <= 20),
    description TEXT NOT NULL,
    public BOOLEAN NOT NULL,
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE
);

CREATE TABLE Pet (
    id INTEGER PRIMARY KEY, -- Used for profilePhoto on pet_profile_pictures folder
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE, -- Listed for adoption
    name TEXT CHECK (length(name) <= 20),   -- may not have a name
    birthdate DATE NOT NULL,
    specie INTEGER REFERENCES PetSpecie ON DELETE SET NULL, -- it has either PetRace or a PetSpecie, because the race is associated with the specie. The PetSpecie is only for pets without race
    race INTEGER REFERENCES PetRace ON DELETE SET NULL,
    size INTEGER NOT NULL REFERENCES PetSize ON DELETE SET NULL,
    color INTEGER NOT NULL REFERENCES PetColor ON DELETE SET NULL,
    location TEXT NOT NULL CHECK (length(location) >= 5 AND length(location) <= 20),
    description TEXT NOT NULL CHECK (length(description) >= 20 AND length(description) <= 300),
    datePosted DATETIME NOT NULL,
    archived BOOLEAN NOT NULL
);

CREATE TABLE PetColor (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL CHECK(length(name) <= 20 AND length(name >= 1))
);
CREATE TABLE PetSize (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL CHECK(length(name) <= 20 AND length(name >= 1))
);
CREATE TABLE PetSpecie (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL CHECK(length(name) <= 20 AND length(name >= 1))
);
CREATE TABLE PetRace (
    id INTEGER PRIMARY KEY,
    specieId INTEGER NOT NULL REFERENCES PetSpecie ON DELETE CASCADE,
    name TEXT NOT NULL CHECK(length(name) <= 20 AND length(name >= 1))
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
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    PRIMARY KEY(userId, petId)
);

CREATE TABLE RejectedProposal (
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
    description TEXT NOT NULL CHECK(length(description) >= 1),
    postDate TEXT NOT NULL,
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

INSERT INTO User(name, username, password, birthdate, mail) VALUES("John Lewis", "johnalewis", "$2y$10$u2wqpAM5ntWNjnEdZReqEOT7Xqu.1VjLYyAFQNwV3INcGoWDe0EWa", DATE("1998-08-10"), "johnalewis@placeholder.com");
INSERT INTO User(name, username, password, birthdate, mail, description) VALUES("Rafael Cristino", "rafaavc", "$2y$10$iOGAgqhjw0YK2O/xsr0KpOR1YloVXontN7AkdC8S6spFmof.x2yZq", DATE("2000-08-28"), "rafaavc@mail.com",
    "A humble man who is 'carregating' its LTW group project.");  -- PW: mypassword
INSERT INTO User(name, username, password, birthdate, mail, description) VALUES("Xavier Pisco", "xamas", "$2y$10$dZtzuf9IWnDFzYUL34P8oe5hCAeAFogH1pd3Et8D2smhUJG4VZMLO", DATE("2000-10-29"), "xamas@mail.com",
    "A bad group teammate who just wanna be 'carregated'.");  -- PW: safest
INSERT INTO User(name, username, password, birthdate, mail, description) VALUES("João Diogo Romão", "TsarkFC", "$2y$10$jIDBFmD0.YdhEmabyHLS..VBlCSpdEm/VX8qibLw/riql44HAoTTe", DATE("2000-06-22"), "tsarkfc@mail.com",
    "I didn't even know there was a project");  -- PW: marktsubasa

INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted, archived) VALUES(1, "Boby", DATE("2019-01-20"), NULL, 1, 2, 3, "Amsterdam",
    "A good boy, well trained and really chill with kids.", DATETIME("2019-08-10"), 0);
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted, archived) VALUES(1, "Snoop", DATE("2013-06-13"), NULL, 3, 3, 7, "Amsterdam",
    "This is a calm dog that just wanna play. The older she gets the less she runs, but her spirit never gets old.", DATETIME("2019-09-10"), 0);
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted, archived) VALUES(3, "Husky", DATE("2020-08-25"), NULL, 2, 1, 4, "Moita do Boi",
    "A pure breed husky, really young so he is has a lot of energy!", DATETIME("2019-07-10"), 0);
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted, archived) VALUES(2, "Garfield", DATE("2017-12-14"), 2, NULL, 2, 7, "Cucujães",
    "A cat that just looks like garfield, especially since he got a bit chubbier!", DATETIME("2019-08-09"), 0);
INSERT INTO Pet(userId, name, birthdate, specie, race, size, color, location, description, datePosted, archived) VALUES(4, "Bicho", DATE("2000-01-30"), NULL, 4, 2, 4, "Repeses",
    "This cat is getting old so it doesn't have a lot of energy but still is a good companion to people who spend most their time at home.", DATETIME("2018-04-11"), 0);

INSERT INTO PetPhoto(petId, photoId) VALUES(1, 1);
INSERT INTO PetPhoto(petId, photoId) VALUES(2, 2);
INSERT INTO PetPhoto(petId, photoId) VALUES(3, 3);
INSERT INTO PetPhoto(petId, photoId) VALUES(4, 4);
INSERT INTO PetPhoto(petId, photoId) VALUES(5, 5);
INSERT INTO PetPhoto(petId, photoId) VALUES(1, 6);
INSERT INTO PetPhoto(petId, photoId) VALUES(1, 7);
INSERT INTO PetPhoto(petId, photoId) VALUES(2, 8);
INSERT INTO PetPhoto(petId, photoId) VALUES(5, 9);

INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES (1, 3,
    "Is there any way to get this dog to Portugal? Any pet transporter that works in both countries?", "2020-12-05  13:05:54", NULL);
INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES (1, 1,
    "Yes, that's not a proble, there is a pet transportation company that works here. It's called CCPS", "2020-12-06 10:18:31", 1);
INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES (3, 4,
    "Where is 'Moita do Boi'?", "2020-11-25 20:15:45", NULL);
INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES (3, 2,
    "Is it male or female?", "2020-12-01 16:06:41", NULL);
INSERT INTO Post(petId, userId, description, postDate, answerToPostID) VALUES (3, 3,
    "It's a male.", "2020-12-01 22:10:06", 4);

INSERT INTO ProposedToAdopt(userId, petId) VALUES(2, 3);
INSERT INTO ProposedToAdopt(userId, petId) VALUES(4, 3);

INSERT INTO Adopted(userId, petId) VALUES(3, 1);
INSERT INTO Adopted(userId, petId) VALUES(4, 4);

INSERT INTO List(title, description, public, userId) VALUES ("Favorites", "Does it need description?", 1, 1);
INSERT INTO List(title, description, public, userId) VALUES ("Dogs", "My favourite dogs", 0, 1);
INSERT INTO List(title, description, public, userId) VALUES ("Empty", "Just an empty list...", 1, 1);

INSERT INTO ListPet(listId, petId) VALUES (1, 1);
INSERT INTO ListPet(listId, petId) VALUES (1, 2);
INSERT INTO ListPet(listId, petId) VALUES (1, 3);
INSERT INTO ListPet(listId, petId) VALUES (1, 4);
INSERT INTO ListPet(listId, petId) VALUES (2, 1);
INSERT INTO ListPet(listId, petId) VALUES (2, 3);
