CREATE SCHEMA IF NOT EXISTS lbaw2384;
SET search_path TO lbaw2384;

DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    photo TEXT
);

DROP TABLE IF EXISTS clients CASCADE;
CREATE TABLE clients (
    id SERIAL PRIMARY KEY REFERENCES users (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS administrators CASCADE;
CREATE TABLE administrators (
    id SERIAL PRIMARY KEY REFERENCES users (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS organizations CASCADE;
CREATE TABLE organizations (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    photo TEXT,
    approved BOOLEAN NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS events CASCADE;
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    photo TEXT,
    location TEXT,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP,
    is_public BOOLEAN NOT NULL DEFAULT FALSE,
    organization_id SERIAL NOT NULL REFERENCES organizations (id) ON DELETE CASCADE,

    CONSTRAINT end_date_check CHECK (end_date IS NULL OR start_date < end_date),
    CONSTRAINT start_date_check CHECK (start_date > current_timestamp)
);

DROP TABLE IF EXISTS participants CASCADE;
CREATE TABLE participants (
    user_id SERIAL REFERENCES clients (id) ON DELETE CASCADE,
    event_id SERIAL REFERENCES events (id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, event_id)
);

DROP TABLE IF EXISTS organizers CASCADE;
CREATE TABLE organizers (
    user_id SERIAL REFERENCES clients (id) ON DELETE CASCADE,
    organization_id SERIAL REFERENCES organizations (id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, organization_id)
);

DROP TABLE IF EXISTS tags CASCADE;
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS tag_event CASCADE;
CREATE TABLE tag_event (
    tag_id SERIAL REFERENCES tags (id) ON DELETE CASCADE,
    event_id SERIAL REFERENCES events (id) ON DELETE CASCADE,
    PRIMARY KEY (tag_id, event_id)
);

DROP TABLE IF EXISTS comments CASCADE;
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    author_id SERIAL NOT NULL REFERENCES clients (id) ON DELETE CASCADE,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    vote_balance INT NOT NULL DEFAULT 0,
    event_id SERIAL NOT NULL REFERENCES events (id)
);

DROP TABLE IF EXISTS vote_comments CASCADE;
CREATE TABLE vote_comments (
    comment_id SERIAL REFERENCES comments (id) ON DELETE CASCADE,
    user_id SERIAL REFERENCES clients (id) ON DELETE CASCADE,
    is_up BOOLEAN NOT NULL,
    PRIMARY KEY (comment_id, user_id)
);

DROP TABLE IF EXISTS files CASCADE;
CREATE TABLE files (
    id SERIAL PRIMARY KEY,
    comment_id SERIAL NOT NULL REFERENCES comments (id) ON DELETE CASCADE,
    path TEXT NOT NULL,
    name TEXT NOT NULL,
    type TEXT NOT NULL
);

DROP TABLE IF EXISTS report_reasons_event CASCADE;
CREATE TABLE report_reasons_event (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS reports_event CASCADE;
CREATE TABLE reports_event (
    id SERIAL PRIMARY KEY,
    event_id SERIAL NOT NULL REFERENCES events (id) ON DELETE CASCADE,
    resolved BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    reason_id SERIAL NOT NULL REFERENCES report_reasons_event (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS report_reasons_comment CASCADE;
CREATE TABLE report_reasons_comment (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS reports_comment CASCADE;
CREATE TABLE reports_comment (
    id SERIAL PRIMARY KEY,
    comment_id SERIAL NOT NULL REFERENCES comments (id) ON DELETE CASCADE,
    resolved BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    reason_id SERIAL NOT NULL REFERENCES report_reasons_comment (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS notf_inv_event CASCADE;
CREATE TABLE notf_inv_event (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    seen BOOLEAN NOT NULL DEFAULT FALSE,
    receiver_id SERIAL NOT NULL REFERENCES clients (id) ON DELETE CASCADE,

    emitter_id SERIAL NOT NULL,
    event_id SERIAL NOT NULL,
    FOREIGN KEY (emitter_id, event_id) REFERENCES participants (user_id, event_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS notf_inv_org CASCADE;
CREATE TABLE notf_inv_org (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    seen BOOLEAN NOT NULL DEFAULT FALSE,

    receiver_id SERIAL NOT NULL REFERENCES clients (id) ON DELETE CASCADE,
    organization_id SERIAL NOT NULL REFERENCES organizations (id) ON DELETE CASCADE
);

DROP TYPE IF EXISTS event_field CASCADE;
CREATE TYPE event_field AS ENUM ('name', 'description', 'location', 'end_date', 'start_date');

DROP TABLE IF EXISTS notf_edit_event CASCADE;
CREATE TABLE notf_edit_event (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    seen BOOLEAN NOT NULL DEFAULT FALSE,
    changed_field event_field NOT NULL,

    receiver_id SERIAL NOT NULL,
    event_id SERIAL NOT NULL,
    FOREIGN KEY (receiver_id, event_id) REFERENCES participants (user_id, event_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS notf_reg_req_org CASCADE;
CREATE TABLE notf_reg_req_org (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    seen BOOLEAN NOT NULL DEFAULT FALSE,

    receiver_id SERIAL NOT NULL REFERENCES administrators(id) ON DELETE CASCADE,
    organization_id SERIAL NOT NULL REFERENCES organizations (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS notf_res_reg_req_org CASCADE;
CREATE TABLE notf_res_reg_req_org (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    seen BOOLEAN NOT NULL DEFAULT FALSE,

    receiver_id SERIAL NOT NULL,
    organization_id SERIAL NOT NULL,
    FOREIGN KEY (receiver_id, organization_id) REFERENCES organizers (user_id, organization_id) ON DELETE CASCADE
);

-- Performance Indexes
-- Index 01
CREATE INDEX notf_inv_event_user ON notf_inv_event USING hash (receiver_id);

-- Index 02
CREATE INDEX notf_inv_event_date ON notf_inv_event USING btree (date);

-- Index 03
CREATE INDEX event_start_date ON events USING btree (start_date);
CLUSTER events USING event_start_date;

-- Full-text Search Indexes
-- Index 11

ALTER TABLE events
ADD COLUMN tsvectors TSVECTOR;

DROP FUNCTION IF EXISTS event_search_update();
CREATE FUNCTION event_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('portuguese', NEW.name), 'A') ||
            setweight(to_tsvector('portuguese', NEW.description), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('portuguese', NEW.name), 'A') ||
                setweight(to_tsvector('portuguese', NEW.description), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS event_search_update ON events CASCADE;
CREATE TRIGGER event_search_update 
    BEFORE INSERT OR UPDATE ON events
    FOR EACH ROW 
    EXECUTE PROCEDURE event_search_update();

-- TRIGGERS

-- TRIGGER01
-- Notificação de edição de evento
CREATE OR REPLACE FUNCTION notify_event_edit()
RETURNS TRIGGER AS $$
BEGIN
    IF  OLD.name IS DISTINCT FROM NEW.name 
    THEN
        INSERT INTO notf_edit_event (changed_field, receiver_id, event_id)
        SELECT 'name', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.description IS DISTINCT FROM NEW.description 
    THEN
        INSERT INTO notf_edit_event (changed_field, receiver_id, event_id)
        SELECT 'description', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.location IS DISTINCT FROM NEW.location 
    THEN
        INSERT INTO notf_edit_event (changed_field, receiver_id, event_id)
        SELECT 'location', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.start_date IS DISTINCT FROM NEW.start_date 
    THEN
        INSERT INTO notf_edit_event (changed_field, receiver_id, event_id)
        SELECT 'start_date', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.end_date IS DISTINCT FROM NEW.end_date 
    THEN
        INSERT INTO notf_edit_event (changed_field, receiver_id, event_id)
        SELECT 'end_date', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS notify_event_edit_trigger ON events CASCADE;
CREATE TRIGGER notify_event_edit_trigger
AFTER UPDATE ON events
FOR EACH ROW
EXECUTE FUNCTION notify_event_edit();


-- TRIGGER02
-- Notificação de aprovação de organização
CREATE OR REPLACE FUNCTION notify_organization_approval()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.approved = TRUE
    THEN
        INSERT INTO notf_res_reg_req_org (receiver_id, organization_id)
        SELECT organization.id, organization.id
        FROM organizations AS organization 
        WHERE organization.id = NEW.id;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS organization_approval_trigger ON organizations CASCADE;
CREATE TRIGGER organization_approval_trigger
AFTER UPDATE ON organizations
FOR EACH ROW
WHEN (OLD.approved = FALSE AND NEW.approved = TRUE)
EXECUTE FUNCTION notify_organization_approval();

-- TRIGGER03
-- Quando um comentário é apagado todos os votos desse comentário também são apagados
CREATE OR REPLACE FUNCTION delete_comment_votes()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM vote_comments
    WHERE vote_comments.comment_id = OLD.id;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_comment_votes_trigger ON comments CASCADE;
CREATE TRIGGER delete_comment_votes_trigger
AFTER DELETE ON comments
FOR EACH ROW
EXECUTE FUNCTION delete_comment_votes();

-- TRIGGER04
-- Um cliente pode apenas acrescentar comentários nos eventos em que participa. (BR06)
CREATE OR REPLACE FUNCTION check_participant_comment()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM participants p
        WHERE p.user_id = NEW.author_id AND p.event_id = NEW.event_id
    )
    THEN
        RAISE EXCEPTION 'The comment author does not participate in the event.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_participant_comment_trigger ON comments CASCADE;
CREATE TRIGGER check_participant_comment_trigger
BEFORE INSERT ON comments
FOR EACH ROW
EXECUTE FUNCTION check_participant_comment();


-- TRIGGER05
-- Um cliente só pode ter um voto em cada comentário. (BR07)
DROP TRIGGER IF EXISTS check_participant_comment_trigger ON comments CASCADE;
CREATE TRIGGER check_participant_comment_trigger
BEFORE INSERT ON comments
FOR EACH ROW
EXECUTE FUNCTION check_participant_comment();

CREATE OR REPLACE FUNCTION check_vote_comment()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM vote_comments v
        WHERE v.user_id = NEW.user_id AND v.comment_id = NEW.comment_id
    )
    THEN
        RAISE EXCEPTION 'The client has already voted on this comment.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_vote_comment_trigger ON vote_comments CASCADE;
CREATE TRIGGER check_vote_comment_trigger
BEFORE INSERT ON vote_comments
FOR EACH ROW
EXECUTE FUNCTION check_vote_comment();


-- TRIGGER06
-- Um cliente não pode pedir para participar num evento no qual já participa. (BR08)

CREATE OR REPLACE FUNCTION check_participant_event()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM participants p
        WHERE p.user_id = NEW.user_id AND p.event_id = NEW.event_id
    )
    THEN
        RAISE EXCEPTION 'The client is already participating in the event.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_participant_event_trigger ON participants CASCADE;
CREATE TRIGGER check_participant_event_trigger
BEFORE INSERT ON participants
FOR EACH ROW
EXECUTE FUNCTION check_participant_event();

INSERT INTO users (id, name, email, password) VALUES
    ('1', 'João Silva', 'admin@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('2', 'Maria Santos', 'maria@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('3', 'António Pereira', 'antonio@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('4', 'Isabel Alves', 'isabel@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('5', 'Francisco Rodrigues', 'francisco@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('6', 'Ana Carvalho', 'ana@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('7', 'Manuel Gomes', 'manuel@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('8', 'Sofia Fernandes', 'sofia@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('9', 'Luís Sousa', 'luis@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('10', 'Margarida Martins', 'margarida@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('11', 'Carlos Costa', 'carlos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('12', 'Helena Oliveira', 'helena@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('13', 'Rui Torres', 'rui@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('14', 'Beatriz Pereira', 'beatriz@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('15', 'José Ferreira', 'jose@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('16', 'Lúcia Santos', 'lucia@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('17', 'Pedro Lopes', 'pedro@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('18', 'Teresa Rodrigues', 'teresa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('19', 'Paulo Silva', 'paulo@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'),
    ('20', 'Catarina Santos', 'catarina@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W');

INSERT INTO administrators (id) VALUES
    ('1'),
    ('2');

INSERT INTO clients (id) VALUES
    ('3'),
    ('4'),
    ('5'),
    ('6'),
    ('7'),
    ('8'),
    ('9'),
    ('10'),
    ('11'),
    ('12'),
    ('13'),
    ('14'),
    ('15'),
    ('16'),
    ('17'),
    ('18'),
    ('19'),
    ('20');

INSERT INTO organizations (id, name, description) VALUES
    ('1', 'Xutos & Pontapés', 'Os Xutos & Pontapés são uma das bandas de rock mais icónicas de Portugal, conhecidos pelos seus hits e energia nos palcos.'),
    ('2', 'Amor Electro', 'Amor Electro é uma banda portuguesa de música pop e eletrónica, com uma sonoridade única e envolvente.'),
    ('3', 'Mão Morta', 'Mão Morta é uma banda de rock alternativo e experimental, famosa pela sua abordagem artística ousada.'),
    ('4', 'Os Azeitonas', 'Os Azeitonas são conhecidos pelas suas letras inteligentes e músicas contagiantes, abrangendo vários géneros musicais.'),
    ('5', 'Ornatos Violeta', 'Ornatos Violeta foi uma das bandas mais influentes da cena alternativa portuguesa, conhecida pela sua poesia e estilo único.'),
    ('6', 'Moonspell', 'Moonspell é uma banda de metal gótico que ganhou reconhecimento internacional pelo seu som sombrio e lírico.'),
    ('7', 'Os Quatro e Meia', 'Os Quatro e Meia são conhecidos pelo seu folk e pop rock com letras cativantes e emotivas.'),
    ('8', 'Capitão Fausto', 'Capitão Fausto é uma banda de rock alternativo e psicadélico com uma abordagem inovadora à música.');

INSERT INTO events (id, name, description, location, start_date, end_date, organization_id) VALUES
    ('1', 'Concerto dos Xutos & Pontapés', 'Concerto de celebração dos 40 anos dos Xutos & Pontapés.', 'Coliseu do Porto', '2024-12-14 21:00:00', '2024-12-14 23:00:00', '1'),
    ('2', 'Aniversário dos Amor Electro', 'Concerto de celebração dos 10 anos dos Amor Electro.', 'Pavilhão Atlântico, Lisboa', '2024-12-14 21:00:00', '2024-12-14 23:00:00', '2'),
    ('3', '30 Anos de Mão Morta', 'Concerto de celebração dos 30 anos dos Mão Morta.', 'Teatro Tivoli, Lisboa', '2024-12-21 21:00:00', '2024-12-21 23:00:00', '3'),
    ('4', '20 Anos dos Os Azeitonas', 'Concerto de celebração dos 20 anos dos Os Azeitonas.', 'Altice Arena, Lisboa', '2024-12-28 21:00:00', '2024-12-28 23:00:00', '4'),
    ('5', '20 Anos de Ornatos Violeta', 'Concerto de celebração dos 20 anos dos Ornatos Violeta.', 'Teatro São Luiz, Lisboa', '2024-01-04 21:00:00', '2024-01-04 23:00:00', '5'),
    ('6', '25 Anos dos Moonspell', 'Concerto de celebração dos 25 anos dos Moonspell.', 'Hard Club, Porto', '2024-01-11 21:00:00', '2024-01-11 23:00:00', '6'),
    ('7', '5 Anos dos Os Quatro e Meia', 'Concerto de celebração dos 5 anos dos Os Quatro e Meia.', 'Teatro Aveirense, Aveiro', '2024-01-18 21:00:00', '2024-01-18 23:00:00', '7'),
    ('8', '10 Anos dos Capitão Fausto', 'Concerto de celebração dos 10 anos dos Capitão Fausto.', 'Centro Cultural de Belém, Lisboa', '2024-01-25 21:00:00', '2024-01-25 23:00:00', '8');

INSERT INTO organizers (user_id, organization_id) VALUES
    ('3', '1'),
    ('4', '2'),
    ('5', '3'),
    ('6', '4'),
    ('7', '5'),
    ('8', '6'),
    ('9', '7'),
    ('10', '8');

INSERT INTO participants (user_id, event_id) VALUES
    ('4', '1'),
    ('5', '2'),
    ('6', '3'),
    ('7', '4'),
    ('8', '5'),
    ('9', '6'),
    ('10', '7'),
    ('11', '8'),
    ('12', '1'),
    ('13', '2'),
    ('14', '3'),
    ('15', '4'),
    ('16', '5'),
    ('17', '6'),
    ('18', '7'),
    ('19', '8'),
    ('20', '1');


INSERT INTO tags (id, name) VALUES
    ('1', 'Rock'),
    ('2', 'Pop'),
    ('3', 'Metal'),
    ('4', 'Alternativo'),
    ('5', 'Folk');

INSERT INTO tag_event (tag_id, event_id) VALUES
    ('1', '1'),
    ('2', '2'),
    ('3', '3'),
    ('4', '4'),
    ('5', '5'),
    ('1', '6'),
    ('2', '7'),
    ('3', '8');

INSERT INTO comments (id, author_id, text, event_id) VALUES
    ('1', '4', 'Vai ser um concerto incrível!', '1'),
    ('2', '5', 'Mal posso esperar!', '2'),
    ('3', '6', 'Certamente será um concerto fabuloso!', '3'),
    ('4', '7', 'Só quero que chegue este dia!', '4'),
    ('5', '8', 'Vai ser um concerto incrível!', '5'),
    ('6', '9', 'Mal posso esperar!', '6'),
    ('7', '10', 'Certamente será um concerto fabuloso!', '7'),
    ('8', '11', 'Só quero que chegue este dia!', '8');

INSERT INTO vote_comments (comment_id, user_id, is_up) VALUES
    ('1', '12', TRUE),
    ('2', '13', TRUE),
    ('3', '14', TRUE),
    ('4', '15', TRUE),
    ('5', '16', TRUE),
    ('6', '17', TRUE),
    ('7', '18', TRUE),
    ('8', '19', TRUE),
    ('1', '20', FALSE);

INSERT INTO report_reasons_event (id, text) VALUES
    ('1', 'Suspeita de fraude ou golpe'),
    ('2', 'Conteúdo inadequado ou ofensivo'),
    ('3', 'Informações incorretas sobre o evento');

INSERT INTO report_reasons_comment (id, text) VALUES
    ('1', 'Conteúdo inadequado ou não apropriado'),
    ('2', 'Ameaças ou incitação à violência'),
    ('3', 'Informações incorretas ou enganosas'),
    ('4', 'Assédio ou bullying'),
    ('5', 'Conteúdo comercial ou spam');

INSERT INTO reports_event (id, event_id, reason_id) VALUES
    ('1', '1', '1'),
    ('2', '2', '2'),
    ('3', '3', '3');

INSERT INTO reports_comment (id, comment_id, reason_id) VALUES
    ('1', '1', '1'),
    ('2', '2', '2'),
    ('3', '5', '5');
