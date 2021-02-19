SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `attends` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) unsigned NOT NULL COMMENT 'Id da unidade que o usuário cursa',
  `user_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário',
  `status` char(1) NOT NULL DEFAULT 'M' COMMENT '''M''=matriculado; ''D''=desistente; ''R''=remanejado; ''T''=transferido',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_id_user_id` (`unit_id`,`user_id`),
  KEY `disciplina` (`unit_id`),
  KEY `aluno` (`user_id`),
  CONSTRAINT `attends_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `attends_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `attests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) unsigned NOT NULL,
  `student_id` int(11) unsigned NOT NULL,
  `date` date NOT NULL,
  `days` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idInstitution` (`institution_id`),
  KEY `idStudent` (`student_id`),
  CONSTRAINT `attests_ibfk_3` FOREIGN KEY (`institution_id`) REFERENCES `users` (`id`),
  CONSTRAINT `attests_ibfk_4` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  CONSTRAINT `attests_ibfk_5` FOREIGN KEY (`institution_id`) REFERENCES `users` (`id`),
  CONSTRAINT `attests_ibfk_6` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `binds` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `discipline_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_discipline_id` (`user_id`,`discipline_id`),
  KEY `idUser` (`user_id`),
  KEY `idDiscipline` (`discipline_id`),
  CONSTRAINT `binds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `binds_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
  CONSTRAINT `binds_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `binds_ibfk_4` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL COMMENT 'Nome da cidade',
  `state_id` int(11) unsigned NOT NULL COMMENT 'Id do estado ao que a cidade pertence',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cidade_estado` (`state_id`),
  CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  CONSTRAINT `cities_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` int(11) unsigned NOT NULL COMMENT 'periodo da turma',
  `name` varchar(50) DEFAULT NULL,
  `school_year` int(11) NOT NULL COMMENT 'Ano letivo',
  `class` varchar(50) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idPeriod` (`period_id`),
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`),
  CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `controllers` (
  `controller_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário controlador',
  `subject_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário controlado',
  `type` char(2) NOT NULL COMMENT 'Usuários (ex.: instituição) podem cadastrar usuários (ex.: professor);''IP'' = Instituição controla Professor; ...',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subject_id`,`controller_id`),
  KEY `idController` (`controller_id`),
  CONSTRAINT `controllers_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `users` (`id`),
  CONSTRAINT `controllers_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `countries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT 'Nome do país',
  `short` varchar(5) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) unsigned NOT NULL COMMENT 'Id da instituição à que o curso está relacionado',
  `name` varchar(100) NOT NULL COMMENT 'Nome do curso',
  `type` varchar(100) DEFAULT NULL COMMENT 'dados do csv',
  `modality` varchar(100) DEFAULT NULL COMMENT 'dados do csv',
  `quant_unit` int(10) DEFAULT NULL COMMENT 'Total de unidades do curso',
  `absent_percent` float unsigned DEFAULT NULL,
  `average` float unsigned DEFAULT NULL,
  `average_final` float unsigned DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT 'E - enable, D - disable',
  `curricular_profile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idInstitution` (`institution_id`),
  CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`institution_id`) REFERENCES `users` (`id`),
  CONSTRAINT `courses_ibfk_3` FOREIGN KEY (`institution_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `descriptive_exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attend_id` int(11) unsigned NOT NULL,
  `exam_id` int(11) unsigned NOT NULL,
  `description` text,
  `approved` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attend_id_exam_id` (`attend_id`,`exam_id`),
  KEY `idAttend` (`attend_id`),
  KEY `idExam` (`exam_id`),
  CONSTRAINT `descriptive_exams_ibfk_3` FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`) ON DELETE CASCADE,
  CONSTRAINT `descriptive_exams_ibfk_4` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `descriptive_exams_ibfk_5` FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`),
  CONSTRAINT `descriptive_exams_ibfk_6` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `disciplines` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `period_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'Nome da disciplina',
  `ementa` text COMMENT 'ementa da disciplina',
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT '''E'' = Enabled; ''D'' = Disabled; ''F'' = Finalized;',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Disciplines_Periods1_idx` (`period_id`),
  CONSTRAINT `disciplines_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`),
  CONSTRAINT `disciplines_ibfk_2` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL COMMENT 'Tipo de avaliação''E'' = "exams";''L'' = "list";''P'' = "projects";...',
  `aval` char(1) NOT NULL COMMENT '''A'': Avaliação, ''R'': Recuperação da Unidade',
  `weight` varchar(5) NOT NULL DEFAULT '1' COMMENT 'Peso',
  `date` date NOT NULL,
  `comments` varchar(1023) DEFAULT NULL COMMENT 'comentários',
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUnit` (`unit_id`),
  CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exams_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `exams_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attend_id` int(11) unsigned NOT NULL COMMENT 'Id do relacionamento "Cursa"',
  `exam_id` int(11) unsigned NOT NULL COMMENT 'Título da avaliação',
  `value` varchar(5) DEFAULT NULL COMMENT 'Valor da avaliação (nota máxima)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attend_id_exam_id` (`attend_id`,`exam_id`),
  KEY `exam_id` (`exam_id`),
  KEY `attend_id` (`attend_id`),
  CONSTRAINT `exams_values_ibfk_4` FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exams_values_ibfk_5` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exams_values_ibfk_6` FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`),
  CONSTRAINT `exams_values_ibfk_7` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `final_exams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` int(10) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `value` varchar(5) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offer_id_user_id` (`offer_id`,`user_id`),
  KEY `idOffer` (`offer_id`),
  KEY `idAttend` (`user_id`),
  CONSTRAINT `final_exams_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
  CONSTRAINT `final_exams_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `final_exams_ibfk_3` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
  CONSTRAINT `final_exams_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `frequencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attend_id` int(11) unsigned NOT NULL COMMENT 'Id do relacionamento "cursa"',
  `lesson_id` int(11) unsigned NOT NULL COMMENT 'Id da aula',
  `value` char(1) DEFAULT NULL COMMENT '''P'' = Presente;''F'' = Falta;',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attend_id_lesson_id` (`attend_id`,`lesson_id`),
  KEY `attend_id` (`attend_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `frequencies_ibfk_4` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `frequencies_ibfk_5` FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `frequencies_ibfk_6` FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`),
  CONSTRAINT `frequencies_ibfk_7` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `lectures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT 'Id do professor que leciona a disciplina',
  `offer_id` int(10) unsigned NOT NULL COMMENT 'Id da disciplina lecionada',
  `order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_offer_id` (`user_id`,`offer_id`),
  KEY `user_id` (`user_id`),
  KEY `offer_id` (`offer_id`),
  CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `lectures_ibfk_2` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
  CONSTRAINT `lectures_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `lectures_ibfk_4` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `lessons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) unsigned NOT NULL COMMENT 'Id da unidade à que a aula está relacionada',
  `date` date NOT NULL COMMENT 'Data',
  `title` varchar(255) NOT NULL COMMENT 'Título da aula',
  `description` text COMMENT 'summary/abstract, breve descrição da aula ',
  `goals` text COMMENT 'Objetivos',
  `content` text COMMENT 'Conteúdo',
  `methodology` text COMMENT 'Metodologia de classe',
  `resources` text COMMENT 'Recursos necessários',
  `keyworks` varchar(255) DEFAULT NULL COMMENT 'palavras-chave',
  `estimated_time` int(11) DEFAULT NULL COMMENT 'Tempo estimado de uma aula',
  `bibliography` text COMMENT 'Bibliografia',
  `valuation` varchar(255) DEFAULT NULL COMMENT 'Método de avaliação (prova, trabalho, lista...)',
  `notes` text COMMENT 'Anotações',
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUnit` (`unit_id`),
  CONSTRAINT `lessons_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lessons_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(15) COLLATE utf8_bin NOT NULL,
  `msg` varchar(100) COLLATE utf8_bin NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) unsigned DEFAULT NULL COMMENT 'Auto relacionamento master/slave',
  `class_id` int(11) unsigned NOT NULL,
  `discipline_id` int(11) unsigned NOT NULL,
  `classroom` varchar(40) DEFAULT NULL,
  `day_period` varchar(50) DEFAULT NULL COMMENT 'dados do csv',
  `maxlessons` smallint(5) unsigned NOT NULL DEFAULT '180',
  `type_final` char(2) DEFAULT NULL,
  `date_final` date DEFAULT NULL,
  `comments` text,
  `status` char(1) NOT NULL DEFAULT 'E',
  `grouping` char(1) NOT NULL DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idDiscipline` (`discipline_id`),
  KEY `idClass` (`class_id`),
  CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
  CONSTRAINT `offers_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `offers_ibfk_4` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `offers_ibfk_5` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `periods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Periods_Courses1_idx` (`course_id`),
  CONSTRAINT `periods_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `periods_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  CONSTRAINT `periods_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `relationships` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário',
  `friend_id` int(11) unsigned NOT NULL COMMENT 'Id do amigo do usuário',
  `enrollment` varchar(50) DEFAULT NULL COMMENT 'Matricula',
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT 'Relacionamento ativo ou inativo',
  `type` char(1) DEFAULT NULL COMMENT 'F-friends;P-parents;S-subscribe; 1-instituição->aluno; 2-instituição->professor; 3-professor->aluno',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_friend_id` (`user_id`,`friend_id`),
  KEY `friend_id` (`friend_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `relationships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `relationships_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `states` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `short` varchar(5) DEFAULT NULL,
  `country_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_estado_pais` (`country_id`),
  CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `states_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `suggestions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `value` char(1) NOT NULL DEFAULT 'S' COMMENT 'S-sugestões; B-bugs',
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`user_id`),
  CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `suggestions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `units` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` int(10) unsigned NOT NULL,
  `value` int(11) NOT NULL DEFAULT '1' COMMENT '''1'' = primeira unidade;''2'' = segunda unidade;...',
  `calculation` char(1) CHARACTER SET utf8 DEFAULT 'A' COMMENT 'Tipo de cálculo para média( ''S'' = sum; ''A'' = avarage, ''W''=média ponderada)',
  `status` char(1) NOT NULL DEFAULT 'E' COMMENT '"E" => "Enable", "D" => "Disable"',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Units_Classes1_idx` (`offer_id`),
  CONSTRAINT `units_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
  CONSTRAINT `units_ibfk_2` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL COMMENT 'Email do usuário',
  `password` varchar(60) DEFAULT NULL COMMENT 'senha',
  `remember_token` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT 'nome do usuário',
  `type` char(1) DEFAULT 'P' COMMENT '''P'' = Professor; ''A'' = Aluno; ''N'' = aluno não cadastrado; ''M''=professor não cadastrado',
  `gender` char(1) DEFAULT NULL COMMENT '''F'' = feminino; ''M'' = masculino',
  `birthdate` date DEFAULT NULL COMMENT 'Data de nascimento  (YYYY-MM-DD)',
  `institution` varchar(50) DEFAULT NULL COMMENT 'Nome da Instituição de ensino',
  `uee` varchar(20) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL COMMENT 'Cursos realizados pelo usuário (formação)',
  `formation` char(1) NOT NULL DEFAULT '0' COMMENT 'Nível de formação acadêmica(Graduated, Master, PhD...)',
  `cadastre` char(1) DEFAULT 'N' COMMENT 'T=Temporário, W=aguardando,N=Normal,G=Google,F=Facebook',
  `city_id` int(11) unsigned DEFAULT NULL COMMENT 'Id da cidade residente',
  `street` varchar(100) DEFAULT NULL,
  `photo` varchar(255) NOT NULL DEFAULT '/images/user-photo-default.jpg' COMMENT 'foto de perfil',
  `enrollment` varchar(50) DEFAULT NULL COMMENT 'matricula ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCity` (`city_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SET foreign_key_checks = 1;
