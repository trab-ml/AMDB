PRAGMA foreign_key = on;

CREATE TABLE users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    pseudo TEXT,
    email TEXT,
    password TEXT
);

CREATE TABLE films (
    film_id INTEGER PRIMARY KEY AUTOINCREMENT,
    titre TEXT,
    description TEXT,
    langue TEXT,
    date_tournage TEXT
);

CREATE TABLE rechercher (
    user_id INTEGER NOT NULL,
    film_id INTEGER NOT NULL,
    from_d TEXT,
    to_d TEXT, 
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (film_id) REFERENCES films(film_id)
);

CREATE TABLE noter (
    user_id INTEGER NOT NULL,
    film_id INTEGER NOT NULL,
    note_globale TEXT, 
    date_note TEXT, 
    title TEXT, 
    cover_img TEXT, 
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (film_id) REFERENCES films(film_id)
);
CREATE TABLE filtrer (
    id INTEGER,
    genre TEXT 
);