SET @sName = 'bx_forum';


-- TABLES
ALTER TABLE `bx_forum_discussions` CHANGE `status` `status` enum('active','hidden') NOT NULL DEFAULT 'active';
