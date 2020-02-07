CREATE TABLE IF NOT EXISTS `user` (
       `id` VARCHAR(36) NOT NULL,
       `login` VARCHAR(255) NOT NULL,
       `password_hash` VARCHAR(255) NOT NULL,
       `first_name` VARCHAR(255) NOT NULL,
       `last_name` VARCHAR(255) NOT NULL,
       `full_name` VARCHAR(255) NOT NULL,
       PRIMARY KEY (`id`),
       UNIQUE INDEX `login_UNIQUE` (`login` ASC))
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8mb4;
