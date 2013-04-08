
SET @iId = (SELECT `ID` FROM `sys_menu_top` WHERE `Parent` = 0 AND `Name` = 'Forums' AND `Caption` = '_bx_forums');

UPDATE `sys_menu_top` SET `Deletable` = 1, `Editable` = 1 WHERE `Parent` = @iId AND `Name` = 'Forums Home' AND `Deletable` = 0 AND `Editable` = 0;
UPDATE `sys_menu_top` SET `Deletable` = 1, `Editable` = 1 WHERE `Parent` = @iId AND `Name` = 'Flagged Topics' AND `Deletable` = 0 AND `Editable` = 0;
UPDATE `sys_menu_top` SET `Deletable` = 1, `Editable` = 1 WHERE `Parent` = @iId AND `Name` = 'My Topics' AND `Deletable` = 0 AND `Editable` = 0;
UPDATE `sys_menu_top` SET `Deletable` = 1, `Editable` = 1 WHERE `Parent` = @iId AND `Name` = 'Spy' AND `Deletable` = 0 AND `Editable` = 0;
UPDATE `sys_menu_top` SET `Deletable` = 1, `Editable` = 1 WHERE `Parent` = @iId AND `Name` = 'Forum Search' AND `Deletable` = 0 AND `Editable` = 0;


