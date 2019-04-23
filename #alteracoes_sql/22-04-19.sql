USE `igsis`;

ALTER TABLE `ig_evento` ADD `numero_apresentacao` INT NOT NULL DEFAULT '0' AFTER `statusEvento`, ADD `espaco_publico` TINYINT(1) NOT NULL DEFAULT '0' AFTER `numero_apresentacao`;

/*
--------------------------------------------------------------------------------------------------------------------
22/04/2019 -> Lorelei
--------------------------------------------------------------------------------------------------------------------
*/
CREATE TABLE `igsis_subprefeitura` (`id` tinyint(2) NOT NULL, `subprefeitura` varchar(30) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `igsis_subprefeitura` (`id`, `subprefeitura`) VALUES
(1, 'Aricanduva/Vila Formosa'),
(2, 'Butantã'),
(3, 'Campo Limpo'),
(4, 'Capela do Socorro'),
(5, 'Casa Verde / Cachoeirinha'),
(6, 'Cidade Ademar'),
(7, 'Cidade Tiradentes'),
(8, 'Ermelino Matarazzo'),
(9, 'Freguesia do Ó / Brasilândia'),
(10, 'Guaianases'),
(11, 'Ipiranga'),
(12, 'Itaim Paulista'),
(13, 'Itaquera'),
(14, 'Jabaquara'),
(15, 'Jaçanã/Tremembé'),
(16, 'Lapa'),
(17, 'M\'Boi Mirim'),
(18, 'Mooca'),
(19, 'Parelheiros'),
(20, 'Penha'),
(21, 'Perus'),
(22, 'Pinheiros'),
(23, 'Pirituba/Jaraguá'),
(24, 'Santana/Tucuruvi'),
(25, 'Santo Amaro'),
(26, 'São Mateus'),
(27, 'São Miguel Paulista'),
(28, 'Sapopemba'),
(29, 'Sé'),
(30, 'Vila Maria / Vila Guilherme'),
(31, 'Vila Mariana'),
(32, 'Vila Prudente');

ALTER TABLE `igsis_subprefeitura`
   ADD PRIMARY KEY (`id`);

ALTER TABLE `igsis_subprefeitura`
   MODIFY `id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

ALTER TABLE `ig_ocorrencia` ADD `idSubprefeitura` TINYINT(2) NULL AFTER `observacao`;


CREATE TABLE `igsis`.`ig_periodo_dia` (`id` INT NOT NULL AUTO_INCREMENT COMMENT '', `periodo` VARCHAR(25) NOT NULL COMMENT '', PRIMARY KEY (`id`)  COMMENT '');
INSERT INTO `ig_periodo_dia` (`id`, `periodo`) VALUES (NULL, 'Manhã (06h às 12h)'), (NULL, 'Tarde (12h às 18h)'), (NULL, 'Noite (18h às 00h)'), (NULL, 'Madrugada (00h às 06h)');

ALTER TABLE `ig_ocorrencia` ADD `idPeriodoDia` TINYINT(1) NULL AFTER `idSubprefeitura`;

/*
--------------------------------------------------------------------------------------------------------------------
 ULTIMAS ALTERAÇÕES
-------------------------------------------------------------------------------------------------------------------- 
*/

CREATE TABLE `igsis`.`fomento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fomento` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`));
  
  ALTER TABLE `igsis`.`ig_evento` 
ADD COLUMN `fomento` TINYINT(1) NOT NULL DEFAULT 0 AFTER `espaco_publico`,
ADD COLUMN `tipo_fomento` INT NOT NULL DEFAULT 0 AFTER `fomento`;

INSERT INTO fomento (fomento) VALUES 
('Fomento à Dança'),
('Fomento ao Teatro'),
('Fomento às Rádio Comunitárias'),
('Apoio ao Raggae'),
('Apoio à Fotografia'),
('Apoio à Música'),
('Digitalização de Acervo'),
('Apoio ao Circo'),
('Museu de Arte de Rua - Apoio à Arte de Rua'),
('Fomento aos Livros: Autores não estreantes'),
('Fomento aos Livros: Autores estreantes'),
('Fomento à Cultura da Periferia'),
('Programa VAI'),
('Programa Cultura Viva Municipal');

/*
--------------------------------------------------------------------------------------------------------------------
23/04/2019 -> Tanair
--------------------------------------------------------------------------------------------------------------------
*/
ALTER TABLE `igsis`.`ig_local`
    ADD COLUMN `logradouro` VARCHAR(255) NULL AFTER `rua`,
    ADD COLUMN `numero` INT(5) NULL AFTER `logradouro`,
    ADD COLUMN `complemento` VARCHAR(20) NULL AFTER `numero`,
    ADD COLUMN `bairro` VARCHAR(80) NULL AFTER `complemento`;