CREATE TABLE IF NOT EXISTS `flux_donation_credits` (
 `account_id` INT( 11 ) UNSIGNED NOT NULL ,
 `balance` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
 `last_donation_date` DATETIME NULL ,
 `last_donation_amount` FLOAT UNSIGNED NOT NULL ,
PRIMARY KEY ( `account_id` )
) ENGINE = MYISAM COMMENT = 'Donation credits balance for a given account.';