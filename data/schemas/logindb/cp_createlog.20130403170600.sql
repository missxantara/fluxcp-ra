ALTER TABLE `cp_createlog`
DROP COLUMN `sex`,
CHANGE COLUMN `account_id` `cp_aid`  int(11) UNSIGNED NOT NULL AFTER `id`,
CHANGE COLUMN `userid` `username`  varchar(23) NOT NULL AFTER `cp_aid`,
CHANGE COLUMN `user_pass` `password`  varchar(32) NOT NULL AFTER `username`;