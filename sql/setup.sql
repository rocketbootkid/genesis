CREATE SCHEMA IF NOT EXISTS genesis;

USE genesis;

CREATE TABLE `genesis`.`definitions` (
  `definition_id` INT NOT NULL AUTO_INCREMENT,
  `definition` VARCHAR(500) NULL,
  `options` VARCHAR(200) NULL,
  PRIMARY KEY (`definition_id`));