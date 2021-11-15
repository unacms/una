SET @sName = 'bx_notifications';


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='bx_menu_slide(''bx_notifications_preview'', this, ''site'', {id:{value:''bx_notifications_preview'', force:1}, cssClass: ''''});' WHERE `set_name`='sys_toolbar_member' AND `name`='notifications-preview';