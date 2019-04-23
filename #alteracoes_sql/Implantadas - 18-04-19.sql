USE `igsis`;

CREATE TABLE `igsis_linguagem` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `linguagem` VARCHAR(42) NOT NULL,
   `descricao` VARCHAR(200) NOT NULL,
   `publicado` TINYINT(1) NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`)
)
COMMENT='Contém as linguagens / ações que devem ser usadas no agendão'
COLLATE='utf8_general_ci';

INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (1, 'Audiovisual e cinema', 'Formas de comunicação que combinam som e imagem, como publicidade, videoclipe, filme, documentário, série, multimídia, games e outros', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (2, 'Circo', 'Coletivo que reúne artistas de diferentes especialidades, como malabarismo, palhaço, acrobacia, monociclo, contorcionismo, equilibrismo, ilusionismo, entre outros', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (3, 'Dança', 'Engloba todos os gêneros da dança, como contemporânea, balé clássico, jongo, break, danças africanas, indígena e outros', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (4, 'Debates / Rodas de Conversa', 'Atividades voltadas para a troca de informação e ideias, com foco na discussão e problematização de diversos assuntos da atualidade, de curta duração', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (5, 'Feiras', 'Engloba a exposição e venda de trabalhos e produtos artístico-culturais, identitários, inovadores e/ou com alto grau de inserção social', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (6, 'Literatura', 'Conjunto de obras literárias de reconhecido valor estético da linguagem escrita; arte literária; saraus, contação de história', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (7, 'Música', 'Engloba todos os gêneros musicais, da musica clássica e erudita  aos gêneros vinculados aos grandes movimentos socioterritoriais, como o hip  hop, o dancehall, o funk, o tecnobrega e outros', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (8, 'Oficinas e Formação Cultural', 'Engloba as atividades de formação e educação, como oficinas,  formações culturais; workshops; ateliês; palestras; aulas públicas', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (9, 'Patrimônio Cultural, Museu e Memória', 'Patrimônio material e imaterial, gastronomia, memória, identidade coletiva', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (10, 'Intervenção e Vivência Artístico Cultural', 'Experimentação e intervenção artística, performance, fundindo  teatro, o cinema, a dança, a poesia, a música e as artes plásticas', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (11, 'Teatro', 'Um ator ou conjunto de atores que interpretam uma história ou atividades para o público, stand-up', 1);
INSERT INTO `igsis_linguagem` (`id`, `linguagem`, `descricao`, `publicado`) VALUES (12, 'Visual', 'Desenho, pintura, colagem, fotografia, gravura, grafite, história em quadrinhos, arte digital', 1);

CREATE TABLE `igsis_representatividade` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `representatividade_social` VARCHAR(27) NOT NULL,
   `descricao` TEXT NOT NULL,
   `publicado` TINYINT(1) NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`)
)
COMMENT='Contém o publico destinado que devem ser usadas no agendão'
COLLATE='utf8_general_ci';

INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (1, 'Acessibilidade', 'Engloba acessibilidade arquitetônica, comunicacional ou atitudinal', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (2, 'Consciência Negra', 'Ações que lutem pela visibilidade, representatividade, igualdade no acesso e a direitos, contra todas as formas de preconceito, valorizando a inclusão da população afrodescendente no processo de desenvolvimento social, econômico, político e cultural da cidade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (3, 'Consciência Indígena', 'Ações que lutem pela visibilidade, representatividade, igualdade no acesso e a direitos, contra todas as formas de preconceito, valorizando a inclusão de indígenas e seus descendentes no processo de desenvolvimento social, econômico, político e cultural da cidade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (4, 'Imigrantes', 'Ações que lutem pela visibilidade, representatividade, igualdade no acesso e a direitos, contra todas as formas de preconceito, valorizando a inclusão da população imigrante no processo de desenvolvimento social, econômico, político e cultural da cidade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (5, 'Mulheres', 'Ações que lutem pela visibilidade, representatividade, igualdade no acesso e a direitos, contra todas as formas de preconceito, valorizando a inclusão da mulher no processo de desenvolvimento social, econômico, político e cultural da cidade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (6, 'LGBTQ+', 'Ações que lutem pela visibilidade, representatividade, igualdade no acesso e a direitos, contra todas as formas de preconceito, valorizando a inclusão lésbicas, gays, bissexuais, transexuais, queer e outras formas não binárias de existência no processo de desenvolvimento social, econômico, político e cultural da cidade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (7, 'Terceira idade', 'Ações que lutem pela visibilidade, representatividade, igualdade no acesso e a direitos, contra todas as formas de preconceito, valorizando a inclusão dessa população no processo de desenvolvimento social, econômico, político e cultural da cidade idosa, isto é, com mais de 64 anos de idade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (8, 'Primeira infância', 'Engloba atividades artístico-culturais direcionadas para a população com menos de 3 anos de idade', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (9, 'Criança', 'Engloba atividades artístico-culturais direcionadas para a população com idade inferior a doze anos e maior ou igual a seis anos', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (10, 'Adolescente', 'Engloba atividades artístico-culturais direcionadas para a população com idade inferior a dezoito anos e maior que 12 anos', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (11, 'Adulto', 'Engloba atividades artístico-culturais direcionadas para a população com idade maior que dezoito anos', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (12, 'Alternativo / colaborativo', 'Engloba as ações populares, com ou sem fomento público, amadoras ou não, com alto grau de experimentação, de liberdade político-partidária e coletivismo associativo', 1);
INSERT INTO `igsis_representatividade` (`id`, `representatividade_social`, `descricao`, `publicado`) VALUES (13, 'Vulnerabilidade social', 'Atividades artístico-culturais que são desenvolvidas por grupos sociais de vulnerabilidade social ou em territórios da cidade com baixos índices de desenvolvimento socioeconômico e cultural', 1);

CREATE TABLE `igsis_evento_linguagem` (
   `idEvento` INT NOT NULL,
   `idLinguagem` INT NOT NULL
)
COMMENT='Trata dos relacionamentos entre as tabelas igsis_linguagem e ig_evento'
COLLATE='utf8_general_ci';

CREATE TABLE `igsis_evento_representatividade` (
   `idEvento` INT NOT NULL,
   `idRepresentatividade` INT NOT NULL
)
COMMENT='Trata dos relacionamentos entre as tabelas igsis_representatividade e ig_evento'
COLLATE='utf8_general_ci';

INSERT INTO `ig_modulo` (`idModulo`, `nome`, `pag`, `descricao`) VALUES ('30', 'Agendão', 'agendao', 'Cadastra e exporta dados de eventos para o site Agendão.');

ALTER TABLE `ig_papelusuario` ADD `agendao` TINYINT(1) NULL DEFAULT NULL AFTER `emia`;

UPDATE `ig_papelusuario` SET `agendao` = '1' WHERE `ig_papelusuario`.`idPapelUsuario` = 1;