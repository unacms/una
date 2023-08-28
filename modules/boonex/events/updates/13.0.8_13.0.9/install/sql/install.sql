SET @sName = 'bx_events';


-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_events_cover';


-- FORMS
UPDATE `sys_form_inputs` SET `checker_func`='Length', `checker_params`='a:2:{s:3:"min";i:1;s:3:"max";i:80;}' WHERE `object`='bx_event' AND `name`='event_name';


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_events' WHERE `name`='bx_events';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_events' WHERE `Name`='bx_events';
