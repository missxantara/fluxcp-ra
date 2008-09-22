<?php
require_once 'Flux/TemporaryTable.php';
require_once 'Flux/ItemExistsError.php';

class Flux_ItemShop {
	/**
	 * @access public
	 * @var Flux_Athena
	 */
	public $server;
	
	public function __construct(Flux_Athena $server)
	{
		$this->server = $server;
	}
	
	/**
	 * Add an item to the shop.
	 */
	public function add($itemID, $cost, $quantity, $info)
	{
		$db    = $this->server->charMapDatabase;
		$table = Flux::config('FluxTables.ItemShopTable');
		$sql   = "INSERT INTO $db.$table (nameid, quantity, cost, info, create_date) VALUES (?, ?, ?, ?, NOW())";
		$sth   = $this->server->connection->getStatement($sql);
		
		return $sth->execute(array($itemID, $quantity, $cost, $info));
	}
	
	/**
	 * Modify item info in the shop.
	 */
	public function edit($shopItemID, $cost = null, $quantity = null, $info = null)
	{
		$crdQ = '';
		$qtyQ = '';
		$infQ = '';
		$bind = array();
		
		if (!is_null($cost)) {
			$crdQ   = "cost = ? ";
			$bind[] = (int)$cost;
		}
		
		if (!is_null($quantity)) {
			if ($crdQ) {
				$qtyQ = ', quantity = ? ';
			}
			else {
				$qtyQ = "quantity = ? ";
			}
			
			$bind[] = (int)$quantity;
		}
		
		if (!is_null($info)) {
			if ($qtyQ) {
				$infQ = ', info = ? ';
			}
			else {
				$infQ = "info = ? ";
			}
			
			$bind[] = trim($info);
		}
		
		if (empty($bind)) {
			return false;
		}
		
		$db    = $this->server->charMapDatabase;
		$table = Flux::config('FluxTables.ItemShopTable');
		$sql   = "UPDATE $db.$table SET $crdQ $qtyQ $infQ WHERE id = ?";
		$sth   = $this->server->connection->getStatement($sql);
		
		$bind[] = $shopItemID;
		return $sth->execute($bind);
	}
	
	/**
	 *
	 */
	public function delete($shopItemID)
	{
		$db    = $this->server->charMapDatabase;
		$table = Flux::config('FluxTables.ItemShopTable');
		$sql   = "DELETE FROM $db.$table WHERE id = ?";
		$sth   = $this->server->connection->getStatement($sql);
		
		return $sth->execute(array($shopItemID));
	}
	
	/**
	 *
	 */
	public function buy(Flux_DataObject $account, $shopItemID)
	{
		
	}
	
	/**
	 *
	 */
	public function getItem($shopItemID)
	{
		$db    = $this->server->charMapDatabase;
		$temp  = new Flux_TemporaryTable($this->server->connection, "$db.items", array("$db.item_db", "$db.item_db2"));
		$shop  = Flux::config('FluxTables.ItemShopTable');
		$col   = "$shop.id AS shop_item_id, $shop.cost AS shop_item_cost, $shop.quantity AS shop_item_qty, ";
		$col  .= "$shop.nameid AS shop_item_nameid, $shop.info AS shop_item_info, items.name_japanese AS shop_item_name";
		$sql   = "SELECT $col FROM $db.$shop LEFT OUTER JOIN $db.items ON items.id = $shop.nameid WHERE $shop.id = ?";
		$sth   = $this->server->connection->getStatement($sql);
		
		if ($sth->execute(array($shopItemID))) {
			return $sth->fetch();
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function getItems()
	{	
		$db    = $this->server->charMapDatabase;
		$temp  = new Flux_TemporaryTable($this->server->connection, "$db.items", array("$db.item_db", "$db.item_db2"));
		$shop  = Flux::config('FluxTables.ItemShopTable');
		$col   = "$shop.id AS shop_item_id, $shop.cost AS shop_item_cost, $shop.quantity AS shop_item_qty, ";
		$col  .= "$shop.nameid AS shop_item_nameid, $shop.info AS shop_item_info, items.name_japanese AS shop_item_name";
		$sql   = "SELECT $col FROM $db.$shop LEFT OUTER JOIN $db.items ON items.id = $shop.nameid";
		$sth   = $this->server->connection->getStatement($sql);
		
		if ($sth->execute()) {
			return $sth->fetchAll();
		}
		else {
			return false;
		}
	}
}
?>