SET @sName = 'bx_events';


-- PAGES
UPDATE `sys_objects_page` SET `uri`='event-questionnaire', `title_system`='_bx_events_page_title_sys_questionnaire', `title`='_bx_events_page_title_questionnaire', `url`='page.php?i=event-questionnaire' WHERE `object`='bx_events_questionnaire';

UPDATE `sys_objects_page` SET `uri`='event-sessions', `url`='page.php?i=event-sessions' WHERE `object`='bx_events_profile_sessions';
UPDATE `sys_pages_blocks` set `title_system`='', `title`='_bx_events_page_block_title_profile_sessions' WHERE `object`='bx_events_profile_sessions' AND `title`='_bx_events_page_block_title_profile_sessions_link';

UPDATE `sys_objects_page` SET `uri`='event-pricing', `url`='page.php?i=event-pricing' WHERE `object`='bx_events_profile_pricing';
UPDATE `sys_pages_blocks` set `title_system`='', `title`='_bx_events_page_block_title_profile_pricing' WHERE `object`='bx_events_profile_pricing' AND `title`='_bx_events_page_block_title_profile_pricing_link';


-- MENUS
UPDATE `sys_menu_items` SET `name`='event-questionnaire', `link`='page.php?i=event-questionnaire&profile_id={profile_id}' WHERE `set_name`='bx_events_view_actions_more' AND `name`='edit-event-questionnaire';
UPDATE `sys_menu_items` SET `name`='event-sessions', `link`='page.php?i=event-sessions&profile_id={profile_id}' WHERE `set_name`='bx_events_view_actions_more' AND `name`='edit-event-sessions';
UPDATE `sys_menu_items` SET `name`='event-pricing', `link`='page.php?i=event-pricing&profile_id={profile_id}' WHERE `set_name`='bx_events_view_actions_more' AND `name`='edit-event-pricing';

UPDATE `sys_menu_items` SET `name`='event-questionnaire' WHERE `set_name`='bx_events_view_actions_all' AND `name`='edit-event-questionnaire';
UPDATE `sys_menu_items` SET `name`='event-sessions' WHERE `set_name`='bx_events_view_actions_all' AND `name`='edit-event-sessions';
UPDATE `sys_menu_items` SET `name`='event-pricing' WHERE `set_name`='bx_events_view_actions_all' AND `name`='edit-event-pricing';

UPDATE `sys_menu_items` SET `title`='_bx_events_menu_item_title_sm_members' WHERE `set_name`='bx_events_view_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `title`='_bx_events_menu_item_title_sm_subscribers' WHERE `set_name`='bx_events_view_meta' AND `name`='subscribers';

UPDATE `sys_menu_items` SET `title_system`='_bx_events_menu_item_title_system_clear_reports' WHERE `set_name`='bx_events_menu_manage_tools' AND `name`='clear-reports';
