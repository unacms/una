SET @sName = 'bx_accounts';


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object}.onClickResendCemail({content_id}, this);' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='resend-cemail';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object}.onClickDelete({content_id}, this);' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='delete';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object}.onClickDeleteWithContent({content_id}, this);' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='delete-with-content';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object}.onClickMakeOperator({content_id}, this);' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='make-operator';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object}.onClickUnmakeOperator({content_id}, this);' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='unmake-operator';