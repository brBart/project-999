--
-- Base de datos: `999_store`
--
CREATE DATABASE `999_store` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `999_store`;

--
-- Create 999_user
--
GRANT ALL PRIVILEGES ON `999_store`.*

      TO '999_user'@'localhost' IDENTIFIED BY '999_user'

      WITH GRANT OPTION;