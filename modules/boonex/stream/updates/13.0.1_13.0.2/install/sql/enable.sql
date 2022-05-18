-- GRIDS:
UPDATE `sys_grid_actions` SET `title`='_bx_stream_grid_action_download', `icon_only`='1' WHERE `object`='bx_stream_recordings' AND `name`='download';
UPDATE `sys_grid_actions` SET `title`='_bx_stream_grid_action_publish', `icon_only`='1' WHERE `object`='bx_stream_recordings' AND `name`='publish';
UPDATE `sys_grid_actions` SET `title`='_Delete', `icon_only`='1' WHERE `object`='bx_stream_recordings' AND `name`='delete';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_stream_recordings';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_stream_recordings', '* * * * *', 'BxStrmCronRecordings', 'modules/boonex/stream/classes/BxStrmCronRecordings.php', '');
