
CREATE TABLE IF NOT EXISTS `bx_oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
);

CREATE TABLE IF NOT EXISTS `bx_oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `redirect_uri` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(255) DEFAULT NULL,
  `id_token` varchar(1000) DEFAULT NULL,
  `code_challenge` varchar(1000) DEFAULT NULL,
  `code_challenge_method` varchar(20) DEFAULT NULL
  PRIMARY KEY (`authorization_code`),
  KEY `user_id` (`user_id`)
);

CREATE TABLE IF NOT EXISTS `bx_oauth_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(255) NOT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT 0,
  `user_id` int(10) unsigned DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`)
);

CREATE TABLE IF NOT EXISTS `bx_oauth_allowed_origins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(191))
);

CREATE TABLE IF NOT EXISTS `bx_oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`),
  KEY `user_id` (`user_id`)
);

CREATE TABLE IF NOT EXISTS `bx_oauth_scopes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scope` varchar(255)  DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
   PRIMARY KEY (`id`)
);

INSERT INTO `bx_oauth_scopes` (`scope`, `is_default`) VALUES
('basic', 1),
('service', 0),
('market', 0);

-- Studio page and widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_oauth', '_bx_oauth', '_bx_oauth', 'bx_oauth@modules/boonex/oauth2/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_oauth', 'integrations', '{url_studio}module.php?name=bx_oauth&page=settings', '', 'bx_oauth@modules/boonex/oauth2/|std-icon.svg', '_bx_oauth', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

