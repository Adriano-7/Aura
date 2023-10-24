
INSERT INTO Utilizador (idUtilizador, nome, email, password) VALUES
    ('123e4567-e89b-12d3-a456-426655440001', 'João Silva', 'joao@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440002', 'Maria Santos', 'maria@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440003', 'António Pereira', 'antonio@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440004', 'Isabel Alves', 'isabel@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440005', 'Francisco Rodrigues', 'francisco@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440006', 'Ana Carvalho', 'ana@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440007', 'Manuel Gomes', 'manuel@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440008', 'Sofia Fernandes', 'sofia@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440009', 'Luís Sousa', 'luis@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440010', 'Margarida Martins', 'margarida@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440011', 'Carlos Costa', 'carlos@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440012', 'Helena Oliveira', 'helena@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440013', 'Rui Torres', 'rui@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440014', 'Beatriz Pereira', 'beatriz@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440015', 'José Ferreira', 'jose@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440016', 'Lúcia Santos', 'lucia@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440017', 'Pedro Lopes', 'pedro@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440018', 'Teresa Rodrigues', 'teresa@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440019', 'Paulo Silva', 'paulo@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2'),
    ('123e4567-e89b-12d3-a456-426655440020', 'Catarina Santos', 'catarina@example.com', '$2y$10$D6D4HftdCKh64zpaP/AjVOC3iZ1tw7Nh1s5ifYSe5vWfmcQeR68m2');

INSERT INTO Administrador (idUtilizador) VALUES
    ('123e4567-e89b-12d3-a456-426655440001'),
    ('123e4567-e89b-12d3-a456-426655440002');

INSERT INTO Cliente (idUtilizador) VALUES
    ('123e4567-e89b-12d3-a456-426655440003'),
    ('123e4567-e89b-12d3-a456-426655440004'),
    ('123e4567-e89b-12d3-a456-426655440005'),
    ('123e4567-e89b-12d3-a456-426655440006'),
    ('123e4567-e89b-12d3-a456-426655440007'),
    ('123e4567-e89b-12d3-a456-426655440008'),
    ('123e4567-e89b-12d3-a456-426655440009'),
    ('123e4567-e89b-12d3-a456-426655440010'),
    ('123e4567-e89b-12d3-a456-426655440011'),
    ('123e4567-e89b-12d3-a456-426655440012'),
    ('123e4567-e89b-12d3-a456-426655440013'),
    ('123e4567-e89b-12d3-a456-426655440014'),
    ('123e4567-e89b-12d3-a456-426655440015'),
    ('123e4567-e89b-12d3-a456-426655440016'),
    ('123e4567-e89b-12d3-a456-426655440017'),
    ('123e4567-e89b-12d3-a456-426655440018'),
    ('123e4567-e89b-12d3-a456-426655440019'),
    ('123e4567-e89b-12d3-a456-426655440020');

INSERT INTO Organizacao (idOrganizacao, nome, descricao) VALUES
    ('123e4567-e89b-12d3-a456-426655440021', 'Xutos & Pontapés', 'Os Xutos & Pontapés são uma das bandas de rock mais icónicas de Portugal, conhecidos pelos seus hits e energia nos palcos.'),
    ('123e4567-e89b-12d3-a456-426655440022', 'Amor Electro', 'Amor Electro é uma banda portuguesa de música pop e eletrónica, com uma sonoridade única e envolvente.'),
    ('123e4567-e89b-12d3-a456-426655440023', 'Mão Morta', 'Mão Morta é uma banda de rock alternativo e experimental, famosa pela sua abordagem artística ousada.'),
    ('123e4567-e89b-12d3-a456-426655440024', 'Os Azeitonas', 'Os Azeitonas são conhecidos pelas suas letras inteligentes e músicas contagiantes, abrangendo vários géneros musicais.'),
    ('123e4567-e89b-12d3-a456-426655440025', 'Ornatos Violeta', 'Ornatos Violeta foi uma das bandas mais influentes da cena alternativa portuguesa, conhecida pela sua poesia e estilo único.'),
    ('123e4567-e89b-12d3-a456-426655440026', 'Moonspell', 'Moonspell é uma banda de metal gótico que ganhou reconhecimento internacional pelo seu som sombrio e lírico.'),
    ('123e4567-e89b-12d3-a456-426655440027', 'Os Quatro e Meia', 'Os Quatro e Meia são conhecidos pelo seu folk e pop rock com letras cativantes e emotivas.'),
    ('123e4567-e89b-12d3-a456-426655440028', 'Capitão Fausto', 'Capitão Fausto é uma banda de rock alternativo e psicadélico com uma abordagem inovadora à música.');

INSERT INTO Evento (idEvento, nome, descricao, localizacao, dataInicio, dataFim, idOrganizacao) VALUES
    ('123e4567-e89b-12d3-a456-426655440029', 'Concerto dos Xutos & Pontapés', 'Concerto de celebração dos 40 anos dos Xutos & Pontapés.', 'Coliseu do Porto', '2019-12-07 21:00:00', '2019-12-07 23:00:00', '123e4567-e89b-12d3-a456-426655440021'),
    ('123e4567-e89b-12d3-a456-426655440030', 'Aniversário dos Amor Electro', 'Concerto de celebração dos 10 anos dos Amor Electro.', 'Pavilhão Atlântico, Lisboa', '2019-12-14 21:00:00', '2019-12-14 23:00:00', '123e4567-e89b-12d3-a456-426655440022'),
    ('123e4567-e89b-12d3-a456-426655440031', '30 Anos de Mão Morta', 'Concerto de celebração dos 30 anos dos Mão Morta.', 'Teatro Tivoli, Lisboa', '2019-12-21 21:00:00', '2019-12-21 23:00:00', '123e4567-e89b-12d3-a456-426655440023'),
    ('123e4567-e89b-12d3-a456-426655440032', '20 Anos dos Os Azeitonas', 'Concerto de celebração dos 20 anos dos Os Azeitonas.', 'Altice Arena, Lisboa', '2019-12-28 21:00:00', '2019-12-28 23:00:00', '123e4567-e89b-12d3-a456-426655440024'),
    ('123e4567-e89b-12d3-a456-426655440033', '20 Anos de Ornatos Violeta', 'Concerto de celebração dos 20 anos dos Ornatos Violeta.', 'Teatro São Luiz, Lisboa', '2020-01-04 21:00:00', '2020-01-04 23:00:00', '123e4567-e89b-12d3-a456-426655440025'),
    ('123e4567-e89b-12d3-a456-426655440034', '25 Anos dos Moonspell', 'Concerto de celebração dos 25 anos dos Moonspell.', 'Hard Club, Porto', '2020-01-11 21:00:00', '2020-01-11 23:00:00', '123e4567-e89b-12d3-a456-426655440026'),
    ('123e4567-e89b-12d3-a456-426655440035', '5 Anos dos Os Quatro e Meia', 'Concerto de celebração dos 5 anos dos Os Quatro e Meia.', 'Teatro Aveirense, Aveiro', '2020-01-18 21:00:00', '2020-01-18 23:00:00', '123e4567-e89b-12d3-a456-426655440027'),
    ('123e4567-e89b-12d3-a456-426655440036', '10 Anos dos Capitão Fausto', 'Concerto de celebração dos 10 anos dos Capitão Fausto.', 'Centro Cultural de Belém, Lisboa', '2020-01-25 21:00:00', '2020-01-25 23:00:00', '123e4567-e89b-12d3-a456-426655440028');

INSERT INTO Organizador (idUtilizador, idOrganizacao) VALUES
    ('123e4567-e89b-12d3-a456-426655440003', '123e4567-e89b-12d3-a456-426655440021'),
    ('123e4567-e89b-12d3-a456-426655440004', '123e4567-e89b-12d3-a456-426655440022'),
    ('123e4567-e89b-12d3-a456-426655440005', '123e4567-e89b-12d3-a456-426655440023'),
    ('123e4567-e89b-12d3-a456-426655440006', '123e4567-e89b-12d3-a456-426655440024'),
    ('123e4567-e89b-12d3-a456-426655440007', '123e4567-e89b-12d3-a456-426655440025'),
    ('123e4567-e89b-12d3-a456-426655440008', '123e4567-e89b-12d3-a456-426655440026'),
    ('123e4567-e89b-12d3-a456-426655440009', '123e4567-e89b-12d3-a456-426655440027'),
    ('123e4567-e89b-12d3-a456-426655440010', '123e4567-e89b-12d3-a456-426655440028');

INSERT INTO Participante (idUtilizador, idEvento) VALUES
    ('123e4567-e89b-12d3-a456-426655440004', '123e4567-e89b-12d3-a456-426655440029'),
    ('123e4567-e89b-12d3-a456-426655440005', '123e4567-e89b-12d3-a456-426655440030'),
    ('123e4567-e89b-12d3-a456-426655440006', '123e4567-e89b-12d3-a456-426655440031'),
    ('123e4567-e89b-12d3-a456-426655440007', '123e4567-e89b-12d3-a456-426655440032'),
    ('123e4567-e89b-12d3-a456-426655440008', '123e4567-e89b-12d3-a456-426655440033'),
    ('123e4567-e89b-12d3-a456-426655440009', '123e4567-e89b-12d3-a456-426655440034'),
    ('123e4567-e89b-12d3-a456-426655440010', '123e4567-e89b-12d3-a456-426655440035'),
    ('123e4567-e89b-12d3-a456-426655440011', '123e4567-e89b-12d3-a456-426655440036'),
    ('123e4567-e89b-12d3-a456-426655440012', '123e4567-e89b-12d3-a456-426655440029'),
    ('123e4567-e89b-12d3-a456-426655440013', '123e4567-e89b-12d3-a456-426655440030'),
    ('123e4567-e89b-12d3-a456-426655440014', '123e4567-e89b-12d3-a456-426655440031'),
    ('123e4567-e89b-12d3-a456-426655440015', '123e4567-e89b-12d3-a456-426655440032'),
    ('123e4567-e89b-12d3-a456-426655440016', '123e4567-e89b-12d3-a456-426655440033'),
    ('123e4567-e89b-12d3-a456-426655440017', '123e4567-e89b-12d3-a456-426655440034'),
    ('123e4567-e89b-12d3-a456-426655440018', '123e4567-e89b-12d3-a456-426655440035'),
    ('123e4567-e89b-12d3-a456-426655440019', '123e4567-e89b-12d3-a456-426655440036'),
    ('123e4567-e89b-12d3-a456-426655440020', '123e4567-e89b-12d3-a456-426655440029');


INSERT INTO Tag (idTag, nome) VALUES
    ('123e4567-e89b-12d3-a456-426655440037', 'Rock'),
    ('123e4567-e89b-12d3-a456-426655440038', 'Pop'),
    ('123e4567-e89b-12d3-a456-426655440039', 'Metal'),
    ('123e4567-e89b-12d3-a456-426655440040', 'Alternativo'),
    ('123e4567-e89b-12d3-a456-426655440041', 'Folk');

INSERT INTO TagEvento (idTag, idEvento) VALUES
    ('123e4567-e89b-12d3-a456-426655440037', '123e4567-e89b-12d3-a456-426655440029'),
    ('123e4567-e89b-12d3-a456-426655440038', '123e4567-e89b-12d3-a456-426655440030'),
    ('123e4567-e89b-12d3-a456-426655440039', '123e4567-e89b-12d3-a456-426655440031'),
    ('123e4567-e89b-12d3-a456-426655440040', '123e4567-e89b-12d3-a456-426655440032'),
    ('123e4567-e89b-12d3-a456-426655440041', '123e4567-e89b-12d3-a456-426655440033'),
    ('123e4567-e89b-12d3-a456-426655440037', '123e4567-e89b-12d3-a456-426655440034'),
    ('123e4567-e89b-12d3-a456-426655440038', '123e4567-e89b-12d3-a456-426655440035'),
    ('123e4567-e89b-12d3-a456-426655440039', '123e4567-e89b-12d3-a456-426655440036');

INSERT INTO Comentario (idComentario, idAutor, texto, idEvento) VALUES
    ('123e4567-e89b-12d3-a456-426655440042', '123e4567-e89b-12d3-a456-426655440004', 'Vai ser um concerto incrível!', '123e4567-e89b-12d3-a456-426655440029'),
    ('123e4567-e89b-12d3-a456-426655440043', '123e4567-e89b-12d3-a456-426655440005', 'Mal posso esperar!', '123e4567-e89b-12d3-a456-426655440030'),
    ('123e4567-e89b-12d3-a456-426655440044', '123e4567-e89b-12d3-a456-426655440006', 'Certamente será um concerto fabuloso!', '123e4567-e89b-12d3-a456-426655440031'),
    ('123e4567-e89b-12d3-a456-426655440045', '123e4567-e89b-12d3-a456-426655440007', 'Só quero que chegue este dia!', '123e4567-e89b-12d3-a456-426655440032'),
    ('123e4567-e89b-12d3-a456-426655440046', '123e4567-e89b-12d3-a456-426655440008', 'Vai ser um concerto incrível!', '123e4567-e89b-12d3-a456-426655440033'),
    ('123e4567-e89b-12d3-a456-426655440047', '123e4567-e89b-12d3-a456-426655440009', 'Mal posso esperar!', '123e4567-e89b-12d3-a456-426655440034'),
    ('123e4567-e89b-12d3-a456-426655440048', '123e4567-e89b-12d3-a456-426655440010', 'Certamente será um concerto fabuloso!', '123e4567-e89b-12d3-a456-426655440035'),
    ('123e4567-e89b-12d3-a456-426655440049', '123e4567-e89b-12d3-a456-426655440011', 'Só quero que chegue este dia!', '123e4567-e89b-12d3-a456-426655440036');

INSERT INTO VotoComentario (idComentario, idUtilizador, isUp) VALUES
    ('123e4567-e89b-12d3-a456-426655440042', '123e4567-e89b-12d3-a456-426655440012', TRUE),
    ('123e4567-e89b-12d3-a456-426655440043', '123e4567-e89b-12d3-a456-426655440013', TRUE),
    ('123e4567-e89b-12d3-a456-426655440044', '123e4567-e89b-12d3-a456-426655440014', TRUE),
    ('123e4567-e89b-12d3-a456-426655440045', '123e4567-e89b-12d3-a456-426655440015', TRUE),
    ('123e4567-e89b-12d3-a456-426655440046', '123e4567-e89b-12d3-a456-426655440016', TRUE),
    ('123e4567-e89b-12d3-a456-426655440047', '123e4567-e89b-12d3-a456-426655440017', TRUE),
    ('123e4567-e89b-12d3-a456-426655440048', '123e4567-e89b-12d3-a456-426655440018', TRUE),
    ('123e4567-e89b-12d3-a456-426655440049', '123e4567-e89b-12d3-a456-426655440019', TRUE),
    ('123e4567-e89b-12d3-a456-426655440042', '123e4567-e89b-12d3-a456-426655440020', FALSE);

INSERT INTO MotivoDenunciaEvento (idMotivo, texto) VALUES
    ('123e4567-e89b-12d3-a456-426655440050', 'Suspeita de fraude ou golpe'),
    ('123e4567-e89b-12d3-a456-426655440051', 'Conteúdo inadequado ou ofensivo'),
    ('123e4567-e89b-12d3-a456-426655440052', 'Informações incorretas sobre o evento');

INSERT INTO MotivoDenunciaComentario (idMotivo, texto) VALUES
    ('123e4567-e89b-12d3-a456-426655440053', 'Conteúdo inadequado ou não apropriado'),
    ('123e4567-e89b-12d3-a456-426655440054', 'Ameaças ou incitação à violência'),
    ('123e4567-e89b-12d3-a456-426655440055', 'Informações incorretas ou enganosas'),
    ('123e4567-e89b-12d3-a456-426655440056', 'Assédio ou bullying'),
    ('123e4567-e89b-12d3-a456-426655440057', 'Conteúdo comercial ou spam');

INSERT INTO DenunciaEvento (idEvento, idMotivoDenunciaEvento) VALUES
    ('123e4567-e89b-12d3-a456-426655440029', '123e4567-e89b-12d3-a456-426655440050'),
    ('123e4567-e89b-12d3-a456-426655440030', '123e4567-e89b-12d3-a456-426655440051'),
    ('123e4567-e89b-12d3-a456-426655440031', '123e4567-e89b-12d3-a456-426655440052');

INSERT INTO DenunciaComentario (idComentario, idMotivoDenunciaComentario) VALUES
    ('123e4567-e89b-12d3-a456-426655440042', '123e4567-e89b-12d3-a456-426655440053'),
    ('123e4567-e89b-12d3-a456-426655440043', '123e4567-e89b-12d3-a456-426655440054'),
    ('123e4567-e89b-12d3-a456-426655440046', '123e4567-e89b-12d3-a456-426655440057');
