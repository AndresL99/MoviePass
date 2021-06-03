drop database if exists dbmoviepass;
CREATE DATABASE dbmoviepass;

USE dbmoviepass;

CREATE TABLE genres
(
  id_genre INT,
  genre_name VARCHAR (300),

  CONSTRAINT pk_id_genre PRIMARY KEY (id_genre)
);

CREATE TABLE movies
(
  id_movie INT,
  title VARCHAR(100),
  poster_path VARCHAR(100),
  overview VARCHAR(300),
  release_date DATE,
  original_language VARCHAR(30),
  vote_count INT,
  popularity INT,
  runtime INT,
  vote_average INT,
  is_active BOOLEAN,

  CONSTRAINT pk_id_movie PRIMARY KEY (id_movie)
);

CREATE TABLE movies_x_genres
(
  id_movie_x_genre INT AUTO_INCREMENT,
  id_movie INT,
  id_genre INT,

  CONSTRAINT pk_id_movie_x_genre PRIMARY KEY (id_movie_x_genre),
  CONSTRAINT fk_id_movie_movie_x_genre FOREIGN KEY (id_movie) REFERENCES movies(id_movie),
  CONSTRAINT fk_id_genre_movie_x_genre FOREIGN KEY (id_genre) REFERENCES genres(id_genre)
);


CREATE TABLE cinemas
(
  id_cinema INT AUTO_INCREMENT,
  state BOOLEAN,
  name VARCHAR (50),
  address VARCHAR (50),

  CONSTRAINT pk_id_cinema PRIMARY KEY (id_cinema),
  CONSTRAINT uniq_name UNIQUE (name),
  CONSTRAINT uniq_address UNIQUE (address)
);

  CREATE TABLE movie_theaters
  (
    id_movie_theater INT AUTO_INCREMENT,
    state BOOLEAN,
    name VARCHAR (50),
    current_capacity INT, /*capacidad actual*/
    price INT, /*valor de la entrada*/
    total_capacity INT,

    id_cinema INT,

    CONSTRAINT pk_id_movie_theater PRIMARY KEY (id_movie_theater),
    CONSTRAINT fk_id_cinema FOREIGN KEY (id_cinema) REFERENCES cinemas (id_cinema) ON DELETE CASCADE
);

CREATE TABLE shows
      (
        id_show INT AUTO_INCREMENT,
        state BOOLEAN,
        day DATE,
        hour TIME,

        id_movie INT,
        id_movie_theater INT,

        CONSTRAINT pk_id_show PRIMARY KEY (id_show),
        CONSTRAINT fk_id_movie FOREIGN KEY (id_movie) REFERENCES movies (id_movie) ON DELETE CASCADE,
        CONSTRAINT fk_id_movie_theater FOREIGN KEY (id_movie_theater) REFERENCES movie_theaters (id_movie_theater) ON DELETE CASCADE
);

CREATE TABLE roles
(
  idRol INT AUTO_INCREMENT,
  role varchar (30) not null,
  constraint pk_idRol primary key (idRol)
);

CREATE TABLE users
(
  idUser INT AUTO_INCREMENT,
  idRol INT,
  userName VARCHAR(100),
  password VARCHAR(100),
  firstName VARCHAR(300),
  lastName VARCHAR(100),
  email VARCHAR(100),

  CONSTRAINT pk_IdUser PRIMARY KEY (idUser),
  CONSTRAINT pk_idRol FOREIGN KEY (idRol) REFERENCES roles (idRol) ON DELETE CASCADE
);

CREATE TABLE purchases
(
  idPurchase int auto_increment,
  ticket_quantity int,
  discount boolean,
  date_purchase varchar (30),
  total float,
  idUser INT,

  constraint pk_idPurchase primary key (idPurchase),
  CONSTRAINT pk_idUser FOREIGN KEY (idUser) REFERENCES users (idUser) ON DELETE CASCADE
);

CREATE TABLE tickets
(
  idTicket INT auto_increment,
  ticket_number int,
  qr varchar(30),
  id_show int,
  idPurchase int,
  constraint pk_idTicket primary key (idTicket),
  constraint fk_id_show foreign key (id_show) references shows (id_show) on delete cascade,
  constraint fk_idPurchase foreign key (idPurchase) references purchases (idPurchase) on delete cascade
);

INSERT INTO roles (role) VALUES ('administrador'),('cliente');
INSERT INTO users (userName, password, firstName, lastName, email, idRol) VALUES
('admin','admin','admin', 'admin','admin@gmail.com',1),
('andreslerner', 'andres123','Andres', 'Lerner','andres@gmail.com',2);
