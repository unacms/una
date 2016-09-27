-- TABLES
ALTER TABLE `bx_oauth_clients` CHANGE `user_id` `user_id` INT( 10 ) UNSIGNED NULL DEFAULT '0';


DELETE FROM `bx_oauth_scopes` WHERE `scope` = 'market';
INSERT INTO `bx_oauth_scopes` (`scope`, `is_default`) VALUES ('market', 0);