-- SCHEMA: lbaw2384
create schema if not exists lbaw2384;


-- TRIGGERS

--TRIGGER01
-- Notificação de edição de evento
CREATE OR REPLACE FUNCTION notificar_edicao_evento()
RETURNS TRIGGER AS $$
BEGIN
    IF  OLD.nome IS DISTINCT FROM NEW.nome 
    THEN
        INSERT INTO NotfEdicaoEvento (campoAlterado, idRecetor, idEvento)
        SELECT 'nome', p.idUtilizador, NEW.idEvento
        FROM Participante p
        WHERE p.idEvento = NEW.idEvento;
    END IF;
    IF OLD.descricao IS DISTINCT FROM NEW.descricao 
    THEN
        INSERT INTO NotfEdicaoEvento (campoAlterado, idRecetor, idEvento)
        SELECT 'descricao', p.idUtilizador, NEW.idEvento
        FROM Participante p
        WHERE p.idEvento = NEW.idEvento;
    END IF;
    IF OLD.localizacao IS DISTINCT FROM NEW.localizacao 
    THEN
        INSERT INTO NotfEdicaoEvento (campoAlterado, idRecetor, idEvento)
        SELECT 'localizacao', p.idUtilizador, NEW.idEvento
        FROM Participante p
        WHERE p.idEvento = NEW.idEvento;
    END IF;
    IF OLD.data_inicio IS DISTINCT FROM NEW.data_inicio 
    THEN
        INSERT INTO NotfEdicaoEvento (campoAlterado, idRecetor, idEvento)
        SELECT 'data_inicio', p.idUtilizador, NEW.idEvento
        FROM Participante p
        WHERE p.idEvento = NEW.idEvento;
    END IF;
    IF OLD.data_fim IS DISTINCT FROM NEW.data_fim 
    THEN
        INSERT INTO NotfEdicaoEvento (campoAlterado, idRecetor, idEvento)
        SELECT 'data_fim', p.idUtilizador, NEW.idEvento
        FROM Participante p
        WHERE p.idEvento = NEW.idEvento;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS notificar_edicao_evento_trigger ON Evento CASCADE;
CREATE TRIGGER notificar_edicao_evento_trigger
AFTER UPDATE ON Evento
FOR EACH ROW
EXECUTE FUNCTION notificar_edicao_evento();


--TRIGGER02
-- Notificação de aprovação de organização
CREATE OR REPLACE FUNCTION notificar_aprovacao_organizacao()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.aprovada = TRUE
    THEN
        INSERT INTO NotfRespostaRegOrg (idRecetor, idOrganizacao)
        SELECT Organizacao.idUtilizador, Organizacao.idOrganizacao
        FROM Organizacao 
        WHERE Organizacao.idOrganizacao = NEW.idOrganizacao;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS aprovacao_organizacao_trigger ON Organizacao CASCADE;
CREATE TRIGGER aprovacao_organizacao_trigger
AFTER UPDATE ON Organizacao
FOR EACH ROW
WHEN (OLD.aprovada = FALSE AND NEW.aprovada = TRUE)
EXECUTE FUNCTION notificar_aprovacao_organizacao();

--TRIGGER03
-- Quando um comentário é apagado todos os votos desse comentário também são apagados
CREATE OR REPLACE FUNCTION apagar_votos_comentario()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM VotoComentario
    WHERE VotoComentario.idComentario = OLD.idComentario;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS apagar_votos_comentario_trigger ON Comentario CASCADE;
CREATE TRIGGER apagar_votos_comentario_trigger
AFTER DELETE ON Comentario
FOR EACH ROW
EXECUTE FUNCTION apagar_votos_comentario();

--TRIGGER04
-- Um cliente pode apenas acrescentar comentários nos eventos em que participa. (BR06)
CREATE OR REPLACE FUNCTION check_participante_comentario()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (
        SELECT * 
        FROM Participante p
        WHERE p.idUtilizador = NEW.idAutor AND p.idEvento = NEW.idEvento
    )
    THEN
        RAISE EXCEPTION 'O autor do comentário não participa no evento.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_participante_comentario_trigger ON Comentario CASCADE;
CREATE TRIGGER check_participante_comentario_trigger
BEFORE INSERT ON Comentario
FOR EACH ROW
EXECUTE FUNCTION check_participante_comentario();


--TRIGGER05
-- Um cliente só pode ter um voto em cada comentário. (BR07)
DROP TRIGGER IF EXISTS check_participante_comentario_trigger ON Comentario CASCADE;
CREATE TRIGGER check_participante_comentario_trigger
BEFORE INSERT ON Comentario
FOR EACH ROW
EXECUTE FUNCTION check_participante_comentario();

CREATE OR REPLACE FUNCTION check_voto_comentario()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM VotoComentario v
        WHERE v.idUtilizador = NEW.idUtilizador AND v.idComentario = NEW.idComentario
    )
    THEN
        RAISE EXCEPTION 'O cliente já votou neste comentário.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_voto_comentario_trigger ON VotoComentario CASCADE;
CREATE TRIGGER check_voto_comentario_trigger
BEFORE INSERT ON VotoComentario

FOR EACH ROW
EXECUTE FUNCTION check_voto_comentario();


--TRIGGER06
-- Um cliente não pode pedir para participar num evento no qual já participa. (BR08)

CREATE OR REPLACE FUNCTION check_participante_evento()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM Participante p
        WHERE p.idUtilizador = NEW.idUtilizador AND p.idEvento = NEW.idEvento
    )
    THEN
        RAISE EXCEPTION 'O cliente já participa no evento.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_participante_evento_trigger ON Participante CASCADE;
CREATE TRIGGER check_participante_evento_trigger
BEFORE INSERT ON Participante
FOR EACH ROW
EXECUTE FUNCTION check_participante_evento();

--TRIGGER07
-- Um organizador não pode denunciar o seu próprio evento. (BR09)

CREATE OR REPLACE FUNCTION check_organizador_evento()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM Organizador o
        WHERE o.idUtilizador = NEW.idUtilizador AND o.idOrganizacao = (
            SELECT e.idOrganizacao
            FROM Evento e
            WHERE e.idEvento = NEW.idEvento
        )
    )
    THEN
        RAISE EXCEPTION 'O organizador não pode denunciar o seu próprio evento.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_organizador_evento_trigger ON DenunciaEvento CASCADE;
CREATE TRIGGER check_organizador_evento_trigger
BEFORE INSERT ON DenunciaEvento
FOR EACH ROW
EXECUTE FUNCTION check_organizador_evento();


--TRIGGER08
-- Um cliente não pode denunciar o seu próprio comentário. (BR10)

CREATE OR REPLACE FUNCTION check_cliente_comentario()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * 
        FROM Cliente c
        WHERE c.idUtilizador = NEW.idUtilizador AND NEW.idComentario = (
            SELECT c.idComentario
            FROM Comentario c
            WHERE c.idComentario = NEW.idComentario
        )
    )
    THEN
        RAISE EXCEPTION 'O cliente não pode denunciar o seu próprio comentário.';
    END IF;
    RETURN NEW;
END;

$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_cliente_comentario_trigger ON DenunciaComentario CASCADE;
CREATE TRIGGER check_cliente_comentario_trigger
BEFORE INSERT ON DenunciaComentario
FOR EACH ROW
EXECUTE FUNCTION check_cliente_comentario();
