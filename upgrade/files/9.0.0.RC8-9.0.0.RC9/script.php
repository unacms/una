<?php

    if (!$this->oDb->isFieldExists('sys_accounts', 'locked'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `locked` tinyint(4) NOT NULL DEFAULT '0' AFTER `reffered`");
    if (!$this->oDb->isFieldExists('sys_accounts', 'login_attempts'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `login_attempts` tinyint(4) NOT NULL DEFAULT '0' AFTER `reffered`");

    if (!$this->oDb->isFieldExists('sys_objects_page', 'type_id'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `type_id` int(11) NOT NULL DEFAULT '1' AFTER `cover_image`");
    if (!$this->oDb->isFieldExists('sys_objects_page', 'submenu'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `submenu` varchar(64) NOT NULL DEFAULT '' AFTER `layout_id`");

    if (!$this->oDb->isFieldExists('sys_preloader', 'order')) {
        $this->oDb->query("ALTER TABLE `sys_preloader` ADD `order` int(11) unsigned NOT NULL default '0' AFTER `active`");
        $this->oDb->query("DELETE FROM `sys_preloader` WHERE `module` = 'system'");
        $this->oDb->query("INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'css_system', '{dir_plugins_public}marka/|marka.min.css', 1, 1),
('system', 'css_system', '{dir_plugins_public}at.js/css/|jquery.atwho.min.css', 1, 2),
('system', 'css_system', '{dir_plugins_public}prism/|prism.css', 1, 3),
('system', 'css_system', 'common.css', 1, 10),
('system', 'css_system', 'default.less', 1, 11),
('system', 'css_system', 'general.css', 1, 12),
('system', 'css_system', 'icons.css', 1, 13),
('system', 'css_system', 'colors.css', 1, 14),
('system', 'css_system', 'forms.css', 1, 15),
('system', 'css_system', 'media-desktop.css', 1, 20),
('system', 'css_system', 'media-tablet.css', 1, 21),
('system', 'css_system', 'media-phone.css', 1, 22),
('system', 'css_system', 'media-print.css', 1, 23),
('system', 'css_system', 'cmts.css', 1, 30),
('system', 'css_system', 'favorite.css', 1, 31),
('system', 'css_system', 'feature.css', 1, 32),
('system', 'css_system', 'report.css', 1, 33),
('system', 'css_system', 'score.css', 1, 34),
('system', 'css_system', 'view.css', 1, 35),
('system', 'css_system', 'vote.css', 1, 36)");
        $this->oDb->query("INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'jquery/jquery.min.js', 1, 1),
('system', 'js_system', 'jquery/jquery-migrate.min.js', 1, 2),
('system', 'js_system', 'jquery-ui/jquery.ui.position.min.js', 1, 3),
('system', 'js_system', 'jquery.easing.js', 1, 4),
('system', 'js_system', 'jquery.cookie.min.js', 1, 5),
('system', 'js_system', 'jquery.form.min.js', 1, 6),
('system', 'js_system', 'spin.min.js', 1, 7),
('system', 'js_system', 'moment-with-locales.min.js', 1, 8),
('system', 'js_system', 'marka/marka.min.js', 1, 9),
('system', 'js_system', 'headroom.min.js', 1, 10),
('system', 'js_system', 'at.js/js/jquery.atwho.min.js', 1, 11),
('system', 'js_system', 'prism/prism.js', 1, 12),
('system', 'js_system', 'functions.js', 1, 20),
('system', 'js_system', 'jquery.webForms.js', 1, 21),
('system', 'js_system', 'jquery.dolPopup.js', 1, 22),
('system', 'js_system', 'jquery.dolConverLinks.js', 1, 23),
('system', 'js_system', 'jquery.anim.js', 1, 24),
('system', 'js_system', 'BxDolCmts.js', 1, 30),
('system', 'js_system', 'BxDolFavorite.js', 1, 31),
('system', 'js_system', 'BxDolFeature.js', 1, 32),
('system', 'js_system', 'BxDolReport.js', 1, 33),
('system', 'js_system', 'BxDolScore.js', 1, 34),
('system', 'js_system', 'BxDolView.js', 1, 35),
('system', 'js_system', 'BxDolVote.js', 1, 36)");
        $this->oDb->query("INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_translation', '_Are_you_sure', 1, 1),
('system', 'js_translation', '_error occured', 1, 2),
('system', 'js_translation', '_sys_loading', 1, 3),
('system', 'js_translation', '_copyright', 1, 4)");
    }
        
    return true;
