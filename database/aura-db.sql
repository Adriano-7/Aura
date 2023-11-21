CREATE SCHEMA IF NOT EXISTS lbaw2384;
SET search_path TO lbaw2384;
SET client_encoding TO 'UTF8';

DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    photo TEXT default 'default.jpeg'
);

DROP TABLE IF EXISTS clients CASCADE;
CREATE TABLE clients (
    id INTEGER PRIMARY KEY REFERENCES users (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS administrators CASCADE;
CREATE TABLE administrators (
    id INTEGER PRIMARY KEY REFERENCES users (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS organizations CASCADE;
CREATE TABLE organizations (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    photo TEXT default 'default.png',
    approved BOOLEAN NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS events CASCADE;
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    photo TEXT default 'default.png',
    adress TEXT,
    venue TEXT,
    city TEXT,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP,
    is_public BOOLEAN NOT NULL DEFAULT FALSE,
    organization_id INTEGER NOT NULL REFERENCES organizations (id) ON DELETE CASCADE,

    CONSTRAINT end_date_check CHECK (end_date IS NULL OR start_date < end_date),
    CONSTRAINT start_date_check CHECK (start_date > current_timestamp)
);

DROP TABLE IF EXISTS participants CASCADE;
CREATE TABLE participants (
    user_id INTEGER REFERENCES clients (id) ON DELETE CASCADE,
    event_id INTEGER REFERENCES events (id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, event_id)
);

DROP TABLE IF EXISTS organizers CASCADE;
CREATE TABLE organizers (
    user_id INTEGER REFERENCES clients (id) ON DELETE CASCADE,
    organization_id INTEGER REFERENCES organizations (id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, organization_id)
);

DROP TABLE IF EXISTS tags CASCADE;
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS tag_event CASCADE;
CREATE TABLE tag_event (
    tag_id INTEGER REFERENCES tags (id) ON DELETE CASCADE,
    event_id INTEGER REFERENCES events (id) ON DELETE CASCADE,
    PRIMARY KEY (tag_id, event_id)
);

DROP TABLE IF EXISTS comments CASCADE;
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    author_id INTEGER NOT NULL REFERENCES clients (id) ON DELETE CASCADE,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    vote_balance INT NOT NULL DEFAULT 0,
    event_id INTEGER NOT NULL REFERENCES events (id)
);

DROP TABLE IF EXISTS vote_comments CASCADE;
CREATE TABLE vote_comments (
    comment_id INTEGER REFERENCES comments (id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES clients (id) ON DELETE CASCADE,
    is_up BOOLEAN NOT NULL,
    PRIMARY KEY (comment_id, user_id)
);

DROP TABLE IF EXISTS files CASCADE;
CREATE TABLE files (
    id SERIAL PRIMARY KEY,
    comment_id INTEGER NOT NULL REFERENCES comments (id) ON DELETE CASCADE,
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
    event_id INTEGER NOT NULL REFERENCES events (id) ON DELETE CASCADE,
    resolved BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    reason_id INTEGER NOT NULL REFERENCES report_reasons_event (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS report_reasons_comment CASCADE;
CREATE TABLE report_reasons_comment (
    id SERIAL PRIMARY KEY,
    text TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS reports_comment CASCADE;
CREATE TABLE reports_comment (
    id SERIAL PRIMARY KEY,
    comment_id INTEGER NOT NULL REFERENCES comments (id) ON DELETE CASCADE,
    resolved BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    reason_id INTEGER NOT NULL REFERENCES report_reasons_comment (id) ON DELETE CASCADE
);

DROP TYPE IF EXISTS notification_type CASCADE;
CREATE TYPE notification_type AS ENUM ('event_invitation', 'event_edit', 'organization_invitation', 'organization_registration_request', 'organization_registration_response');

DROP TYPE IF EXISTS event_field CASCADE;
CREATE TYPE event_field AS ENUM ('name', 'description', 'photo', 'adress', 'venue', 'city', 'start_date', 'end_date', 'is_public');

/*
    event_invitation -> user_emitter_id, event_id
    event_edit -> user_emitter_id, event_id, changed_field

    organization_invitation -> user_emitter_id, organization_id
    organization_registration_request -> user_emitter_id, organization_id

    organization_registration_response -> organization_id
*/

DROP TABLE IF EXISTS notifications CASCADE;
CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    date TIMESTAMP NOT NULL DEFAULT current_timestamp,
    seen BOOLEAN NOT NULL DEFAULT FALSE,
    receiver_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    type notification_type NOT NULL,

    /*'organization_invitation', 'organization_registration_request', 'organization_registration_response'*/
    organization_id INTEGER default null,

    /*'event_edit'*/
    changed_field event_field default null,

    /*'organization_registration_request', 'event_invitation'*/
    user_emitter_id INTEGER default null,

    /*'event_edit', 'event_invitation'*/
    event_id INTEGER default null,


    FOREIGN KEY (organization_id) REFERENCES organizations (id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE,
    FOREIGN KEY (user_emitter_id) REFERENCES users (id) ON DELETE CASCADE
);

-- Performance Indexes
/*
-- Index 01
CREATE INDEX notf_inv_event_user ON notf_inv_event USING hash (receiver_id);

-- Index 02
CREATE INDEX notf_inv_event_date ON notf_inv_event USING btree (date);
*/

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
-- Event edit notification

CREATE OR REPLACE FUNCTION notify_event_edit()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.name IS DISTINCT FROM NEW.name 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'name', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.description IS DISTINCT FROM NEW.description 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'description', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.adress IS DISTINCT FROM NEW.adress 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'adress', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.venue IS DISTINCT FROM NEW.venue 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'venue', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.city IS DISTINCT FROM NEW.city 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'city', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.start_date IS DISTINCT FROM NEW.start_date 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'start_date', p.user_id, NEW.id
        FROM participants p
        WHERE p.event_id = NEW.id;
    END IF;
    IF OLD.end_date IS DISTINCT FROM NEW.end_date 
    THEN
        INSERT INTO notifications (type, changed_field, receiver_id, event_id)
        SELECT 'event_edit', 'end_date', p.user_id, NEW.id
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
-- Notificação a todos os organizadores de uma organização da aprovação de um pedido de registo de organização
CREATE OR REPLACE FUNCTION notify_organization_approval()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.approved = TRUE
    THEN
        INSERT INTO notifications (type, receiver_id, organization_id)
        SELECT 'organization_registration_response', o.user_id, NEW.id
        FROM organizers o
        WHERE o.organization_id = NEW.id;
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
-- When a comment is deleted, all its votes are deleted as well
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
-- A client can only comment on an event in which he participates. (BR06)
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
-- A client can only vote on a comment once. (BR07)
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
-- A client cant request to join an event in which he already participates. (BR08)

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

/*Before insert on notification, check integrity of notification*/
CREATE OR REPLACE FUNCTION check_notification_insert() RETURNS TRIGGER AS $$
DECLARE
BEGIN
    IF NEW.type = 'event_invitation' THEN
        IF 
            NEW.user_emitter_id IS NULL 
            OR NEW.event_id IS NULL 
        THEN
            RAISE EXCEPTION 'Event invitation notification missing fields';
        ELSIF
            NEW.organization_id IS NOT NULL 
            OR NEW.changed_field IS NOT NULL 
        THEN
            RAISE EXCEPTION 'Event invitation notification has extra fields';
        ELSEIF
            EXISTS (SELECT * FROM administrators a WHERE a.id = NEW.user_emitter_id)
        THEN
            RAISE EXCEPTION 'Event invitation notification cannot be sent by an admin';
        END IF;
    ELSIF NEW.type = 'event_edit' THEN
        IF  
            NEW.event_id IS NULL 
            OR NEW.changed_field IS NULL 
        THEN
            RAISE EXCEPTION 'Event edit notification missing fields';
        ELSIF
            NEW.user_emitter_id IS NULL 
            OR NEW.organization_id IS NOT NULL 
        THEN
            RAISE EXCEPTION 'Event edit notification has extra fields';
        END IF;

    ELSIF NEW.type = 'organization_invitation' OR NEW.type = 'organization_registration_request' THEN
        IF 
            NEW.user_emitter_id IS NULL 
            OR NEW.organization_id IS NULL 
        THEN
            RAISE EXCEPTION 'Organization invitation or registration request notification missing fields';
        ELSIF
            NEW.event_id IS NOT NULL 
            OR NEW.changed_field IS NOT NULL 
        THEN
            RAISE EXCEPTION 'Organization invitation or registration request notification has extra fields';
        END IF;

    ELSIF NEW.type = 'organization_registration_response' THEN
        IF 
            NEW.organization_id IS NULL 
            OR NEW.event_id IS NOT NULL 
        THEN
            RAISE EXCEPTION 'Organization registration response notification missing fields';
        ELSIF
            NEW.user_emitter_id IS NOT NULL 
            OR NEW.changed_field IS NOT NULL 
        THEN
            RAISE EXCEPTION 'Organization registration response notification has extra fields';
        END IF;
    ELSE
        RAISE EXCEPTION 'Invalid notification';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_notification_insert_trigger ON notifications CASCADE;
CREATE TRIGGER check_notification_insert_trigger
BEFORE INSERT ON notifications
FOR EACH ROW
EXECUTE FUNCTION check_notification_insert();


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

INSERT INTO organizations (id, name, photo, approved, description) VALUES
    (14, 'Mac Miller', 'mac-miller.jpeg', TRUE,'Durante um período tragicamente breve, o rapper e produtor Mac Miller, sediado em Pittsburgh, conectou-se com legiões de ouvintes através do apelo de seu estilo instrumental curioso, com nuances de jazz, e letras sinceras que expuseram suas lutas contra a depressão e a dependência. Embora seu álbum de estúdio de estreia, "Blue Slide Park", lançado em 2011, tenha liderado as paradas, seu estilo e foco lírico mudaram em lançamentos subsequentes mais pessoais, como "The Divine Feminine" de 2016, que dominou as paradas de R&B e rap. Miller seguiu com "Swimming" em 2018, mas morreu de overdose um mês após o lançamento do álbum. Seu trabalho inicial teve um ressurgimento imediato à medida que os fãs de longa data lamentavam e aqueles que acabavam de descobrir o rapper exploravam seu trabalho pela primeira vez. O primeiro álbum póstumo de Miller, "Circles", foi concluído pelo produtor e lançado em 2020, alcançando o terceiro lugar nos EUA. Nascido como Malcolm McCormick, Miller usou inicialmente o pseudônimo Easy Mac, nome referenciado em sua primeira mixtape, "But My Mackin'' Ain''t Easy", de 2007. Sua mixtape "KIDS" tornou-se seu ponto de viragem quando foi lançada em agosto de 2010, recebendo muita atenção de blogs de hip-hop e garantindo a Miller um contrato de gravação com a Rostrum Records. Ele lançou seu primeiro EP, "On and on and Beyond", e seu álbum de estreia, "Blue Slide Park", em 2011. O álbum estreou em primeiro lugar na Billboard 200. Sua sétima mixtape, "Macadelic", chegou no ano seguinte, apresentando participações de [nomes não mencionados] (o conjunto foi posteriormente remasterizado para um lançamento na primavera de 2018). O esforço mais experimental, "Watching Movies with the Sound Off", seguiu em 2013, com nomes do hip-hop fora do convencional como [nomes não mencionados] colaborando. Um ano depois, Miller lançou a mixtape "Faces", assinou com [nome não mencionado] e lançou seu próprio selo, [nome não mencionado], sob a grande gravadora. "GO:OD AM" seguiu em 2015, com [nomes não mencionados] na lista de convidados do álbum. O single "100 Grandkids" atingiu a posição apropriada de número 100, enquanto "Weekend" foi certificado como ouro. Apenas um ano depois de "GO:OD AM" subir para o Top Five da Billboard 200 e das paradas de rap, Miller retornou com seu quarto álbum, "The Divine Feminine". O álbum contou com contribuições de convidados como [nomes não mencionados], [nomes não mencionados], [nomes não mencionados], [nomes não mencionados] e [nome não mencionado], que emprestou sua voz soulful ao primeiro single "Dang!". Um par de singles não pertencentes ao álbum ("Buttons" e "Programs") manteve Miller ocupado até 2018, quando lançou seu quinto álbum, "Swimming". Estreando em terceiro lugar tanto na Billboard 200 quanto nas paradas de R&B/hip-hop, o conjunto incluía as músicas "Small Worlds", "Self-Care" e "What''s the Use?". Um mês após o lançamento do álbum, Miller morreu de uma suspeita de overdose de drogas em sua casa no Vale de San Fernando. Ele tinha 26 anos. Após sua morte, sete de seus álbuns completos postumamente entraram na Billboard 200 (as mixtapes "Best Day Ever" e "Macadelic" estrearam nas paradas), e "Swimming" foi indicado ao Grammy na categoria de Melhor Álbum de Rap. No início de 2020, seu primeiro álbum póstumo foi lançado. Destinado a ser um complemento para "Swimming", "Circles" apresentava vocais gravados para esse projeto eventual, que foi concluído pelo produtor [nome não mencionado]. O álbum tornou-se o quinto de Miller a alcançar o Top Three nas paradas dos EUA. Mais tarde naquele ano, "KIDS" foi lançado em serviços de streaming pela primeira vez, o que o ajudou a voltar para a Billboard 200. Uma edição revisada de outra mixtape, "Faces", foi lançada comercialmente em 2021. No ano seguinte, sua mixtape de 2011, "I Love Life, Thank You", chegou aos serviços de streaming, levando a coleção ao número 22 na Billboard 200 (e ao Top Five na lista de álbuns independentes dos EUA). ~ David Jeffries & Neil Z. Yeung, Rovi'),
    (15, 'Linking Park', 'linking-park.jpg', TRUE,'Com o lançamento de "Living Things" em 2012, os incansáveis pioneiros do rap rock, Linkin Park, alcançaram o seu quarto consecutivo número 1 na Billboard 200, seguindo rapidamente em 2013 com "Recharged", um álbum de remisturas do lançamento original com produções de Steve Aoki e KillSonik. O som cada vez mais orientado para a dança da banda foi refletido nos seus concertos de 2013, nos quais Aoki apareceu ao lado da banda em uma série de performances de alta energia ancoradas pela remistura de Aoki da última música deles, "A Light That Never Comes". O álbum de estreia da banda em 2000, "Hybrid Theory", foi um sucesso da noite para o dia, tornando-se o álbum mais vendido de 2001 e rendendo à banda o seu primeiro Grammy pelo single "Crawling". O sucessor de 2003, "Meteora", foi ainda mais bem-sucedido, alcançando o primeiro lugar na Billboard 200 e gerando quatro singles em primeiro lugar, incluindo os favoritos dos fãs "Faint" e "Numb". A subsequente Meteora World Tour gerou milhões em vendas de ingressos, consolidando firmemente a presença contínua da banda no topo dos cartazes de festivais. Em 2013, o vocalista Chester Bennington anunciou a sua substituição de Scott Weiland como vocalista principal dos ícones alternativos dos anos 90, Stone Temple Pilots, mas os fãs podem ficar tranquilos - Linkin Park anunciou planos para lançar o seu sexto álbum de estúdio com Bennington em 2014 e realizará uma apresentação completa do seu álbum de estreia, "Hybrid Theory", no Download Festival de 2014.'),
    (8, 'Ornatos Violeta', 'ornatos-violeta.jpg',TRUE,'Os Ornatos Violeta são uma banda de rock alternativo formada em 1991 em Coimbra. A banda é composta por Manel Cruz, Nuno Prata, Peixe, Kinörm e Elísio Donas.'),
    (11, 'The National', 'the-national.jpg',TRUE,'Os The National são uma banda de indie rock formada em 1999 em Cincinnati. A banda é composta por Matt Berninger, Aaron Dessner, Bryce Dessner, Scott Devendorf e Bryan Devendorf.'),
    (12, 'The Killers', 'the-killers.jpg',TRUE, 'Os The Killers são uma banda de rock alternativo formada em 2001 em Las Vegas. A banda é composta por Brandon Flowers, Dave Keuning, Mark Stoermer e Ronnie Vannucci Jr.'),
    (13, 'The Cure', 'the-cure.jpg', TRUE,'Os The Cure são uma banda de rock alternativo formada em 1976 em Crawley. A banda é composta por Robert Smith, Simon Gallup, Roger ODonnell, Jason'),
    (3, 'Metallica', 'metallica.jpg', TRUE , 'Os Metallica foram formados pelo baterista Lars Ulrich e pelo cantor/guitarrista James Hetfield em Los Angeles em 1981; desde então, as suas incríveis conquistas de carreira incluem a conquista de oito Grammys e a venda de mais de 125 milhões de álbuns em todo o mundo, incluindo o aclamado álbum "Master of Puppets" (1986), frequentemente citado como um dos álbuns de metal mais importantes da história. Eles também são um dos atos de turnê mais populares e comercialmente bem-sucedidos da história - de acordo com a Pollstar, até 2019, os Metallica tinham vendido mais de 22 milhões de ingressos e arrecadado $1,4 bilhão em turnês desde 1982. O quinto álbum de estúdio dos Metallica, "Metallica" (1991), conhecido como The Black Album, vendeu mais de 30 milhões de cópias em todo o mundo e apresenta alguns dos maiores sucessos da banda: "Nothing Else Matters", "The Unforgiven" e "Enter Sandman". Em seis álbuns de estúdio consecutivos que definiram o género (de "Metallica" de 1991 a "Hardwired... to Self-Destruct" de 2016), os Metallica estrearam consistentemente em primeiro lugar na Billboard 200. Em apoio ao seu álbum de 2023 "72 Seasons", a M72 World Tour dos Metallica verá a banda se apresentando duas noites em cada cidade que visitam ao longo de 2023-24.'),    
    (10, 'Aerosmith', 'aerosmith.jpg',TRUE, 'Os Aerosmith são uma banda de hard rock formada em 1970 em Boston. A banda é composta por Steven Tyler, Joe Perry, Tom Hamilton, Joey Kramer e Brad Whitford.'),
    (2, 'Guns N Roses', 'guns-n-roses.jpg', TRUE,'Formados em 1985 em Los Angeles, os Guns N'' Roses são uma das bandas de rock mais vendidas da história. Além de mais de 100 milhões de discos vendidos em todo o mundo (incluindo o seu álbum de estreia "Appetite for Destruction", que atingiu 18 vezes platina), o grupo também é uma poderosa atração ao vivo, regularmente esgotando estádios e encabeçando grandes festivais. Eles se apresentaram pela primeira vez como Guns N'' Roses em junho de 1985 no Troubadour em Hollywood, Califórnia, e praticamente não saíram da estrada desde então, embora a banda tenha passado por várias mudanças de formação. A turnê "Not In This Lifetime..." de 2016 a 2019, que contou com os membros clássicos da formação original Axl Rose, Slash e Duff McKagan tocando juntos pela primeira vez desde 1993, é uma das turnês mais lucrativas da história. Até o momento, os Guns N'' Roses arrecadaram $774,1 milhões e venderam 9,6 milhões de ingressos ao longo de 453 shows.'),
    (4,  'Foo Fighters', 'foo-fighters.jpeg', TRUE, 'Os Foo Fighters são uma banda de rock alternativo formada em 1994 por Dave Grohl, ex-baterista dos Nirvana. A banda é composta por Dave Grohl, Taylor Hawkins, Nate Mendel, Chris Shiflett, Pat Smear e Rami Jaffee.'),
    (5,  'The Strokes', 'the-strokes.jpg',TRUE,'Os The Strokes são uma banda de rock alternativo formada em 1998 em Nova Iorque. A banda é composta por Julian Casablancas, Nick Valensi, Albert Hammond Jr., Nikolai Fraiture e Fabrizio Moretti.'),
    (6, 'Primavera Sound', 'primavera-sound.jpg',TRUE,'O Primavera Sound Porto (anteriormente NOS Primavera Sound até 2022) é o homólogo português do festival Primavera Sound que se celebra em Barcelona desde 2001. [1] O cartaz do Primavera Sound Porto conta com uma ampla seleção de artistas internacionais, com uma significativa representação do panorama musical português. A linha artística segue as mesmas diretrizes do evento musical barcelonês, que se distingue pela variedade de estilos e pela aposta em novas bandas, destacando tanto o panorama local como artistas internacionais, com longas e respeitadas carreiras. Depois do sucesso da quarta edição, o Primavera Sound Porto é já uma parada obrigatória no panorama de festivais europeus. A excelente localização geográfica, as boas vias de comunicação da cidade com o resto da Europa e do Mundo e a distinção do festival no panorama musical português contribui para o crescimento da cidade, na sua projeção enquanto capital cultural e para a sua dinamização internacional como destino turístico.'),
    (7,  'TEUP - Tuna de Engenharia da Universidade do Porto', 'teup.png',TRUE,'A Tuna de Engenharia da Universidade do Porto (TEUP) é uma tuna académica da Universidade do Porto, fundada em 1993. A TEUP é composta por estudantes e antigos estudantes da Faculdade de Engenharia da Universidade do Porto (FEUP).'),
    (1, 'Everything is new', 'everything-is-new.png', TRUE, 'A Everything is New é uma promotora de eventos portuguesa, fundada em 2005 por Álvaro Covões, Luís Montez e Vasco Sacramento. A promotora é responsável pela organização de eventos como o NOS Alive, o NOS Primavera Sound, o EDP Cool Jazz, o Super Bock Super Rock, o Sumol Summer Fest, o Vodafone Mexefest, o ID No Limits, o Brunch Electronik, o Jameson Urban Routes, o Super Bock em Stock, o Festival F, o Festival Iminente, o Festival Fado, o Festival Fuso, o Festival Silêncio, o Festival Músicas do Mundo, o Festival de Jazz de Cascais'),
    (9, 'Musica no Coração', 'musica-no-coracao.png',TRUE,'A Música no Coração é uma promotora de eventos portuguesa, fundada em 1999 por Luís Montez. A promotora é responsável pela organização de eventos como o NOS Alive, o NOS Primavera Sound, o EDP Cool Jazz, o Super Bock Super Rock, o Sumol Summer Fest, o Vodafone Mexefest, o ID No Limits, o Brunch Electronik, o Jameson Urban Routes, o Super Bock em Stock, o Festival F, o Festival Iminente, o Festival Fado, o Festival Fuso, o Festival Silêncio, o Festival Músicas do Mundo, o Festival de Jazz de Cascais'),
    (16, 'Klepht', 'klepht.jpeg', FALSE, 'Os Klepht são uma banda de pop rock formada em 2005 em Lisboa. A banda é composta por Diogo Dias, Filipe Ferreira, Filipe Silva, João Silva e Marco Nunes.'),
    (17, 'The Gift', 'the-gift.jpg', FALSE, 'Os The Gift são uma banda de pop rock formada em 1994 em Alcobaça. A banda é composta por Sónia Tavares, Nuno Gonçalves, John Gonçalves e Miguel Ribeiro.'),
    (18, 'Xutos e Pontapés', 'xutos-e-pontapes.jpg', FALSE, 'Os Xutos e Pontapés são uma banda de rock formada em 1978 em Lisboa. A banda é composta por Tim, Kalú, João Cabeleira e Gui.'),
    (19, 'Capitão Fausto', 'capitao-fausto.jpg', FALSE, 'Os Capitão Fausto são uma banda de rock formada em 2009 em Lisboa. A banda é composta por Tomás Wallenstein, Domingos Coimbra, Francisco Ferreira, Manuel Palha e Salvador Seabra.');

INSERT INTO events (id, name, photo, city, venue, adress, organization_id, start_date, end_date, description) VALUES
    ('1', 'NOS Alive', 'nos-alive.jpg', 'Oeiras', 'Passeio Marítimo de Algés', 'Passeio Marítimo de Algés - 1495-165 Algés', 1, '2024-07-06 21:00:00', '2024-07-10 06:00:00',    'NOS Alive é um festival de música anual que acontece em Algés, Portugal. É organizado pela Everything is New e patrocinado pela NOS. O festival é conhecido por ter um cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip hop, pop e eletrónica.'),
    ('2', 'Guns N Roses', 'guns-n-roses.jpg', 'Lisboa', 'Altice Arena', 'Rossio dos Olivais, 1990-231 Lisboa', 2, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'A tour Not In This Lifetime dos Guns N Roses, que começou em 2016, é a terceira maior tour de sempre, tendo já passado por 3 continentes e 14 países, com mais de 5 milhões de bilhetes vendidos. A banda é composta por Axl Rose, Slash e Duff McKagan, membros originais da banda, e ainda por Dizzy Reed, Richard Fortus, Frank Ferrer e Melissa Reese.'),
    ('3', 'Metallica', 'metallica.jpg', 'Lisboa', 'Estádio do Restelo', 'Estádio do Restelo - Av. do Restelo 1449-016 Lisboa', 3, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os Metallica são uma das bandas mais influentes e bem sucedidas de sempre, com mais de 110 milhões de álbuns vendidos em todo o mundo e inúmeros prémios e distinções. A banda foi formada em 1981 e é composta por James Hetfield, Lars Ulrich, Kirk Hammett e Robert Trujillo.'),
    ('4', 'Foo Fighters', 'foo-fighters.jpeg', 'Lisboa', 'Estádio Nacional', 'Estádio Nacional - Av. Pierre de Coubertin, 1495-751 Cruz Quebrada-Dafundo', 4, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os Foo Fighters são uma banda de rock alternativo formada em 1994 por Dave Grohl, ex-baterista dos Nirvana. A banda é composta por Dave Grohl, Taylor Hawkins, Nate Mendel, Chris Shiflett, Pat Smear e Rami Jaffee.'),
    ('5', 'The Strokes', 'the-strokes.jpg', 'Lisboa', 'Altice Arena', 'Altice Arena - Rossio dos Olivais, 1990-231 Lisboa', 5, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os The Strokes são uma banda de rock alternativo formada em 1998 em Nova Iorque. A banda é composta por Julian Casablancas, Nick Valensi, Albert Hammond Jr., Nikolai Fraiture e Fabrizio Moretti.'),
    ('6', 'NOS Primavera Sound', 'nos-primavera-sound.jpg', 'Porto', 'Parque da Cidade', 'Parque da Cidade - 4100-099 Porto',  6, '2024-06-06 21:00:00', '2024-06-09 07:00:00',    'O NOS Primavera Sound é um festival de música anual que acontece no Parque da Cidade, no Porto. É organizado pela Everything is New e patrocinado pela NOS. O festival é conhecido por ter um cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip hop, pop e eletrónica.'),
    ('7', 'Ornatos Violeta','ornatos-violeta.jpg', 'Porto', 'Estádio do Dragão', 'Estádio do Dragão - Via Futebol Clube do Porto, 4350-415 Porto', 8, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os Ornatos Violeta são uma banda de rock alternativo formada em 1991 em Coimbra. A banda é composta por Manel Cruz, Nuno Prata, Peixe, Kinörm e Elísio Donas.'),
    ('10', 'Aerosmith', 'aerosmith.jpg', 'Coimbra', 'Estádio Cidade de Coimbra', 'Estádio Cidade de Coimbra - Av. Cidade de Aeminium, 3030-183 Coimbra', 2, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os Aerosmith são uma banda de hard rock formada em 1970 em Boston. A banda é composta por Steven Tyler, Joe Perry, Tom Hamilton, Joey Kramer e Brad Whitford.'),
    ('11', 'The National', 'the-national.jpg', 'Lisboa', 'Altice Arena', 'Altice Arena - Rossio dos Olivais, 1990-231 Lisboa', 5, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os The National são uma banda de indie rock formada em 1999 em Cincinnati. A banda é composta por Matt Berninger, Aaron Dessner, Bryce Dessner, Scott Devendorf e Bryan Devendorf.'),
    ('8', 'Super Bock Super Rock', 'super-rock-super-bock.png', 'Lisboa', 'Parque das Nações',  'Parque das Nações - 1990-231 Lisboa',9, '2024-08-14 21:00:00', '2024-08-18 21:00:00',    'O Super Bock Super Rock é um festival de música anual que acontece no Parque das Nações, em Lisboa. É organizado pela Música no Coração e patrocinado pela Super Bock. O festival é conhecido por ter um cartaz eclético, com uma variedade de géneros musicais, incluindo rock, indie, metal, hip hop, pop e eletrónica.'),
    ('9', 'PortusCalle 23', 'PortusCalle-23-Coliseu-do-Porto.png', 'Porto', 'Coliseu do Porto', 'Coliseu do Porto - R. de Passos Manuel 137, 4000-385 Porto', 7, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'O festival de tunas da FEUP já está na sua 11ª edição e o público estimado para preencher o Coliseu do Porto no tão aguardado espectáculo musical é de cerca de 3 mil pessoas. "É a 2ª vez que organizamos o festival, embora este ano seja a 1ª vez que temos disponível a lotação total do Coliseu", afirmou a equipa TEUP que está a frente do evento, acrescentando que "haverá uma surpresa, mas obviamente não poderá ser revelada". O PortusCalle vai decorrer nos dias 6, 7 e 8 de Novembro e pretende marcar o XXI aniversário da Tuna de Engenharia da Universidade do Porto, que será a anfitriã da noite. A comemoração vai envolver mais seis tunas do Porto, Lisboa, Aveiro e Viana do Castelo e uma programação especial, com direito a muita música, convívio e diversão. Nestes 21 anos, já passaram pela TEUP mais de 200 "tunos", que representaram a Faculdade de Engenharia, divulgando a sua música um pouco por todo mundo. "Uma vez tuno, tuno toda a vida!" - é assim que Luís Justiniano, ex-aluno FEUP, caracteriza o "espírito TEUP", certificando que este é o requisito mais importante para fazer parte da Tuna de Engenharia. "Nem é preciso cantar ou tocar", revela. O ex-aluno do curso de Engenharia Civil esteve ligado a TEUP entre 1993 e 2001, mas ainda hoje assiste a alguns ensaios e participa de encontros semestrais. Para o ex-integrante, as expectativas para a TEUP traduzem-se num futuro "cada vez mais sorridente".'),
    ('12', 'The Killers', 'the-killers.jpg', 'Lisboa', 'Altice Arena', 'Altice Arena - Rossio dos Olivais, 1990-231 Lisboa', 5, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os The Killers são uma banda de rock alternativo formada em 2001 em Las Vegas. A banda é composta por Brandon Flowers, Dave Keuning, Mark Stoermer e Ronnie Vannucci Jr.'),
    ('13', 'The Cure', 'the-cure.jpg', 'Lisboa', 'Altice Arena', 'Altice Arena - Rossio dos Olivais, 1990-231 Lisboa', 5, '2024-12-14 21:00:00', '2024-12-14 23:00:00',    'Os The Cure são uma banda de rock alternativo formada em 1976 em Crawley. A banda é composta por Robert Smith, Simon Gallup, Roger ODonnell, Jason');

INSERT INTO organizers (user_id, organization_id) VALUES
    ('3', '1'),
    ('4', '2'),
    ('5', '3'),
    ('6', '4'),
    ('7', '5'),
    ('8', '6'),
    ('9', '7'),
    ('10', '8'),
    ('11', '9'),
    ('12', '10'),
    ('13', '11'),
    ('14', '12'),
    ('15', '13'),
    ('16', '14'),
    ('17', '15'),
    ('16', '16'),
    ('17', '17'),
    ('18', '18'),
    ('19', '19');

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

-- Just to test comments (add more variety to comments later)
INSERT INTO comments (id, author_id, text, event_id, date) VALUES
    ('9', '5', 'Só quero que chegue este dia!', '2', '2023-12-08');

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

--Insert notifications for event invite
INSERT INTO notifications(id, receiver_id, type, user_emitter_id, event_id) VALUES
    ('1', '3', 'event_invitation', '4', '1'),
    ('2', '3', 'event_invitation', '5', '2'),
    ('3', '5', 'event_invitation', '6', '3'),
    ('4', '4', 'event_invitation', '7', '4'),
    ('5', '7', 'event_invitation', '8', '5'),
    ('6', '9', 'event_invitation', '9', '6'),
    ('7', '10', 'event_invitation', '10', '7'),
    ('8', '12', 'event_invitation', '11', '8'),
    ('9', '11', 'event_invitation', '12', '1'),
    ('10', '15', 'event_invitation', '13', '2'),
    ('11', '18', 'event_invitation', '14', '3'),
    ('12', '20', 'event_invitation', '15', '4'),
    ('13', '19', 'event_invitation', '16', '5'),
    ('14', '14', 'event_invitation', '17', '6'),
    ('15', '13', 'event_invitation', '18', '7'),
    ('16', '12', 'event_invitation', '19', '8'),
    ('17', '11', 'event_invitation', '20', '1'),
    ('18', '10', 'event_invitation', '4', '2'),
    ('19', '9', 'event_invitation', '5', '3'),
    ('20', '8', 'event_invitation', '6', '4'),
    ('21', '7', 'event_invitation', '7', '5'),
    ('22', '6', 'event_invitation', '8', '6'),
    ('23', '5', 'event_invitation', '9', '7'),
    ('24', '4', 'event_invitation', '10', '8'),
    ('25', '3', 'event_invitation', '11', '1'),
    ('26', '4', 'event_invitation', '12', '2'),
    ('27', '5', 'event_invitation', '13', '3'),
    ('28', '6', 'event_invitation', '14', '4'),
    ('29', '7', 'event_invitation', '15', '5'),
    ('30', '8', 'event_invitation', '16', '6'),
    ('31', '9', 'event_invitation', '17', '7'),
    ('32', '10', 'event_invitation', '18', '8');

--Insert notifications for organization invite (the user_emitter becomes the organization)
INSERT INTO notifications(id, receiver_id, type, organization_id, user_emitter_id) VALUES
    ('33', '3', 'organization_invitation', '1', '3'),
    ('34', '4', 'organization_invitation', '2', '4'),
    ('35', '5', 'organization_invitation', '3', '5'),
    ('36', '6', 'organization_invitation', '4', '6'),
    ('37', '7', 'organization_invitation', '5', '7'),
    ('38', '8', 'organization_invitation', '6', '8'),
    ('39', '9', 'organization_invitation', '7', '9'),
    ('40', '10', 'organization_invitation', '8', '10'),
    ('41', '11', 'organization_invitation', '2', '4'),
    ('42', '12', 'organization_invitation', '3', '5'),
    ('43', '13', 'organization_invitation', '4', '6'),
    ('44', '14', 'organization_invitation', '5', '7'),
    ('45', '15', 'organization_invitation', '6', '8'),
    ('46', '16', 'organization_invitation', '7', '9'),
    ('47', '17', 'organization_invitation', '8', '10'),
    ('48', '18', 'organization_invitation', '1', '3'),
    ('49', '19', 'organization_invitation', '2', '4'),
    ('50', '20', 'organization_invitation', '3', '5'),
    ('51', '6', 'organization_invitation', '4', '6'),
    ('52', '7', 'organization_invitation', '5', '7'),
    ('53', '8', 'organization_invitation', '6', '8'),
    ('54', '9', 'organization_invitation', '7', '9'),
    ('55', '10', 'organization_invitation', '8', '10'),
    ('56', '11', 'organization_invitation', '1', '3'),
    ('57', '12', 'organization_invitation', '2', '4'),
    ('58', '13', 'organization_invitation', '3', '5'),
    ('59', '14', 'organization_invitation', '4', '6'),
    ('60', '15', 'organization_invitation', '5', '7'),
    ('61', '16', 'organization_invitation', '6', '8'),
    ('62', '17', 'organization_invitation', '7', '9');

--Insert notifications for organization register
INSERT INTO notifications(id, receiver_id, type, organization_id, user_emitter_id) VALUES
    ('63', '1',  'organization_registration_request', '16', '16'),
    ('64', '2',  'organization_registration_request', '16', '16'),
    ('65', '1',  'organization_registration_request', '17', '17'),
    ('66', '2',  'organization_registration_request', '17', '17'),
    ('67', '1',  'organization_registration_request', '18', '18'),
    ('68', '2',  'organization_registration_request', '18', '18'),
    ('69', '1',  'organization_registration_request', '19', '19'),
    ('70', '2',  'organization_registration_request', '19', '19');
