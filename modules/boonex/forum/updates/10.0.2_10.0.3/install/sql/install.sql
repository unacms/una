SET @sName = 'bx_forum';


-- TABLES
UPDATE `bx_forum_discussions` SET `status`='hidden' WHERE `status`='draft';
ALTER TABLE `bx_forum_discussions` CHANGE `status` `status` enum('active','hidden') NOT NULL DEFAULT 'active';
