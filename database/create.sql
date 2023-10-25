create schema if not exists lbaw2384;
SET search_path TO lbaw2384;

DROP TABLE IF EXISTS Utilizador CASCADE;
CREATE TABLE Utilizador (
    idUtilizador SERIAL PRIMARY KEY,
    nome TEXT  NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    foto TEXT
);

DROP TABLE IF EXISTS Cliente CASCADE;
CREATE TABLE Cliente (
    idUtilizador SERIAL PRIMARY KEY REFERENCES Utilizador (idUtilizador) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Administrador CASCADE;
CREATE TABLE Administrador (
    idUtilizador SERIAL PRIMARY KEY REFERENCES Utilizador (idUtilizador) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Organizacao CASCADE;
CREATE TABLE Organizacao (
    idOrganizacao SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    descricao TEXT,
    foto TEXT,
    aprovada BOOLEAN NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS Evento CASCADE;
CREATE TABLE Evento (
    idEvento SERIAL PRIMARY KEY,
    nome TEXT NOT NULL,
    descricao TEXT,
    foto TEXT,
    localizacao TEXT,
    dataInicio TIMESTAMP NOT NULL,
    dataFim TIMESTAMP,
    isPublic BOOLEAN NOT NULL DEFAULT FALSE,
    idOrganizacao SERIAL NOT NULL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE,

    CONSTRAINT data_fim_check CHECK (dataFim IS NULL OR dataInicio < dataFim),
    CONSTRAINT data_inicio_check CHECK (dataInicio > current_timestamp)
);

DROP TABLE IF EXISTS Participante CASCADE;
CREATE TABLE Participante (
    idUtilizador SERIAL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    idEvento SERIAL REFERENCES Evento (idEvento) ON DELETE CASCADE,
    PRIMARY KEY (idUtilizador, idEvento)
);

DROP TABLE IF EXISTS Organizador CASCADE;
CREATE TABLE Organizador (
    idUtilizador SERIAL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    idOrganizacao SERIAL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE,
    PRIMARY KEY (idUtilizador, idOrganizacao)
);

DROP TABLE IF EXISTS Tag CASCADE;
CREATE TABLE Tag (
    idTag SERIAL PRIMARY KEY,
    nome TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS TagEvento CASCADE;
CREATE TABLE TagEvento (
    idTag SERIAL REFERENCES Tag (idTag) ON DELETE CASCADE,
    idEvento SERIAL REFERENCES Evento (idEvento) ON DELETE CASCADE,
    PRIMARY KEY (idTag, idEvento)
);

DROP TABLE IF EXISTS Comentario CASCADE;
CREATE TABLE Comentario (
    idComentario SERIAL PRIMARY KEY,
    idAutor SERIAL NOT NULL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    texto TEXT NOT NULL,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    balancoVotos INT NOT NULL DEFAULT 0,
    idEvento SERIAL NOT NULL REFERENCES Evento (idEvento)
);

DROP TABLE IF EXISTS VotoComentario CASCADE;
CREATE TABLE VotoComentario (
    idComentario SERIAL REFERENCES Comentario (idComentario) ON DELETE CASCADE,
    idUtilizador SERIAL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    isUp BOOLEAN NOT NULL,
    PRIMARY KEY (idComentario, idUtilizador)
);

DROP TABLE IF EXISTS Ficheiro CASCADE;
CREATE TABLE Ficheiro (
    idFicheiro SERIAL PRIMARY KEY,
    idComentario SERIAL NOT NULL REFERENCES Comentario (idComentario) ON DELETE CASCADE,
    caminho TEXT NOT NULL,
    nome TEXT NOT NULL,
    tipo TEXT NOT NULL
);

DROP TABLE IF EXISTS MotivoDenunciaEvento CASCADE;
CREATE TABLE MotivoDenunciaEvento (
    idMotivo SERIAL PRIMARY KEY,
    texto TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS DenunciaEvento CASCADE;
CREATE TABLE DenunciaEvento (
    idDenunciaEvento SERIAL PRIMARY KEY,
    idEvento SERIAL NOT NULL REFERENCES Evento (idEvento) ON DELETE CASCADE,
    resolvido BOOLEAN NOT NULL DEFAULT FALSE,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    idMotivo SERIAL NOT NULL REFERENCES MotivoDenunciaEvento (idMotivo) ON DELETE CASCADE
);

DROP TABLE IF EXISTS MotivoDenunciaComentario CASCADE;
CREATE TABLE MotivoDenunciaComentario (
    idMotivo SERIAL PRIMARY KEY,
    texto TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS DenunciaComentario CASCADE;
CREATE TABLE DenunciaComentario (
    idDenunciaComentario SERIAL,
    idComentario SERIAL NOT NULL REFERENCES Comentario (idComentario) ON DELETE CASCADE,
    resolvido BOOLEAN NOT NULL DEFAULT FALSE,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    idMotivo SERIAL NOT NULL REFERENCES MotivoDenunciaComentario (idMotivo) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfConvEvento CASCADE;
CREATE TABLE NotfConvEvento (
    idNotificacao SERIAL PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,
    idRecetor SERIAL NOT NULL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,

    idEmissor SERIAL NOT NULL,
    idEvento SERIAL NOT NULL,
    FOREIGN KEY (idEmissor, idEvento) REFERENCES Participante (idUtilizador, idEvento) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfConvOrganizacao CASCADE;
CREATE TABLE NotfConvOrganizacao (
    idNotificacao SERIAL PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor SERIAL NOT NULL REFERENCES Cliente (idUtilizador) ON DELETE CASCADE,
    idOrganizacao SERIAL NOT NULL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE
);

DROP TYPE IF EXISTS CampoEvento CASCADE;
CREATE TYPE CampoEvento AS ENUM ('nome', 'descricao', 'localizacao', 'data_fim', 'data_inicio');

DROP TABLE IF EXISTS NotfEdicaoEvento CASCADE;
CREATE TABLE NotfEdicaoEvento (
    idNotificacao SERIAL PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,
    campoAlterado CampoEvento NOT NULL,

    idRecetor SERIAL NOT NULL,
    idEvento SERIAL NOT NULL,
    FOREIGN KEY (idRecetor, idEvento) REFERENCES Participante (idUtilizador, idEvento) ON DELETE CASCADE
);


DROP TABLE IF EXISTS NotfPedidoRegOrg CASCADE;
CREATE TABLE NotfPedidoRegOrg (
    idNotificacao SERIAL PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor SERIAL NOT NULL REFERENCES Administrador(idUtilizador) ON DELETE CASCADE,
    idOrganizacao SERIAL NOT NULL REFERENCES Organizacao (idOrganizacao) ON DELETE CASCADE
);

DROP TABLE IF EXISTS NotfRespostaRegOrg CASCADE;
CREATE TABLE NotfRespostaRegOrg (
    idNotificacao SERIAL PRIMARY KEY,
    data TIMESTAMP NOT NULL DEFAULT current_timestamp,
    visto BOOLEAN NOT NULL DEFAULT FALSE,

    idRecetor SERIAL NOT NULL,
    idOrganizacao SERIAL NOT NULL,
    FOREIGN KEY (idRecetor, idOrganizacao) REFERENCES Organizador (idUtilizador, idOrganizacao) ON DELETE CASCADE
);

-- Performance Indexes
-- IDX01 
CREATE INDEX notf_conf_evento_utilizador ON NotfConvEvento USING hash (idRecetor);

--IDX02
CREATE INDEX notf_conf_evento_data ON NotfConvEvento USING btree (data);

--IDX03
CREATE INDEX evento_data_inicio ON Evento USING btree (dataInicio);
CLUSTER Evento USING evento_data_inicio;

-- Full-text Search Indexes
--IDX11
ALTER TABLE Evento
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS evento_search_update();
CREATE FUNCTION evento_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('portuguese', NEW.nome), 'A') ||
            setweight(to_tsvector('portuguese', NEW.descricao), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.nome <> OLD.nome OR NEW.descricao <> OLD.descricao) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('portuguese', NEW.nome), 'A') ||
                setweight(to_tsvector('portuguese', NEW.descricao), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER evento_search_update
    BEFORE INSERT OR UPDATE ON Evento
    FOR EACH ROW
    EXECUTE PROCEDURE evento_search_update();

CREATE INDEX search_idx ON Evento USING GIST (tsvectors);
