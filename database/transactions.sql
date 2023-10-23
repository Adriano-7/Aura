-- TRAN01: Utilizador ($idUtilizador) dá upvote a um comentário ($idComentario).

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

IF (SELECT COUNT(*) FROM VotoComentario
    WHERE idUtilizador = $idUtilizador AND idComentario = $idComentario) = 0 
THEN 
    INSERT INTO VotoComentario (idUtilizador, idComentario, isUp)
    VALUES ($idUtilizador, $idComentario, TRUE);

    UPDATE Comentario
    SET balancoVotos = balancoVotos + 1
    WHERE idComentario = $idComentario;
END IF;

COMMIT;


-- TRAN02: Utilizador ($idUtilizador) remove downvote de um comentário ($idComentario)

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

IF EXISTS(
    SELECT * FROM VotoComentario
    WHERE idUtilizador = $idUtilizador AND idComentario = $idComentario AND isUp = FALSE)
THEN
    DELETE FROM VotoComentario
    WHERE idUtilizador = $idUtilizador AND idComentario = $idComentario;
    
    UPDATE Comentario
    SET balancoVotos = balancoVotos + 1
    WHERE idComentario = $idComentario;
END IF;

COMMIT;


-- TRAN03: Utilizador ($idUtilizador) substitui um upvote por um downvote num comentário ($idComentario)

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

IF EXISTS(
    SELECT * FROM VotoComentario
    WHERE idUtilizador = $idUtilizador AND idComentario = $idComentario AND isUp = TRUE)
THEN
    UPDATE VotoComentario
    SET isUp = FALSE
    WHERE idUtilizador = $idUtilizador AND idComentario = $idComentario;
    
    UPDATE Comentario
    SET balancoVotos = balancoVotos - 2
    WHERE idComentario = $idComentario;
END IF;

COMMIT;


-- TRAN04: Remover todos os eventos que já aconteceram

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

DELETE FROM Participante
WHERE idEvento IN (
    SELECT idEventoFROM Evento
    WHERE (dataFim IS NULL AND dataInicio < current_timestamp) OR dataFim < current_timestamp
);

DELETE FROM Evento
WHERE (dataFim IS NULL AND dataInicio < current_timestamp) OR dataFim < current_timestamp;

COMMIT;
