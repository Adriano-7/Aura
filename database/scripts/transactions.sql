-- SCHEMA: lbaw2384
CREATE SCHEMA IF NOT EXISTS lbaw2384;
SET search_path TO lbaw2384;

-- TRAN01: Utilizador ($idUtilizador) dá upvote a um comentário ($idComentario).

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

IF (SELECT COUNT(*) FROM vote_comments
    WHERE user_id = $user_id AND comment_id = $comment_id) = 0 
THEN 
    INSERT INTO vote_comments (user_id, comment_id, is_up)
    VALUES ($user_id, $comment_id, TRUE);

    UPDATE comments
    SET vote_balance = vote_balance + 1
    WHERE id = $comment_id;
END IF;

COMMIT;


-- TRAN02: User ($userId) removes a downvote from a comment ($commentId)

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

IF EXISTS(
    SELECT * FROM vote_comments
    WHERE user_id = $user_id AND comment_id = $comment_id AND is_up = FALSE)
THEN
    DELETE FROM vote_comments
    WHERE user_id = $user_id AND comment_id = $comment_id;
    
    UPDATE comments
    SET vote_balance = vote_balance + 1
    WHERE id = $comment_id;
END IF;

COMMIT;


-- TRAN03: User ($userId) replaces an upvote with a downvote on a comment ($commentId)

BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

IF EXISTS(
    SELECT * FROM vote_comments
    WHERE user_id = $user_id AND comment_id = $comment_id AND is_up = TRUE)
THEN
    UPDATE vote_comments
    SET is_up = FALSE
    WHERE user_id = $user_id AND comment_id = $comment_id;
    
    UPDATE comments
    SET vote_balance = vote_balance - 2
    WHERE id = $comment_id;
END IF;

COMMIT;
