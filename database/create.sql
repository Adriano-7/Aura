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

CREATE TRIGGER event_search_update 
    BEFORE INSERT OR UPDATE ON events
    FOR EACH ROW 
    EXECUTE PROCEDURE event_search_update();

CREATE INDEX search_idx ON events USING GIST (tsvectors);