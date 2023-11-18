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
    adress TEXT,
    venue TEXT,
    city TEXT,
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

INSERT INTO users (id, name, email, password, photo) VALUES
    ('1', 'João Silva', 'admin@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'joao_silva.jpeg'),
    ('2', 'Maria Santos', 'maria@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'maria_santos.jpeg'),
    ('3', 'António Pereira', 'antonio@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'antonio_pereira.jpeg'),
    ('4', 'Isabel Alves', 'isabel@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'isabel_alves.jpeg'),
    ('5', 'Francisco Rodrigues', 'francisco@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'francisco_rodrigues.jpeg'),
    ('6', 'Ana Carvalho', 'ana@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'ana_carvalho.jpeg'),
    ('7', 'Manuel Gomes', 'manuel@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'manuel_gomes.jpeg'),
    ('8', 'Sofia Fernandes', 'sofia@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'sofia_fernandes.jpeg'),
    ('9', 'Luís Sousa', 'luis@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'luis_sousa.jpeg'),
    ('10', 'Margarida Martins', 'margarida@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'margarida_martins.jpeg'),
    ('11', 'Carlos Costa', 'carlos@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'carlos_costa.jpeg'),
    ('12', 'Helena Oliveira', 'helena@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'helena_oliveira.jpeg'),
    ('13', 'Rui Torres', 'rui@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'rui_torres.jpeg'),
    ('14', 'Beatriz Pereira', 'beatriz@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'beatriz_pereira.jpeg'),
    ('15', 'José Ferreira', 'jose@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'jose_ferreira.jpeg'),
    ('16', 'Lúcia Santos', 'lucia@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'lucia_santos.jpeg'),
    ('17', 'Pedro Lopes', 'pedro@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'pedro_lopes.jpeg'),
    ('18', 'Teresa Rodrigues', 'teresa@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'teresa_rodrigues.jpeg'),
    ('19', 'Paulo Silva', 'paulo@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'paulo_silva.jpeg'),
    ('20', 'Catarina Santos', 'catarina@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'catarina_santos.jpeg');


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
    (1, 'Everything is new', 'A Everything is New é uma promotora de eventos portuguesa, fundada em 2005 por Álvaro Covões, Luís Montez e Vasco Sacramento. A promotora é responsável pela organização de eventos como o NOS Alive, o NOS Primavera Sound, o EDP Cool Jazz, o Super Bock Super Rock, o Sumol Summer Fest, o Vodafone Mexefest, o ID No Limits, o Brunch Electronik, o Jameson Urban Routes, o Super Bock em Stock, o Festival F, o Festival Iminente, o Festival Fado, o Festival Fuso, o Festival Silêncio, o Festival Músicas do Mundo, o Festival de Jazz de Cascais'),
    (2, 'Guns N Roses', 'Os Guns N Roses são uma banda de hard rock formada em 1985 em Los Angeles. A banda é composta por Axl Rose, Slash, Duff McKagan, Dizzy Reed, Richard Fortus, Frank Ferrer e Melissa Reese'),
    (3, 'Metallica', 'Os Metallica são uma banda de heavy metal formada em 1981 em Los Angeles. A banda é composta por James Hetfield, Lars Ulrich, Kirk Hammett e Robert Trujillo.'),
    (4,  'Foo Fighters', 'Os Foo Fighters são uma banda de rock alternativo formada em 1994 por Dave Grohl, ex-baterista dos Nirvana. A banda é composta por Dave Grohl, Taylor Hawkins, Nate Mendel, Chris Shiflett, Pat Smear e Rami Jaffee.'),
    (5,  'The Strokes', 'Os The Strokes são uma banda de rock alternativo formada em 1998 em Nova Iorque. A banda é composta por Julian Casablancas, Nick Valensi, Albert Hammond Jr., Nikolai Fraiture e Fabrizio Moretti.'),
    (6, 'Primavera Sound', 'O Primavera Sound Porto (anteriormente NOS Primavera Sound até 2022) é o homólogo português do festival Primavera Sound que se celebra em Barcelona desde 2001. [1] O cartaz do Primavera Sound Porto conta com uma ampla seleção de artistas internacionais, com uma significativa representação do panorama musical português. A linha artística segue as mesmas diretrizes do evento musical barcelonês, que se distingue pela variedade de estilos e pela aposta em novas bandas, destacando tanto o panorama local como artistas internacionais, com longas e respeitadas carreiras. Depois do sucesso da quarta edição, o Primavera Sound Porto é já uma parada obrigatória no panorama de festivais europeus. A excelente localização geográfica, as boas vias de comunicação da cidade com o resto da Europa e do Mundo e a distinção do festival no panorama musical português contribui para o crescimento da cidade, na sua projeção enquanto capital cultural e para a sua dinamização internacional como destino turístico.'),
    (7,  'TEUP - Tuna de Engenharia da Universidade do Porto',  'A Tuna de Engenharia da Universidade do Porto (TEUP) é uma tuna académica da Universidade do Porto, fundada em 1993. A TEUP é composta por estudantes e antigos estudantes da Faculdade de Engenharia da Universidade do Porto (FEUP).'),
    (8, 'Thirty Seconds to Mars', 'Os Thirty Seconds to Mars são uma banda de rock alternativo formada em 1998 em Los Angeles. A banda é composta por Jared Leto, Shannon Leto e Tomo Miličević.'),
    (9, 'Ornatos Violeta', 'Os Ornatos Violeta são uma banda de rock alternativo formada em 1991 em Coimbra. A banda é composta por Manel Cruz, Nuno Prata, Peixe, Kinörm e Elísio Donas.'),
    (10, 'Musica no Coração', 'A Música no Coração é uma promotora de eventos portuguesa, fundada em 1999 por Luís Montez. A promotora é responsável pela organização de eventos como o NOS Alive, o NOS Primavera Sound, o EDP Cool Jazz, o Super Bock Super Rock, o Sumol Summer Fest, o Vodafone Mexefest, o ID No Limits, o Brunch Electronik, o Jameson Urban Routes, o Super Bock em Stock, o Festival F, o Festival Iminente, o Festival Fado, o Festival Fuso, o Festival Silêncio, o Festival Músicas do Mundo, o Festival de Jazz de Cascais');

INSERT INTO events (id, name, description, photo, city, venue, adress, organization_id, start_date, end_date) VALUES
    ('1', 'NOS Alive', 
    'NOS Alive é um festival de música anual que acontece em Algés, Portugal. É organizado pela Everything is New e patrocinado pela NOS. O festival é conhecido por ter um cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip hop, pop e eletrónica.', 
    'nos-alive.jpg', 'Oeiras', 'Passeio Marítimo de Algés', 'Passeio Marítimo de Algés - 1495-165 Algés', 1, '2024-07-06 21:00:00', '2024-07-10 06:00:00'),

    ('2', 'Guns N Roses', 
    'A tour Not In This Lifetime dos Guns N Roses, que começou em 2016, é a terceira maior tour de sempre, tendo já passado por 3 continentes e 14 países, com mais de 5 milhões de bilhetes vendidos. A banda é composta por Axl Rose, Slash e Duff McKagan, membros originais da banda, e ainda por Dizzy Reed, Richard Fortus, Frank Ferrer e Melissa Reese.',
     'guns-n-roses.jpg', 'Lisboa', 'Altice Arena', 'Rossio dos Olivais, 1990-231 Lisboa', 2, '2024-12-14 21:00:00', '2024-12-14 23:00:00'),

    ('3', 'Metallica', 
    'Os Metallica são uma das bandas mais influentes e bem sucedidas de sempre, com mais de 110 milhões de álbuns vendidos em todo o mundo e inúmeros prémios e distinções. A banda foi formada em 1981 e é composta por James Hetfield, Lars Ulrich, Kirk Hammett e Robert Trujillo.',
     'metallica.jpg', 'Lisboa', 'Estádio do Restelo', 'Estádio do Restelo - Av. do Restelo 1449-016 Lisboa', 3, '2024-12-14 21:00:00', '2024-12-14 23:00:00'),

    ('4', 'Foo Fighters', 
    'Os Foo Fighters são uma banda de rock alternativo formada em 1994 por Dave Grohl, ex-baterista dos Nirvana. A banda é composta por Dave Grohl, Taylor Hawkins, Nate Mendel, Chris Shiflett, Pat Smear e Rami Jaffee.',
    'foo-fighters.jpeg', 'Lisboa', 'Estádio Nacional', 'Estádio Nacional - Av. Pierre de Coubertin, 1495-751 Cruz Quebrada-Dafundo', 4, '2024-12-14 21:00:00', '2024-12-14 23:00:00'),

    ('5', 'The Strokes', 
    'Os The Strokes são uma banda de rock alternativo formada em 1998 em Nova Iorque. A banda é composta por Julian Casablancas, Nick Valensi, Albert Hammond Jr., Nikolai Fraiture e Fabrizio Moretti.',
     'the-strokes.jpg', 'Lisboa', 'Altice Arena', 'Altice Arena - Rossio dos Olivais, 1990-231 Lisboa', 5, '2024-12-14 21:00:00', '2024-12-14 23:00:00'),

    ('6', 'NOS Primavera Sound', 
    'O NOS Primavera Sound é um festival de música anual que acontece no Parque da Cidade, no Porto. É organizado pela Everything is New e patrocinado pela NOS. O festival é conhecido por ter um cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip hop, pop e eletrónica.', 
    'nos-primavera-sound.jpg', 'Porto', 'Parque da Cidade', 'Parque da Cidade - 4100-099 Porto',  6, '2024-06-06 21:00:00', '2024-06-09 07:00:00'),

    ('7', 'Ornatos Violeta',
    'Os Ornatos Violeta são uma banda de rock alternativo formada em 1991 em Coimbra. A banda é composta por Manel Cruz, Nuno Prata, Peixe, Kinörm e Elísio Donas.',  
    'ornatos-violeta.jpg', 'Porto', 'Estádio do Dragão', 'Estádio do Dragão - Via Futebol Clube do Porto, 4350-415 Porto', 9, '2024-12-14 21:00:00', '2024-12-14 23:00:00'),

    ('8', 'Super Bock Super Rock', 
    'O Super Bock Super Rock é um festival de música anual que acontece no Parque das Nações, em Lisboa. É organizado pela Música no Coração e patrocinado pela Super Bock. O festival é conhecido por ter um cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip hop, pop e eletrónica.', 
    'super-rock-super-bock.png', 'Lisboa', 'Parque das Nações',  'Parque das Nações - 1990-231 Lisboa',10, '2024-08-14 21:00:00', '2024-08-18 21:00:00'),

    ('9', 'PortusCalle 23', 
    'O festival de tunas da FEUP já está na sua 11ª edição e o público estimado para preencher o Coliseu do Porto no tão aguardado espectáculo musical é de cerca de 3 mil pessoas. "É a 2ª vez que organizamos o festival, embora este ano seja a 1ª vez que temos disponível a lotação total do Coliseu", afirmou a equipa TEUP que está a frente do evento, acrescentando que "haverá uma surpresa, mas obviamente não poderá ser revelada". O PortusCalle vai decorrer nos dias 6, 7 e 8 de Novembro e pretende marcar o XXI aniversário da Tuna de Engenharia da Universidade do Porto, que será a anfitriã da noite. A comemoração vai envolver mais seis tunas do Porto, Lisboa, Aveiro e Viana do Castelo e uma programação especial, com direito a muita música, convívio e diversão. Nestes 21 anos, já passaram pela TEUP mais de 200 "tunos", que representaram a Faculdade de Engenharia, divulgando a sua música um pouco por todo mundo. "Uma vez tuno, tuno toda a vida!" - é assim que Luís Justiniano, ex-aluno FEUP, caracteriza o "espírito TEUP", certificando que este é o requisito mais importante para fazer parte da Tuna de Engenharia. "Nem é preciso cantar ou tocar", revela. O ex-aluno do curso de Engenharia Civil esteve ligado a TEUP entre 1993 e 2001, mas ainda hoje assiste a alguns ensaios e participa de encontros semestrais. Para o ex-integrante, as expectativas para a TEUP traduzem-se num futuro "cada vez mais sorridente".', 
    'PortusCalle-23-Coliseu-do-Porto.png', 'Porto', 'Coliseu do Porto', 'Coliseu do Porto - R. de Passos Manuel 137, 4000-385 Porto', 7, '2024-12-14 21:00:00', '2024-12-14 23:00:00'),
    
    ('10', 'Thirty Seconds to Mars', 
    'A tour The Monolith dos Thirty Seconds to Mars, que começou em 2018, é a quarta maior tour de sempre, tendo já passado por 3 continentes e 14 países, com mais de 5 milhões de bilhetes vendidos. A banda é composta por Jared Leto, Shannon Leto e Tomo Miličević.',
     'thirthy-seconds-to-mars.jpg', 'Lisboa', 'Altice Arena', 'Rossio dos Olivais, 1990-231 Lisboa', 8,'2024-12-14 21:00:00', '2024-12-14 23:00:00');

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
