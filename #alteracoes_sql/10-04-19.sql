USE `igsis`;

CREATE TABLE `igsis_linguagem` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `linguagem` VARCHAR(30) NOT NULL,
   PRIMARY KEY (`id`)
)
COMMENT='Contém as linguagens que devem ser usadas no agendão'
COLLATE='utf8_general_ci';

INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (1, 'Audiovisual e Cinema');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (2, 'Circo');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (3, 'Dança');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (4, 'Feiras');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (5, 'Literatura');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (6, 'Música');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (7, 'Oficinas e Formação Cultural');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (8, 'Patrimônio Cultural e Memória');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (9, 'Performance');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (10, 'Teatro');
INSERT INTO `igsis_linguagem` (`id`, `linguagem`) VALUES (11, 'Visual');

CREATE TABLE `igsis_representatividade` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `representatividade_social` VARCHAR(27) NOT NULL,
   PRIMARY KEY (`id`)
)
COMMENT='Contém o publico destinado que devem ser usadas no agendão'
COLLATE='utf8_general_ci';

INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (1, 'Acessibilidade');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (2, 'Alternativo / Colaborativo');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (3, 'Consciência Étnico-Racial');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (4, 'Criança e Adolescente');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (5, 'LGBTQIA+');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (6, 'Primeira Infância');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (7, 'Público em Geral');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`) VALUES (8, 'Terceira Idade');
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`)VALUES (9, 'Vulnerabilidade Social');

CREATE TABLE `igsis_evento_linguagem` (
   `idLinguagem` INT NOT NULL,
   `idEvento` INT NOT NULL
)
COMMENT='Trata dos relacionamentos entre as tabelas igsis_linguagem e ig_evento'
COLLATE='utf8_general_ci';

CREATE TABLE `igsis_evento_representatividade` (
   `idRepresentatividade` INT NOT NULL,
   `idEvento` INT NOT NULL
)
COMMENT='Trata dos relacionamentos entre as tabelas igsis_representatividade e ig_evento'
COLLATE='utf8_general_ci';

INSERT INTO `ig_modulo` (`idModulo`, `nome`, `pag`, `descricao`) VALUES ('30', 'Agendão', 'agendao', 'Cadastra e exporta dados de eventos para o site Agendão.');

ALTER TABLE `ig_papelusuario` ADD `agendao` TINYINT(1) NULL DEFAULT NULL AFTER `emia`;

UPDATE `ig_papelusuario` SET `agendao` = '1' WHERE `ig_papelusuario`.`idPapelUsuario` = 1;