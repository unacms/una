SET @sName = 'bx_events';


-- PAGES
UPDATE `sys_objects_page` SET `url`='page.php?i=event-manage' WHERE `object`='bx_events_manage_item';
