
DELETE FROM `sys_stat_site` WHERE `Name` = 'pls' AND `Title` = 'bx_polls';

INSERT INTO 
        `sys_stat_site` 
    SET 
        `Name`       = 'pls', 
        `Title`      = 'bx_polls', 
        `UserLink`   = 'modules/?r=poll/&action=poll_home',
        `UserQuery`  = 'SELECT COUNT(`id_poll`) FROM `bx_poll_data` WHERE `poll_approval`=1 and `poll_status` = ''active'' ', 
        `AdminLink`  = '../modules/?r=poll/administration', 
        `AdminQuery` = 'SELECT COUNT(`id_poll`) FROM `bx_poll_data` WHERE `poll_approval`=0', 
        `IconName`   = 'modules/boonex/poll/|pls.png', 
        `StatOrder`  = 10;

