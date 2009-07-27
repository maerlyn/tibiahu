
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- tibia_character
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_character`;


CREATE TABLE `tibia_character`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`level` INTEGER  NOT NULL,
	`slug` VARCHAR(255)  NOT NULL,
	`last_seen` DATETIME,
	`guild_id` INTEGER,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`vocation_id` INTEGER,
	`server_id` INTEGER,
	PRIMARY KEY (`id`,`slug`),
	UNIQUE KEY `tibia_character_U_1` (`slug`),
	KEY `tibia_character_I_1`(`name`),
	INDEX `tibia_character_FI_1` (`guild_id`),
	CONSTRAINT `tibia_character_FK_1`
		FOREIGN KEY (`guild_id`)
		REFERENCES `tibia_guild` (`id`)
		ON DELETE SET NULL,
	INDEX `tibia_character_FI_2` (`server_id`),
	CONSTRAINT `tibia_character_FK_2`
		FOREIGN KEY (`server_id`)
		REFERENCES `tibia_server` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_creature
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_creature`;


CREATE TABLE `tibia_creature`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100),
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_guild
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_guild`;


CREATE TABLE `tibia_guild`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`slug` VARCHAR(255),
	`updated_at` DATETIME,
	`members` INTEGER,
	`server_id` INTEGER,
	PRIMARY KEY (`id`,`name`),
	UNIQUE KEY `tibia_guild_U_1` (`slug`),
	INDEX `tibia_guild_FI_1` (`server_id`),
	CONSTRAINT `tibia_guild_FK_1`
		FOREIGN KEY (`server_id`)
		REFERENCES `tibia_server` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_levelhistory
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_levelhistory`;


CREATE TABLE `tibia_levelhistory`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`character_id` INTEGER  NOT NULL,
	`level` INTEGER,
	`created_at` DATETIME,
	`reason_id` INTEGER,
	PRIMARY KEY (`id`,`character_id`),
	INDEX `tibia_levelhistory_FI_1` (`character_id`),
	CONSTRAINT `tibia_levelhistory_FK_1`
		FOREIGN KEY (`character_id`)
		REFERENCES `tibia_character` (`id`)
		ON DELETE CASCADE,
	INDEX `tibia_levelhistory_FI_2` (`reason_id`),
	CONSTRAINT `tibia_levelhistory_FK_2`
		FOREIGN KEY (`reason_id`)
		REFERENCES `tibia_creature` (`id`)
		ON DELETE SET NULL
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_cronlog
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_cronlog`;


CREATE TABLE `tibia_cronlog`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME,
	`type` VARCHAR(100),
	`data` VARCHAR(255),
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_setting
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_setting`;


CREATE TABLE `tibia_setting`
(
	`key` VARCHAR(255)  NOT NULL,
	`value` VARCHAR(255),
	PRIMARY KEY (`key`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_banishment
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_banishment`;


CREATE TABLE `tibia_banishment`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`character_id` INTEGER,
	`banished_until` DATETIME,
	`banished_for_id` INTEGER,
	`banished_at` DATETIME,
	`level` INTEGER,
	PRIMARY KEY (`id`),
	KEY `tibia_banishment_I_1`(`character_id`),
	CONSTRAINT `tibia_banishment_FK_1`
		FOREIGN KEY (`character_id`)
		REFERENCES `tibia_character` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_house
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_house`;


CREATE TABLE `tibia_house`
(
	`id` INTEGER,
	`name` VARCHAR(255),
	`slug` VARCHAR(255)  NOT NULL,
	PRIMARY KEY (`slug`),
	UNIQUE KEY `tibia_house_U_1` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_server
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_server`;


CREATE TABLE `tibia_server`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(32),
	`is_enabled` TINYINT,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_news
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_news`;


CREATE TABLE `tibia_news`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER,
	`created_at` DATETIME,
	`category_id` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `tibia_news_FI_1` (`user_id`),
	CONSTRAINT `tibia_news_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `sf_guard_user` (`id`)
		ON DELETE SET NULL,
	INDEX `tibia_news_FI_2` (`category_id`),
	CONSTRAINT `tibia_news_FK_2`
		FOREIGN KEY (`category_id`)
		REFERENCES `tibia_newscategory` (`id`)
		ON DELETE SET NULL
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_news_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_news_i18n`;


CREATE TABLE `tibia_news_i18n`
(
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	`title` VARCHAR(255)  NOT NULL,
	`slug` VARCHAR(255)  NOT NULL,
	`body` TEXT  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	KEY `tibia_news_i18n_I_1`(`slug`),
	CONSTRAINT `tibia_news_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `tibia_news` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_newscategory
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_newscategory`;


CREATE TABLE `tibia_newscategory`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_newscategory_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_newscategory_i18n`;


CREATE TABLE `tibia_newscategory_i18n`
(
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	`name` VARCHAR(64),
	`slug` VARCHAR(64),
	PRIMARY KEY (`id`,`culture`),
	UNIQUE KEY `tibia_newscategory_i18n_U_1` (`slug`),
	CONSTRAINT `tibia_newscategory_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `tibia_newscategory` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- tibia_gamemaster
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tibia_gamemaster`;


CREATE TABLE `tibia_gamemaster`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`last_seen` DATETIME,
	`server_id` INTEGER,
	PRIMARY KEY (`id`),
	UNIQUE KEY `tibia_gamemaster_U_1` (`name`),
	KEY `tibia_gamemaster_I_1`(`last_seen`),
	INDEX `tibia_gamemaster_FI_1` (`server_id`),
	CONSTRAINT `tibia_gamemaster_FK_1`
		FOREIGN KEY (`server_id`)
		REFERENCES `tibia_server` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
