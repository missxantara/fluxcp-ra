ALTER TABLE `cp_txnlog`
	CHANGE `payment_status` `payment_status` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
	CHANGE `pending_reason` `pending_reason` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
	CHANGE `txn_type` `txn_type` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;