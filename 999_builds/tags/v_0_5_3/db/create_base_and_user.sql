DELIMITER $$

--
-- Base de datos: `@db_database@`
--
CREATE DATABASE `@db_database@` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci$$
USE `@db_database@`$$

--
-- Create @db_user@
--
GRANT ALL PRIVILEGES ON `@db_database@`.*

      TO '@db_user@'@'localhost' IDENTIFIED BY '@db_password@'

      WITH GRANT OPTION$$
