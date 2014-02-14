
CREATE TABLE `bx_antispam_ip_table` (
  `ID` int(11) NOT NULL auto_increment,
  `From` int(10) unsigned NOT NULL,
  `To` int(10) unsigned NOT NULL,
  `Type` enum('allow','deny') NOT NULL default 'deny',
  `LastDT` int(11) unsigned NOT NULL,
  `Desc` varchar(128) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `From` (`From`),
  KEY `To` (`To`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `bx_antispam_dnsbl_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chain` enum('spammers','whitelist','uridns') NOT NULL,
  `zonedomain` varchar(255) NOT NULL,
  `postvresp` varchar(32) NOT NULL,
  `url` varchar(255) NOT NULL,
  `recheck` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `added` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `bx_antispam_dnsbl_rules` (`id`, `chain`, `zonedomain`, `postvresp`, `url`, `recheck`, `comment`, `added`, `active`) VALUES
(1, 'whitelist', 'au.countries.nerd.dk.', '127.0.0.2', 'http://countries.nerd.dk/', '', 'Country based zone, any ip from Australia is whitelisted', 1287642420, 0),
(2, 'spammers', 'sbl.spamhaus.org.', 'any', 'http://www.spamhaus.org/sbl/', 'http://www.spamhaus.org/query/bl?ip=%s', 'Any non-failure result from sbl.spamhaus.org is a positive match', 1287642420, 1),
(3, 'spammers', 'zomgbl.spameatingmonkey.net.', 'any', 'http://spameatingmonkey.com/index.html', '', 'This zone is guaranteed to block 100% of all IPs because it lists everything (0.0.0.0/0). This list should never be used in production but exists to verify overall functionality of the blacklist servers.', 1287642420, 0),
(4, 'spammers', 'cn.countries.nerd.dk.', '127.0.0.2', 'http://countries.nerd.dk/', '', 'Country based zone, any ip from China is blocked', 1287642420, 0),
(5, 'uridns', 'multi.surbl.org.', 'any', 'http://www.surbl.org/', 'http://george.surbl.org/lookup.html', 'SURBLs are lists of web sites that have appeared in unsolicited messages. Unlike most lists, SURBLs are not lists of message senders.', 1287642420, 1);

CREATE TABLE `bx_antispam_dnsbluri_zones` (
  `level` tinyint(4) NOT NULL,
  `zone` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_antispam_block_log` (
  `ip` int(10) unsigned NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `type` varchar(32) NOT NULL,
  `extra` text NOT NULL,
  `added` int(11) NOT NULL,
  KEY `ip` (`ip`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Studio page and widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_antispam', '_bx_antispam', '_bx_antispam', 'bx_antispam@modules/boonex/antispam/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_antispam', '{url_studio}module.php?name=bx_antispam', '', 'bx_antispam@modules/boonex/antispam/|std-wi.png', '_bx_antispam', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

