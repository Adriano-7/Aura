-- SCHEMA: lbaw2384
SET search_path TO lbaw2324;

DROP TABLE IF EXISTS Utilizador CASCADE;
CREATE TABLE Utilizador (
    idUtilizador UUID PRIMARY KEY,
    nome TEXT  NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    foto TEXT
);

DROP TABLE IF EXISTS Cliente CASCADE;
CREATE TABLE Cliente (
    idUtilizador UUID PRIMARY KEY REFERENCES Utilizador (idUtilizador) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Administrador CASCADE;
CREATE TABLE Administrador (
    idUtilizador UUID PRIMARY KEY REFERENCES Utilizador (idUtilizador) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Organizacao CASCADE;
CREATE TABLE Organizacao (
    idOrganizacao UUID PRIMARY KEY,
    nome TEXT NOT NULL,
    descricao TEXT,
    foto TEXT,
    aprovada BOOLEAN NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS Evento CASCADE;
CREATE TABLE Evento (
    idEvento UUID PRIMARY KEY,
    nome TEXT NOT NULL,
    descricao TEXT,
    foto TEXT,
    localizacao TEXT,
    dataInicio TIMESTAMP NOT NULL,
    dataFim TIMESTAMP,
    isPublic BOOLEAN NOT NULL DEFAULT FALSE,
    idOrganizacao UUID NOT NULL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE,

    CONSTRAINT data_fim_check CHECK (dataFim IS NULL OR dataInicio < dataFim),
    CONSTRAINT data_inicio_check CHECK (dataInicio > current_timestamp)
);

DROP TABLE IF EXISTS Participante CASCADE;
CREATE TABLE Participante (
    idUtilizador UUID REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    idEvento UUID REFERENCES Evento (idEvento) ON DELETE CASCADE,
    PRIMARY KEY (idUtilizador, idEvento)
);

DROP TABLE IF EXISTS Organizador CASCADE;
CREATE TABLE Organizador (
    idUtilizador UUID REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    idOrganizacao UUID REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE,
    PRIMARY KEY (idUtilizador, idOrganizacao)
);

DROP TABLE IF EXISTS Tag CASCADE;
CREATE TABLE Tag (
    idTag UUID PRIMARY KEY,
    nome TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS TagEvento CASCADE;
CREATE TABLE TagEvento (
    idTag UUID REFERENCES Tag (idTag) ON DELETE CASCADE,
    idEvento UUID REFERENCES Evento (idEvento) ON DELETE CASCADE,
    PRIMARY KEY (idTag, idEvento)
);

DROP TABLE IF EXISTS Comentario CASCADE;
CREATE TABLE Comentario (
    idComentario UUID PRIMARY KEY,
    idAutor UUID NOT NULL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    texto TEXT NOT NULL,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    balancoVotos INT NOT NULL DEFAULT 0,
    idEvento UUID NOT NULL REFERENCES Evento (idEvento)
);

DROP TABLE IF EXISTS VotoComentario CASCADE;
CREATE TABLE VotoComentario (
    idComentario UUID REFERENCES Comentario (idComentario) ON DELETE CASCADE,
    idUtilizador UUID REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    isUp BOOLEAN NOT NULL,
    PRIMARY KEY (idComentario, idUtilizador)
);

DROP TABLE IF EXISTS Ficheiro CASCADE;
CREATE TABLE Ficheiro (
    idFicheiro UUID PRIMARY KEY,
    idComentario UUID NOT NULL REFERENCES Comentario (idComentario) ON DELETE CASCADE,
    caminho TEXT NOT NULL,
    nome TEXT NOT NULL,
    tipo TEXT NOT NULL
);

DROP TABLE IF EXISTS MotivoDenunciaEvento CASCADE;
CREATE TABLE MotivoDenunciaEvento (
    idMotivoDenunciaEvento UUID PRIMARY KEY,
    texto TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS DenunciaEvento CASCADE;
CREATE TABLE DenunciaEvento (
    idDenunciaEvento UUID PRIMARY KEY,
    idEvento UUID NOT NULL REFERENCES Evento (idEvento) ON DELETE CASCADE,
    resolvido BOOLEAN NOT NULL DEFAULT FALSE,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    idMotivoDenunciaEvento UUID NOT NULL REFERENCES MotivoDenunciaEvento (idMotivoDenunciaEvento) ON DELETE CASCADE
);

DROP TABLE IF EXISTS MotivoDenunciaComentario CASCADE;
CREATE TABLE MotivoDenunciaComentario (
    idMotivoDenunciaComentario UUID PRIMARY KEY,
    texto TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS DenunciaComentario CASCADE;
CREATE TABLE DenunciaComentario (
    idDenunciaCOmentario UUID,
    idComentario UUID NOT NULL REFERENCES Comentario (idComentario) ON DELETE CASCADE,
    resolvido BOOLEAN NOT NULL DEFAULT FALSE,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    idMotivoDenunciaComentario UUID NOT NULL REFERENCES MotivoDenunciaComentario (idMotivoDenunciaComentario) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfConvEvento CASCADE;
CREATE TABLE NotfConvEvento (
    idNotificacao UUID PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,
    idRecetor UUID NOT NULL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,

    idEmissor UUID NOT NULL,
    idEvento UUID NOT NULL,
    FOREIGN KEY (idEmissor, idEvento) REFERENCES Participante (idUtilizador, idEvento) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfConvOrganizacao CASCADE;
CREATE TABLE NotfConvOrganizacao (
    idNotificacao UUID PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor UUID NOT NULL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    idOrganizacao UUID NOT NULL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfEdicaoEvento CASCADE;
CREATE TABLE NotfEdicaoEvento (
    idNotificacao UUID PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor UUID NOT NULL,
    idEvento UUID NOT NULL,
    FOREIGN KEY (idRecetor, idEvento) REFERENCES Participante (idUtilizador, idEvento) ON DELETE CASCADE
);


DROP TABLE IF EXISTS NotfPedidoRegOrg CASCADE;
CREATE TABLE NotfPedidoRegOrg (
    idNotificacao UUID PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor UUID NOT NULL REFERENCES Administrador(idUtilizador) ON DELETE CASCADE,
    idOrganizacao UUID NOT NULL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfRespostaRegOrg CASCADE;
CREATE TABLE NotfRespostaRegOrg (
    idNotificacao UUID PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor UUID NOT NULL,
    idOrganizacao UUID NOT NULL,
    FOREIGN KEY (idRecetor, idOrganizacao) REFERENCES Organizador (idUtilizador, idOrganizacao) ON DELETE CASCADE
);
