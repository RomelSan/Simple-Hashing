CREATE DATABASE `hash` /*!40100 DEFAULT CHARACTER SET latin1 */;


CREATE TABLE `hash`.`user` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(100) NOT NULL,
  `lastname` VARCHAR(100) NOT NULL,
  `social_id` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `last_login_date` DATETIME NULL,
  `is_active` VARCHAR(5) NOT NULL,
  `role` VARCHAR(12) NOT NULL,
  `admin_max_users` INT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `social_id_UNIQUE` (`social_id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC));
  

CREATE TABLE `hash`.`filename` (
  `idfilename` INT NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(300) NOT NULL,
  `file_extension` VARCHAR(50) NULL,
  `hash_primary` VARCHAR(100) NOT NULL,
  `hash_secondary` VARCHAR(100) NOT NULL,
  `date_uploaded` DATETIME NOT NULL,
  `user_email` VARCHAR(100) NOT NULL,
  `file_status` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`idfilename`),
  INDEX `user_email_idx` (`user_email` ASC),
  CONSTRAINT `user`
    FOREIGN KEY (`user_email`)
    REFERENCES `hash`.`user` (`email`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
