SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `Users`
CHANGE `idCity` `city_id` int(11) unsigned NULL COMMENT 'Id da cidade residente' AFTER `cadastre`,
RENAME TO `users`;

ALTER TABLE `users`
ADD `remember_token` varchar(100) COLLATE 'utf8_general_ci' NULL AFTER `password`;

ALTER TABLE `users`
ADD `deleted_at` timestamp NULL;

ALTER TABLE `Suggestions`
CHANGE `idUser` `user_id` int(11) unsigned NULL AFTER `id`,
ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
RENAME TO `suggestions`;

ALTER TABLE `Attests`
CHANGE `idInstitution` `institution_id` int(11) unsigned NOT NULL AFTER `id`,
CHANGE `idStudent` `student_id` int(11) unsigned NOT NULL AFTER `institution_id`,
ADD FOREIGN KEY (`institution_id`) REFERENCES `users` (`id`),
ADD FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
RENAME TO `attests`;

ALTER TABLE `Countries`
RENAME TO `countries`;

ALTER TABLE `States`
CHANGE `idCountry` `country_id` int(11) unsigned NOT NULL AFTER `short`,
ADD FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
RENAME TO `states`;

ALTER TABLE `Cities`
CHANGE `idState` `state_id` int(11) unsigned NOT NULL COMMENT 'Id do estado ao que a cidade pertence' AFTER `name`,
ADD FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
RENAME TO `cities`;

ALTER TABLE `Controllers`
CHANGE `idController` `controller_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário controlador' FIRST,
CHANGE `idSubject` `subject_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário controlado' AFTER `controller_id`,
CHANGE `type` `type` char(2) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Usuários (ex.: instituição) podem cadastrar usuários (ex.: professor);\'IP\' = Instituição controla Professor; ...' AFTER `subject_id`,
RENAME TO `controllers`;

ALTER TABLE `Courses`
CHANGE `idInstitution` `institution_id` int(11) unsigned NOT NULL COMMENT 'Id da instituição à que o curso está relacionado' AFTER `id`,
CHANGE `quantUnit` `quant_unit` int(10) NULL COMMENT 'Total de unidades do curso' AFTER `modality`,
CHANGE `absentPercent` `absent_percent` float unsigned NULL AFTER `quant_unit`,
CHANGE `averageFinal` `average_final` float unsigned NULL AFTER `average`,
CHANGE `curricularProfile` `curricular_profile` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `status`,
ADD FOREIGN KEY (`institution_id`) REFERENCES `users` (`id`),
RENAME TO `courses`;

ALTER TABLE `Relationships`
CHANGE `idUser` `user_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário' FIRST,
CHANGE `idFriend` `friend_id` int(11) unsigned NOT NULL COMMENT 'Id do amigo do usuário' AFTER `user_id`,
ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
ADD FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`),
RENAME TO `relationships`;

ALTER TABLE `Periods`
CHANGE `idCourse` `course_id` int(11) unsigned NOT NULL AFTER `id`,
ADD FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
RENAME TO `periods`;

ALTER TABLE `periods`
ADD FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

ALTER TABLE `periods`
DROP `value`;

ALTER TABLE `Disciplines`
CHANGE `idPeriod` `period_id` int(11) unsigned NULL AFTER `id`,
ADD FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`),
RENAME TO `disciplines`;

ALTER TABLE `Classes`
CHANGE `idPeriod` `period_id` int(11) unsigned NOT NULL COMMENT 'periodo da turma' AFTER `id`,
CHANGE `schoolYear` `school_year` int(11) NULL DEFAULT '2018' COMMENT 'Ano letivo' AFTER `name`,
ADD FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`),
RENAME TO `classes`;

ALTER TABLE `Binds`
CHANGE `idUser` `user_id` int(11) unsigned NOT NULL FIRST,
CHANGE `idDiscipline` `discipline_id` int(11) unsigned NOT NULL AFTER `user_id`,
ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
ADD FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
RENAME TO `binds`;

ALTER TABLE `Offers`
CHANGE `idOffer` `offer_id` int(11) unsigned NULL COMMENT 'Auto relacionamento master/slave' AFTER `id`,
CHANGE `idClass` `class_id` int(11) unsigned NOT NULL AFTER `offer_id`,
CHANGE `idDiscipline` `discipline_id` int(11) unsigned NOT NULL AFTER `class_id`,
CHANGE `typeFinal` `type_final` char(1) COLLATE 'utf8_general_ci' NOT NULL AFTER `maxlessons`,
CHANGE `dateFinal` `date_final` date NOT NULL AFTER `type_final`,
ADD FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
ADD FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
RENAME TO `offers`;

ALTER TABLE `Units`
CHANGE `idOffer` `offer_id` int(10) unsigned NOT NULL AFTER `id`,
ADD FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
RENAME TO `units`;

ALTER TABLE `FinalExams`
CHANGE `idOffer` `offer_id` int(10) unsigned NOT NULL FIRST,
CHANGE `idUser` `user_id` int(11) unsigned NOT NULL AFTER `offer_id`,
ADD FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
RENAME TO `final_exams`;

ALTER TABLE `Exams`
CHANGE `idUnit` `unit_id` int(11) unsigned NOT NULL AFTER `id`,
CHANGE `type` `type` char(1) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Tipo de avaliação\'E\' = \"exams\";\'L\' = \"list\";\'P\' = \"projects\";...' AFTER `title`,
ADD FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
RENAME TO `exams`;

ALTER TABLE `Attends`
CHANGE `idUnit` `unit_id` int(11) unsigned NOT NULL COMMENT 'Id da unidade que o usuário cursa' AFTER `id`,
CHANGE `idUser` `user_id` int(11) unsigned NOT NULL COMMENT 'Id do usuário' AFTER `unit_id`,
ADD FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
RENAME TO `attends`;

ALTER TABLE `DescriptiveExams`
CHANGE `idAttend` `attend_id` int(11) unsigned NOT NULL FIRST,
CHANGE `idExam` `exam_id` int(11) unsigned NOT NULL AFTER `attend_id`,
ADD FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`),
ADD FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`),
RENAME TO `descriptive_exams`;

ALTER TABLE `Lessons`
CHANGE `idUnit` `unit_id` int(11) unsigned NOT NULL COMMENT 'Id da unidade à que a aula está relacionada' AFTER `id`,
CHANGE `estimatedTime` `estimated_time` int(11) NOT NULL COMMENT 'Tempo estimado de uma aula' AFTER `keyworks`,
ADD FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
RENAME TO `lessons`;

ALTER TABLE `ExamsValues`
CHANGE `idAttend` `attend_id` int(11) unsigned NOT NULL COMMENT 'Id do relacionamento \"Cursa\"' FIRST,
CHANGE `idExam` `exam_id` int(11) unsigned NOT NULL COMMENT 'Título da avaliação' AFTER `attend_id`,
ADD FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`),
ADD FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`),
RENAME TO `exams_values`;

ALTER TABLE `Lectures`
CHANGE `idUser` `user_id` int(11) unsigned NOT NULL COMMENT 'Id do professor que leciona a disciplina' FIRST,
CHANGE `idOffer` `offer_id` int(10) unsigned NOT NULL COMMENT 'Id da disciplina lecionada' AFTER `user_id`,
ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
ADD FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
RENAME TO `lectures`;

ALTER TABLE `Frequencies`
CHANGE `idAttend` `attend_id` int(11) unsigned NOT NULL COMMENT 'Id do relacionamento \"cursa\"' FIRST,
CHANGE `idLesson` `lesson_id` int(11) unsigned NOT NULL COMMENT 'Id da aula' AFTER `attend_id`,
CHANGE `value` `value` char(1) COLLATE 'utf8_general_ci' NULL COMMENT '\'P\' = Presente;\'F\' = Falta;' AFTER `lesson_id`,
ADD FOREIGN KEY (`attend_id`) REFERENCES `attends` (`id`),
ADD FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`),
RENAME TO `frequencies`;

ALTER TABLE `lessons`
CHANGE `description` `description` text COLLATE 'utf8_general_ci' NULL COMMENT 'summary/abstract, breve descrição da aula ' AFTER `title`;

ALTER TABLE `offers`
CHANGE `type_final` `type_final` char(1) COLLATE 'utf8_general_ci' NULL AFTER `maxlessons`;

ALTER TABLE `offers`
CHANGE `date_final` `date_final` date NULL AFTER `type_final`;

ALTER TABLE `Logs`
RENAME TO `logs`;

ALTER TABLE `lessons`
CHANGE `goals` `goals` text COLLATE 'utf8_general_ci' NULL COMMENT 'Objetivos' AFTER `description`,
CHANGE `content` `content` text COLLATE 'utf8_general_ci' NULL COMMENT 'Conteúdo' AFTER `goals`,
CHANGE `methodology` `methodology` text COLLATE 'utf8_general_ci' NULL COMMENT 'Metodologia de classe' AFTER `content`,
CHANGE `resources` `resources` text COLLATE 'utf8_general_ci' NULL COMMENT 'Recursos necessários' AFTER `methodology`,
CHANGE `keyworks` `keyworks` varchar(255) COLLATE 'utf8_general_ci' NULL COMMENT 'palavras-chave' AFTER `resources`,
CHANGE `estimated_time` `estimated_time` int(11) NULL COMMENT 'Tempo estimado de uma aula' AFTER `keyworks`,
CHANGE `bibliography` `bibliography` text COLLATE 'utf8_general_ci' NULL COMMENT 'Bibliografia' AFTER `estimated_time`,
CHANGE `valuation` `valuation` varchar(255) COLLATE 'utf8_general_ci' NULL COMMENT 'Método de avaliação (prova, trabalho, lista...)' AFTER `bibliography`,
CHANGE `notes` `notes` text COLLATE 'utf8_general_ci' NULL COMMENT 'Anotações' AFTER `valuation`;

SET FOREIGN_KEY_CHECKS=1;