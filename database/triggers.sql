-- SCHEMA: lbaw2384
create schema if not exists lbaw2384;

-- TRIGGERS

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
