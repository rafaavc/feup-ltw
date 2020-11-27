CREATE TABLE User (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    birthdate DATE NOT NULL,
    mail TEXT NOT NULL UNIQUE,
    profilePicture TEXT NOT NULL UNIQUE
);

CREATE TABLE List (
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    public BOOLEAN NOT NULL,
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE
);

CREATE TABLE Pet (
    id INTEGER PRIMARY KEY,
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE, -- Listed for adoption
    name TEXT,   -- may not have a name
    age TEXT NOT NULL,
    species TEXT NOT NULL,
    size TEXT NOT NULL,
    color TEXT NOT NULL,
    location TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE PetPhoto (
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    id INTEGER PRIMARY KEY
);

CREATE TABLE ListPet (
    listId INTEGER NOT NULL REFERENCES List ON DELETE CASCADE,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    PRIMARY KEY(listId, petId)
);

CREATE TABLE ProposedToAdopt (
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE,
    accepted BOOLEAN
);

CREATE TABLE Adopted (
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE,
    petId INTEGER NOT NULL REFERENCES Pet ON DELETE CASCADE UNIQUE -- pet can only be adopted by one person
);

CREATE TABLE Post (
    id INTEGER PRIMARY KEY,
    userId INTEGER NOT NULL REFERENCES User ON DELETE CASCADE,
    question TEXT NOT NULL,
    description TEXT NOT NULL,
    answerToPostID INTEGER REFERENCES Post ON DELETE SET NULL -- If answerToPostID is NULL, the post is not an answer. It it is != NULL, the post is an answer to the referenced post, and should be represented accordingly.
);



