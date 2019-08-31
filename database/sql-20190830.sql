ALTER TABLE `user` ADD COLUMN `access_token` VARCHAR(128) NOT NULL DEFAULT '' AFTER `password_hash`;
ALTER TABLE `user` ADD COLUMN `access_token_expired_at` INT(11) NOT NULL DEFAULT '0' AFTER `access_token`;
ALTER TABLE `user` ADD COLUMN `ping_at` INT(11) NOT NULL DEFAULT '0'  COMMENT '上次ping时间戳' AFTER `access_token_expired_at`;
ALTER TABLE `user` ADD COLUMN `ts_online` INT(11) NOT NULL DEFAULT '0' COMMENT '在线时间' AFTER `ping_at`;