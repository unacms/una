-- TABLES
ALTER TABLE `bx_classes_classes` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';

-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldComments`='' WHERE `Name`='bx_classes_notes';