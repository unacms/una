-- TABLES
ALTER TABLE `bx_market_files` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_market_photos` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_market_photos_resized` CHANGE `size` `size` bigint(20) NOT NULL;
