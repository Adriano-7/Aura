-- SCHEMA: lbaw2384
SET search_path TO lbaw2384;

INSERT INTO Utilizador (idUtilizador, nome, email, password) VALUES
    ('1', 'João Silva', 'joao@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('2', 'Maria Santos', 'maria@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('3', 'António Pereira', 'antonio@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('4', 'Isabel Alves', 'isabel@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('5', 'Francisco Rodrigues', 'francisco@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('6', 'Ana Carvalho', 'ana@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('7', 'Manuel Gomes', 'manuel@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('8', 'Sofia Fernandes', 'sofia@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('9', 'Luís Sousa', 'luis@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('10', 'Margarida Martins', 'margarida@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('11', 'Carlos Costa', 'carlos@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('12', 'Helena Oliveira', 'helena@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('13', 'Rui Torres', 'rui@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('14', 'Beatriz Pereira', 'beatriz@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('15', 'José Ferreira', 'jose@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('16', 'Lúcia Santos', 'lucia@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('17', 'Pedro Lopes', 'pedro@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('18', 'Teresa Rodrigues', 'teresa@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('19', 'Paulo Silva', 'paulo@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('20', 'Catarina Santos', 'catarina@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2');

INSERT INTO Administrador (idUtilizador) VALUES
    ('1'),
    ('2');

INSERT INTO Cliente (idUtilizador) VALUES
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

INSERT INTO Organizacao (idOrganizacao, nome, descricao) VALUES
    ('1', 'Xutos & Pontapés', 'Os Xutos & Pontapés são uma das bandas de rock mais icónicas de Portugal, conhecidos pelos seus hits e energia nos palcos.'),
    ('2', 'Amor Electro', 'Amor Electro é uma banda portuguesa de música pop e eletrónica, com uma sonoridade única e envolvente.'),
    ('3', 'Mão Morta', 'Mão Morta é uma banda de rock alternativo e experimental, famosa pela sua abordagem artística ousada.'),
    ('4', 'Os Azeitonas', 'Os Azeitonas são conhecidos pelas suas letras inteligentes e músicas contagiantes, abrangendo vários géneros musicais.'),
    ('5', 'Ornatos Violeta', 'Ornatos Violeta foi uma das bandas mais influentes da cena alternativa portuguesa, conhecida pela sua poesia e estilo único.'),
    ('6', 'Moonspell', 'Moonspell é uma banda de metal gótico que ganhou reconhecimento internacional pelo seu som sombrio e lírico.'),
    ('7', 'Os Quatro e Meia', 'Os Quatro e Meia são conhecidos pelo seu folk e pop rock com letras cativantes e emotivas.'),
    ('8', 'Capitão Fausto', 'Capitão Fausto é uma banda de rock alternativo e psicadélico com uma abordagem inovadora à música.');

INSERT INTO Evento (idEvento, nome, descricao, localizacao, dataInicio, dataFim, idOrganizacao) VALUES
    ('1', 'Concerto dos Xutos & Pontapés', 'Concerto de celebração dos 40 anos dos Xutos & Pontapés.', 'Coliseu do Porto', '2024-12-14 21:00:00', '2024-12-14 23:00:00', '1'),
    ('2', 'Aniversário dos Amor Electro', 'Concerto de celebração dos 10 anos dos Amor Electro.', 'Pavilhão Atlântico, Lisboa', '2024-12-14 21:00:00', '2024-12-14 23:00:00', '2'),
    ('3', '30 Anos de Mão Morta', 'Concerto de celebração dos 30 anos dos Mão Morta.', 'Teatro Tivoli, Lisboa', '2024-12-21 21:00:00', '2024-12-21 23:00:00', '3'),
    ('4', '20 Anos dos Os Azeitonas', 'Concerto de celebração dos 20 anos dos Os Azeitonas.', 'Altice Arena, Lisboa', '2024-12-28 21:00:00', '2024-12-28 23:00:00', '4'),
    ('5', '20 Anos de Ornatos Violeta', 'Concerto de celebração dos 20 anos dos Ornatos Violeta.', 'Teatro São Luiz, Lisboa', '2024-01-04 21:00:00', '2024-01-04 23:00:00', '5'),
    ('6', '25 Anos dos Moonspell', 'Concerto de celebração dos 25 anos dos Moonspell.', 'Hard Club, Porto', '2024-01-11 21:00:00', '2024-01-11 23:00:00', '6'),
    ('7', '5 Anos dos Os Quatro e Meia', 'Concerto de celebração dos 5 anos dos Os Quatro e Meia.', 'Teatro Aveirense, Aveiro', '2024-01-18 21:00:00', '2024-01-18 23:00:00', '7'),
    ('8', '10 Anos dos Capitão Fausto', 'Concerto de celebração dos 10 anos dos Capitão Fausto.', 'Centro Cultural de Belém, Lisboa', '2024-01-25 21:00:00', '2024-01-25 23:00:00', '8');

INSERT INTO Organizador (idUtilizador, idOrganizacao) VALUES
    ('3', '1'),
    ('4', '2'),
    ('5', '3'),
    ('6', '4'),
    ('7', '5'),
    ('8', '6'),
    ('9', '7'),
    ('10', '8');

INSERT INTO Participante (idUtilizador, idEvento) VALUES
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


INSERT INTO Tag (idTag, nome) VALUES
    ('1', 'Rock'),
    ('2', 'Pop'),
    ('3', 'Metal'),
    ('4', 'Alternativo'),
    ('5', 'Folk');

INSERT INTO TagEvento (idTag, idEvento) VALUES
    ('1', '1'),
    ('2', '2'),
    ('3', '3'),
    ('4', '4'),
    ('5', '5'),
    ('1', '6'),
    ('2', '7'),
    ('3', '8');

INSERT INTO Comentario (idComentario, idAutor, texto, idEvento) VALUES
    ('1', '4', 'Vai ser um concerto incrível!', '1'),
    ('2', '5', 'Mal posso esperar!', '2'),
    ('3', '6', 'Certamente será um concerto fabuloso!', '3'),
    ('4', '7', 'Só quero que chegue este dia!', '4'),
    ('5', '8', 'Vai ser um concerto incrível!', '5'),
    ('6', '9', 'Mal posso esperar!', '6'),
    ('7', '10', 'Certamente será um concerto fabuloso!', '7'),
    ('8', '11', 'Só quero que chegue este dia!', '8');

INSERT INTO VotoComentario (idComentario, idUtilizador, isUp) VALUES
    ('1', '12', TRUE),
    ('2', '13', TRUE),
    ('3', '14', TRUE),
    ('4', '15', TRUE),
    ('5', '16', TRUE),
    ('6', '17', TRUE),
    ('7', '18', TRUE),
    ('8', '19', TRUE),
    ('1', '20', FALSE);

INSERT INTO MotivoDenunciaEvento (idMotivo, texto) VALUES
    ('1', 'Suspeita de fraude ou golpe'),
    ('2', 'Conteúdo inadequado ou ofensivo'),
    ('3', 'Informações incorretas sobre o evento');

INSERT INTO MotivoDenunciaComentario (idMotivo, texto) VALUES
    ('1', 'Conteúdo inadequado ou não apropriado'),
    ('2', 'Ameaças ou incitação à violência'),
    ('3', 'Informações incorretas ou enganosas'),
    ('4', 'Assédio ou bullying'),
    ('5', 'Conteúdo comercial ou spam');

INSERT INTO DenunciaEvento (idDenunciaEvento, idEvento, idMotivo) VALUES
    ('1', '1', '1'),
    ('2', '2', '2'),
    ('3', '3', '3');

INSERT INTO DenunciaComentario (idDenunciaComentario, idComentario, idMotivo) VALUES
    ('1', '1', '1'),
    ('2', '2', '2'),
    ('3', '5', '5');
