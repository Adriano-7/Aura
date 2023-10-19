-- SCHEMA: lbaw2384
SET search_path TO lbaw2384;

DROP TABLE IF EXISTS DenunciaComentario;
DROP TABLE IF EXISTS DenunciaEvento;
DROP TABLE IF EXISTS motivosDenuncia;
DROP TABLE IF EXISTS Denuncia;
DROP TABLE IF EXISTS TagEvento;
DROP TABLE IF EXISTS Tag;
DROP TABLE IF EXISTS Evento;
DROP TABLE IF EXISTS ClienteEvento;
DROP TABLE IF EXISTS ClienteOrganizacao;
DROP TABLE IF EXISTS Organizacao;
DROP TABLE IF EXISTS PedidoRegisto;
DROP TABLE IF EXISTS Ficheiro;
DROP TABLE IF EXISTS VotoComentario;
DROP TABLE IF EXISTS Comentario;
DROP TABLE IF EXISTS NotificacaoEvento;
DROP TABLE IF EXISTS NotificacaoUtilizador;
DROP TABLE IF EXISTS Notificacao;
DROP TABLE IF EXISTS Administrador;
DROP TABLE IF EXISTS Cliente;
DROP TABLE IF EXISTS Utilizador;

CREATE TABLE Utilizador (
    idUtilizador SERIAL PRIMARY KEY,
    nome VARCHAR(256)  NOT NULL,
    email VARCHAR(256) UNIQUE NOT NULL,
    password text NOT NULL,
    foto text
);

CREATE TABLE Organizacao (
    idOrganizacao SERIAL PRIMARY KEY,
    nome VARCHAR(255),
    descricao TEXT,
    aprovada BOOLEAN
);

CREATE TABLE Administrador (
    idUtilizador INTEGER PRIMARY KEY REFERENCES Utilizador (idUtilizador) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Cliente (
    idUtilizador INTEGER PRIMARY KEY REFERENCES Utilizador (idUtilizador) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE Notificacao (
    idNotificacao SERIAL PRIMARY KEY,
    texto TEXT,
    data TIMESTAMP NOT NULL CHECK (data <= now()),
    url VARCHAR(255) NOT NULL,
    visto BOOLEAN NOT NULL DEFAULT FALSE,
    utilizadorNotificado INTEGER NOT NULL REFERENCES Utilizador (idUtilizador) ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE TABLE NotificacaoEvento (
    idNotificacao INT,
    idUtilizador INT NOT NULL,
    FOREIGN KEY (idNotificacao) REFERENCES Notificacao (idNotificacao),
    FOREIGN KEY (idUtilizador) REFERENCES Utilizador (idUtilizador)
);




CREATE TABLE PedidoRegisto (
    idPedidoRegisto SERIAL PRIMARY KEY,
    data DATE,
    idUtilizador INT NOT NULL,
    idOrganizacao INT NOT NULL,
    FOREIGN KEY (idUtilizador) REFERENCES Cliente (idUtilizador),
    FOREIGN KEY (idOrganizacao) REFERENCES Organizacao (idOrganizacao)
);

CREATE TABLE Evento (
    idEvento SERIAL PRIMARY KEY,
    nome VARCHAR(255),
    descricao TEXT,
    localizacao VARCHAR(255),
    dataInicio DATE,
    dataFim DATE,
    isPublic BOOLEAN,
    idOrganizacao INT NOT NULL,
    FOREIGN KEY (idOrganizacao) REFERENCES Organizacao (idOrganizacao)
);

CREATE TABLE Comentario (
    idComentario SERIAL PRIMARY KEY,
    idUtilizador INT NOT NULL,
    texto TEXT,
    data DATE,
    balancoVotos INT,
    idEvento INT NOT NULL,
    FOREIGN KEY (idUtilizador) REFERENCES Cliente (idUtilizador),
    FOREIGN KEY (idEvento) REFERENCES Evento (idEvento)
);

CREATE TABLE VotoComentario (
    idUtilizador INT,
    idComentario INT,
    isUp BOOLEAN,
    PRIMARY KEY (idUtilizador, idComentario),
    FOREIGN KEY (idUtilizador) REFERENCES Cliente (idUtilizador),
    FOREIGN KEY (idComentario) REFERENCES Comentario (idComentario)
);

CREATE TABLE Ficheiro (
    idFicheiro SERIAL PRIMARY KEY,
    idComentario INT,
    caminho VARCHAR(255),
    nome VARCHAR(255),
    tipo VARCHAR(255),
    data DATE,
    FOREIGN KEY (idComentario) REFERENCES Comentario (idComentario)
);


CREATE TABLE NotificacaoUtilizador (
    idNotificacao SERIAL REFERENCES Notificacao (idNotificacao) ON UPDATE CASCADE ON DELETE CASCADE,
    idEvento SERIAL NOT NULL REFERENCES Evento (idEvento) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (idNotificacao, idEvento)
);


CREATE TABLE ClienteOrganizacao (
    idUtilizador INT,
    idOrganizacao INT,
    PRIMARY KEY (idUtilizador, idOrganizacao),
    FOREIGN KEY (idUtilizador) REFERENCES Cliente (idUtilizador),
    FOREIGN KEY (idOrganizacao) REFERENCES Organizacao (idOrganizacao)
);

CREATE TABLE ClienteEvento (
    idUtilizador INT,
    idEvento INT,
    PRIMARY KEY (idUtilizador, idEvento),
    FOREIGN KEY (idUtilizador) REFERENCES Cliente (idUtilizador),
    FOREIGN KEY (idEvento) REFERENCES Evento (idEvento)
);

CREATE TABLE Tag (
    idTag SERIAL PRIMARY KEY,
    nome VARCHAR(255)
);

CREATE TABLE TagEvento (
    idEvento INT,
    idTag INT,
    PRIMARY KEY (idEvento, idTag),
    FOREIGN KEY (idEvento) REFERENCES Evento (idEvento),
    FOREIGN KEY (idTag) REFERENCES Tag (idTag)
);

CREATE TABLE motivosDenuncia (
    idMotivoDenuncia SERIAL PRIMARY KEY
);

CREATE TABLE Denuncia (
    idDenuncia SERIAL PRIMARY KEY,
    resolvido BOOLEAN,
    data DATE,
    idMotivoDenuncia INT NOT NULL,
    FOREIGN KEY (idMotivoDenuncia) REFERENCES motivosDenuncia (idMotivoDenuncia)
);

CREATE TABLE DenunciaEvento (
    idDenuncia INT,
    idEvento INT NOT NULL,
    FOREIGN KEY (idDenuncia) REFERENCES Denuncia (idDenuncia),
    FOREIGN KEY (idEvento) REFERENCES Evento (idEvento)
);

CREATE TABLE DenunciaComentario (
    idDenuncia INT,
    idComentario INT NOT NULL,
    FOREIGN KEY (idDenuncia) REFERENCES Denuncia (idDenuncia),
    FOREIGN KEY (idComentario) REFERENCES Comentario (idComentario)
);


