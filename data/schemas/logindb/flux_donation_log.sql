CREATE TABLE IF NOT EXISTS `flux_donation_log` (
 `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `status` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
 `account_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
 `payment_type` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
 `email` VARCHAR( 255 ) NOT NULL ,
 `amount` FLOAT UNSIGNED NOT NULL DEFAULT  '0',
 `received` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
 `balance` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
 `donation_date` DATETIME NULL
) ENGINE = MYISAM COMMENT =  'Detailed donation log of the donation transactions.';