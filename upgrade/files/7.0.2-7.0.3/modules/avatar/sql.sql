
SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose` WHERE `Page` = 'pedit' AND `Column` = 2 ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
    ('pedit', '998px', 'Manage Avatars', '_bx_ava_manage_avatars', 2, @iMaxOrder, 'PHP', 'return BxDolService::call(''avatar'', ''manage_avatars'', array ((int)$_REQUEST[''ID'']));', 1, 50, 'memb', 0);

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'avatar' AND `version` = '1.0.2';

