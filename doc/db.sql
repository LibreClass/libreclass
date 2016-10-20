-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Attends`;
CREATE TABLE `Attends` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idUnit` int(11) unsigned NOT NULL COMMENT 'Id da unidade que o usuário cursa',
  `idUser` int(11) unsigned NOT NULL COMMENT 'Id do usuário',
  `status` char(1) NOT NULL DEFAULT 'M' COMMENT '''M''=matriculado; ''D''=desistente; ''R''=remanejado; ''T''=transferido',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplina` (`idUnit`),
  KEY `aluno` (`idUser`),
  CONSTRAINT `Attends_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `Users` (`id`),
  CONSTRAINT `Attends_ibfk_3` FOREIGN KEY (`idUnit`) REFERENCES `Units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Attests`;
CREATE TABLE `Attests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idInstitution` int(11) unsigned NOT NULL,
  `idStudent` int(11) unsigned NOT NULL,
  `date` date NOT NULL,
  `days` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idInstitution` (`idInstitution`),
  KEY `idStudent` (`idStudent`),
  CONSTRAINT `Attests_ibfk_3` FOREIGN KEY (`idInstitution`) REFERENCES `Users` (`id`),
  CONSTRAINT `Attests_ibfk_4` FOREIGN KEY (`idStudent`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Binds`;
CREATE TABLE `Binds` (
  `idUser` int(11) unsigned NOT NULL,
  `idDiscipline` int(11) unsigned NOT NULL,
  KEY `idUser` (`idUser`),
  KEY `idDiscipline` (`idDiscipline`),
  CONSTRAINT `Binds_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `Users` (`id`),
  CONSTRAINT `Binds_ibfk_2` FOREIGN KEY (`idDiscipline`) REFERENCES `Disciplines` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Cities`;
CREATE TABLE `Cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL COMMENT 'Nome da cidade',
  `idState` int(11) unsigned NOT NULL COMMENT 'Id do estado ao que a cidade pertence',
  PRIMARY KEY (`id`),
  KEY `fk_cidade_estado` (`idState`),
  CONSTRAINT `Cities_ibfk_1` FOREIGN KEY (`idState`) REFERENCES `States` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Classes`;
CREATE TABLE `Classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idPeriod` int(11) unsigned NOT NULL COMMENT 'periodo da turma',
  `name` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idPeriod` (`idPeriod`),
  CONSTRAINT `Classes_ibfk_1` FOREIGN KEY (`idPeriod`) REFERENCES `Periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Controllers`;
CREATE TABLE `Controllers` (
  `idController` int(11) unsigned NOT NULL COMMENT 'Id do usuário controlador',
  `idSubject` int(11) unsigned NOT NULL COMMENT 'Id do usuário controlado',
  `type` char(2) NOT NULL COMMENT 'Usuários (ex.: instituição) podem cadastrar usuários (ex.: professor);\n''IP'' = Instituição controla Professor; ...',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idSubject`,`idController`),
  KEY `idController` (`idController`),
  CONSTRAINT `Controllers_ibfk_1` FOREIGN KEY (`idController`) REFERENCES `Users` (`id`),
  CONSTRAINT `Controllers_ibfk_2` FOREIGN KEY (`idSubject`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Countries`;
CREATE TABLE `Countries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT 'Nome do país',
  `short` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Courses`;
CREATE TABLE `Courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idInstitution` int(10) unsigned NOT NULL COMMENT 'Id da instituição à que o curso está relacionado',
  `name` varchar(100) NOT NULL COMMENT 'Nome do curso',
  `type` varchar(100) DEFAULT NULL COMMENT 'dados do csv',
  `modality` varchar(100) DEFAULT NULL COMMENT 'dados do csv',
  `absentPercent` float unsigned DEFAULT NULL,
  `average` float unsigned DEFAULT NULL,
  `averageFinal` float unsigned DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT 'E - enable, D - disable',
  `curricularProfile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idInstitution` (`idInstitution`),
  CONSTRAINT `Courses_ibfk_2` FOREIGN KEY (`idInstitution`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Disciplines`;
CREATE TABLE `Disciplines` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idPeriod` int(11) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'Nome da disciplina',
  `ementa` text COMMENT 'ementa da disciplina',
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT '''E'' = Enabled; ''D'' = Disabled; ''F'' = Finalized;',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Disciplines_Periods1_idx` (`idPeriod`),
  CONSTRAINT `Disciplines_ibfk_1` FOREIGN KEY (`idPeriod`) REFERENCES `Periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Exams`;
CREATE TABLE `Exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idUnit` int(11) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` char(1) NOT NULL COMMENT 'Tipo de avaliação\n''E'' = "exams";\n''L'' = "list";\n''P'' = "projects";\n...',
  `aval` char(1) NOT NULL COMMENT '''A'': Avaliação, ''R'': Recuperação da Unidade',
  `weight` varchar(5) NOT NULL DEFAULT '1' COMMENT 'Peso',
  `date` date NOT NULL,
  `comments` varchar(255) DEFAULT NULL COMMENT 'comentários',
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUnit` (`idUnit`),
  CONSTRAINT `Exams_ibfk_2` FOREIGN KEY (`idUnit`) REFERENCES `Units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ExamsValues`;
CREATE TABLE `ExamsValues` (
  `idAttend` int(11) unsigned NOT NULL COMMENT 'Id do relacionamento "Cursa"',
  `idExam` int(11) unsigned NOT NULL COMMENT 'Título da avaliação',
  `value` varchar(5) DEFAULT NULL COMMENT 'Valor da avaliação (nota máxima)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idAttend`,`idExam`),
  KEY `idTitle` (`idExam`),
  CONSTRAINT `ExamsValues_ibfk_4` FOREIGN KEY (`idAttend`) REFERENCES `Attends` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ExamsValues_ibfk_5` FOREIGN KEY (`idExam`) REFERENCES `Exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `FinalExams`;
CREATE TABLE `FinalExams` (
  `idOffer` int(11) unsigned NOT NULL,
  `idUser` int(11) unsigned NOT NULL,
  `value` varchar(5) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `idOffer` (`idOffer`),
  KEY `idAttend` (`idUser`),
  CONSTRAINT `FinalExams_ibfk_1` FOREIGN KEY (`idOffer`) REFERENCES `Offers` (`id`),
  CONSTRAINT `FinalExams_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Frequencies`;
CREATE TABLE `Frequencies` (
  `idAttend` int(11) unsigned NOT NULL COMMENT 'Id do relacionamento "cursa"',
  `idLesson` int(11) unsigned NOT NULL COMMENT 'Id da aula',
  `value` char(1) DEFAULT NULL COMMENT '''P'' = Presente;\n''F'' = Falta;',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idAttend`,`idLesson`),
  KEY `idLesson` (`idLesson`),
  CONSTRAINT `Frequencies_ibfk_4` FOREIGN KEY (`idLesson`) REFERENCES `Lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Frequencies_ibfk_5` FOREIGN KEY (`idAttend`) REFERENCES `Attends` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Lectures`;
CREATE TABLE `Lectures` (
  `idUser` int(11) unsigned NOT NULL COMMENT 'Id do professor que leciona a disciplina',
  `idOffer` int(10) unsigned NOT NULL COMMENT 'Id da disciplina lecionada',
  `order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idUser`,`idOffer`),
  KEY `idDiscipline` (`idOffer`),
  CONSTRAINT `Lectures_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `Users` (`id`),
  CONSTRAINT `Lectures_ibfk_2` FOREIGN KEY (`idOffer`) REFERENCES `Offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Lessons`;
CREATE TABLE `Lessons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idUnit` int(11) unsigned NOT NULL COMMENT 'Id da unidade à que a aula está relacionada',
  `date` date NOT NULL COMMENT 'Data',
  `title` varchar(255) NOT NULL COMMENT 'Título da aula',
  `description` text NOT NULL COMMENT 'summary/abstract, breve descrição da aula ',
  `goals` text NOT NULL COMMENT 'Objetivos',
  `content` text NOT NULL COMMENT 'Conteúdo',
  `methodology` text NOT NULL COMMENT 'Metodologia de classe',
  `resources` text NOT NULL COMMENT 'Recursos necessários',
  `keyworks` varchar(255) NOT NULL COMMENT 'palavras-chave',
  `estimatedTime` int(11) NOT NULL COMMENT 'Tempo estimado de uma aula',
  `bibliography` text NOT NULL COMMENT 'Bibliografia',
  `valuation` varchar(255) NOT NULL COMMENT 'Método de avaliação (prova, trabalho, lista...)',
  `notes` text NOT NULL COMMENT 'Anotações',
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUnit` (`idUnit`),
  CONSTRAINT `Lessons_ibfk_2` FOREIGN KEY (`idUnit`) REFERENCES `Units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Logs`;
CREATE TABLE `Logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(15) COLLATE utf8_bin NOT NULL,
  `msg` varchar(100) COLLATE utf8_bin NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `Offers`;
CREATE TABLE `Offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idClass` int(11) unsigned NOT NULL,
  `idDiscipline` int(11) unsigned NOT NULL,
  `classroom` varchar(40) DEFAULT NULL,
  `day_period` varchar(50) DEFAULT NULL COMMENT 'dados do csv',
  `maxlessons` smallint(5) unsigned NOT NULL DEFAULT '180',
  `typeFinal` char(1) NOT NULL,
  `dateFinal` date NOT NULL,
  `comments` text,
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idDiscipline` (`idDiscipline`),
  KEY `idClass` (`idClass`),
  CONSTRAINT `Offers_ibfk_2` FOREIGN KEY (`idDiscipline`) REFERENCES `Disciplines` (`id`),
  CONSTRAINT `Offers_ibfk_3` FOREIGN KEY (`idClass`) REFERENCES `Classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Periods`;
CREATE TABLE `Periods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idCourse` int(11) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` int(11) NOT NULL COMMENT '''1'' = primeiro período/série;''2'' = segundo período/série;...',
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Periods_Courses1_idx` (`idCourse`),
  CONSTRAINT `Periods_ibfk_1` FOREIGN KEY (`idCourse`) REFERENCES `Courses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Relationships`;
CREATE TABLE `Relationships` (
  `idUser` int(11) unsigned NOT NULL COMMENT 'Id do usuário',
  `idFriend` int(11) unsigned NOT NULL COMMENT 'Id do amigo do usuário',
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT 'Relacionamento ativo ou inativo',
  `type` char(1) DEFAULT NULL COMMENT 'F-friends;P-parents;S-subscribe; 1-instituição->aluno; 2-instituição->professor; 3-professor->aluno',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idUser`,`idFriend`),
  KEY `idFriend` (`idFriend`),
  CONSTRAINT `Relationships_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `Users` (`id`),
  CONSTRAINT `Relationships_ibfk_2` FOREIGN KEY (`idFriend`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `States`;
CREATE TABLE `States` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `short` varchar(5) DEFAULT NULL,
  `idCountry` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_estado_pais` (`idCountry`),
  CONSTRAINT `States_ibfk_1` FOREIGN KEY (`idCountry`) REFERENCES `Countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Suggestions`;
CREATE TABLE `Suggestions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `value` char(1) NOT NULL DEFAULT 'S' COMMENT 'S-sugestões; B-bugs',
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`),
  CONSTRAINT `Suggestions_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `Users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Units`;
CREATE TABLE `Units` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idOffer` int(10) unsigned NOT NULL,
  `value` int(11) NOT NULL DEFAULT '1' COMMENT '''1'' = primeira unidade;''2'' = segunda unidade;...',
  `calculation` char(1) CHARACTER SET utf8 DEFAULT 'A' COMMENT 'Tipo de cálculo para média( ''S'' = sum; ''A'' = avarage, ''W''=média ponderada)',
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT '"E" => "Enable", "D" => "Disable"',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Units_Classes1_idx` (`idOffer`),
  CONSTRAINT `Units_ibfk_1` FOREIGN KEY (`idOffer`) REFERENCES `Offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL COMMENT 'Email do usuário',
  `password` varchar(60) DEFAULT NULL COMMENT 'senha',
  `name` varchar(100) DEFAULT NULL COMMENT 'nome do usuário',
  `type` char(1) DEFAULT 'P' COMMENT '''P'' = Professor; ''A'' = Aluno; ''N'' = aluno não cadastrado; ''M''=professor não cadastrado',
  `gender` char(1) DEFAULT NULL COMMENT '''F'' = feminino; ''M'' = masculino',
  `birthdate` date DEFAULT NULL COMMENT 'Data de nascimento  (YYYY-MM-DD)',
  `institution` varchar(50) DEFAULT NULL COMMENT 'Nome da Instituição de ensino',
  `uee` varchar(20) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL COMMENT 'Cursos realizados pelo usuário (formação)',
  `formation` char(1) NOT NULL DEFAULT '0' COMMENT 'Nível de formação acadêmica(Graduated, Master, PhD...)',
  `cadastre` char(1) DEFAULT 'N' COMMENT 'T=Temporário, W=aguardando,N=Normal,G=Google,F=Facebook',
  `idCity` int(11) unsigned DEFAULT NULL COMMENT 'Id da cidade residente',
  `street` varchar(100) DEFAULT NULL,
  `photo` varchar(255) NOT NULL DEFAULT '/images/user-photo-default.jpg' COMMENT 'foto de perfil',
  `enrollment` varchar(50) DEFAULT NULL COMMENT 'matricula ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCity` (`idCity`),
  CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`idCity`) REFERENCES `Cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2016-10-20 03:02:53