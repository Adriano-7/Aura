-- SCHEMA: lbaw2384
CREATE SCHEMA IF NOT EXISTS lbaw2384;
SET search_path TO lbaw2384;

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
