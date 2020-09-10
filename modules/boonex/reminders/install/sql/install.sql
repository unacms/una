
-- TABLES
CREATE TABLE IF NOT EXISTS `bx_reminders_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL default '0',
  `rmd_pid` int(11) NOT NULL default '0',
  `cnt_pid` int(11) NOT NULL default '0',
  `params` text NOT NULL default '',
  `notified` text NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  `visible` tinyint(4) NOT NULL default '0',
  `added` int(11) NOT NULL,
  `expired` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_reminders_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `when` varchar(32) NOT NULL,
  `show` int(11) NOT NULL default '0',
  `notify` varchar(255) NOT NULL,
  `personal` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `bx_reminders_types`(`author`, `added`, `changed`, `name`, `title`, `text`, `link`, `when`, `show`, `notify`, `personal`, `active`, `order`) VALUES
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'birthday', '_bx_reminders_type_title_birthday', '_bx_reminders_type_text_birthday', '', '', 7, '14,7,3', 1, 1, 1),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'marriage', '_bx_reminders_type_title_marriage', '_bx_reminders_type_text_marriage', '', '', 7, '14,7,3', 1, 1, 2),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'anniversary', '_bx_reminders_type_title_anniversary', '_bx_reminders_type_text_anniversary', '', '', 7, '14,7,3', 1, 1, 3),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'death', '_bx_reminders_type_title_death', '_bx_reminders_type_text_death', '', '', 14, '14,7,3', 1, 1, 4),

(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'new_year', '_bx_reminders_type_title_new_year', '_bx_reminders_type_text_new_year', '', '12-31', 7, '14,7,3', 0, 1, 10),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'mlk_day', '_bx_reminders_type_title_mlk_day', '_bx_reminders_type_text_mlk_day', '', '01-21', 7, '14,7,3', 0, 1, 11),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'valentine_day', '_bx_reminders_type_title_valentine_day', '_bx_reminders_type_text_valentine_day', '', '02-14', 7, '14,7,3', 0, 1, 12),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'start_of_spring', '_bx_reminders_type_title_start_of_spring', '_bx_reminders_type_text_start_of_spring', '', '03-21', 7, '14,7,3', 0, 1, 13),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'easter', '_bx_reminders_type_title_easter', '_bx_reminders_type_text_easter', '', '04-21', 7, '14,7,3', 0, 1, 14),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'memorial_day', '_bx_reminders_type_title_memorial_day', '_bx_reminders_type_text_memorial_day', '', '05-27', 7, '14,7,3', 0, 1, 15),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'start_of_summer', '_bx_reminders_type_title_start_of_summer', '_bx_reminders_type_text_start_of_summer', '', '06-21', 7, '14,7,3', 0, 1, 16),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '4th_july', '_bx_reminders_type_title_4th_july', '_bx_reminders_type_text_4th_july', '', '07-04', 7, '14,7,3', 0, 1, 17),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'labor_day', '_bx_reminders_type_title_labor_day', '_bx_reminders_type_text_labor_day', '', '09-02', 7, '14,7,3', 0, 1, 18),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'start_of_fall', '_bx_reminders_type_title_start_of_fall', '_bx_reminders_type_text_start_of_fall', '', '09-21', 7, '14,7,3', 0, 1, 19),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'halloween', '_bx_reminders_type_title_halloween', '_bx_reminders_type_text_halloween', '', '10-31', 7, '14,7,3', 0, 1, 20),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'veterans_day', '_bx_reminders_type_title_veterans_day', '_bx_reminders_type_text_veterans_day', '', '11-11', 7, '14,7,3', 0, 1, 21),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'thanksgiving', '_bx_reminders_type_title_thanksgiving', '_bx_reminders_type_text_thanksgiving', '', '11-28', 7, '14,7,3', 0, 1, 22),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'start_of_winter', '_bx_reminders_type_title_start_of_winter', '_bx_reminders_type_text_start_of_winter', '', '12-21', 7, '14,7,3', 0, 1, 23),
(0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'christmas', '_bx_reminders_type_title_christmas', '_bx_reminders_type_text_christmas', '', '12-25', 7, '14,7,3', 0, 1, 24);


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_reminders', '_bx_reminders', '_bx_reminders', 'bx_reminders@modules/boonex/reminders/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_reminders', '{url_studio}module.php?name=bx_reminders', '', 'bx_reminders@modules/boonex/reminders/|std-icon.svg', '_bx_reminders', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

