ALTER TABLE `bx_posts_posts` DROP `status`;
ALTER TABLE `bx_posts_posts` ADD `status` ENUM( 'active', 'hidden' ) NOT NULL DEFAULT 'active';