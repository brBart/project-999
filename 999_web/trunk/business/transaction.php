<?php
/**
 * Library with the necessary classes for commiting an inventory transaction.
 * @package Transaction
 * @author Roberto Oliveros
 */


/**
 * Defines common functionality for a transaction class.
 * @package Transaction
 * @author Roberto Oliveros
 */
abstract class Transaction{
	/**
	 * Applies the transaction on the inventory.
	 *
	 * @param DocProductDetail $detail
	 */
	abstract public function apply(DocProductDetail $detail);
	
	/**
	 * Reverts the apply action of the transaction.
	 *
	 * @param DocProductDetail $detail
	 */
	abstract public function cancel(DocProductDetail $detail);
	
	/**
	 * Indicates if this transcation can be cancel.
	 *
	 * @param DocProductDetail $detail
	 * @return boolean
	 */
	abstract public function isCancellable(DocProductDetail $detail);
}


/**
 * Class in charge of making withdraws in the inventory.
 * @package Transaction
 * @author Roberto Oliveros
 */
class Withdraw extends Transaction{
	/**
	 * Makes an withdraw of product in the inventory.
	 *
	 * @param DocProductDetail $detail
	 */
	public function apply(DocProductDetail $detail){
		$quantity = $detail->getQuantity();
		
		$lot = $detail->getLot();
		$reserve = $detail->getReserve();
		$lot->decrease($quantity);
		$lot->decreaseReserve($quantity);
		
		$product = $lot->getProduct();
		Inventory::decrease($product, $quantity);
		Inventory::decreaseReserve($product, $quantity);
		
		Reserve::delete($reserve);
	}
	
	/**
	 * Undoes the apply action of the transaction.
	 *
	 * @param DocProductDetail $detail
	 */
	public function cancel(DocProductDetail $detail){
		$quantity = $detail->getQuantity();
		
		$lot = $detail->getLot();
		$lot->increase($quantity);
		
		$product = $lot->getProduct();
		Inventory::increase($product, $quantity);
	}
	
	/**
	 * This transaction is always cancellable.
	 *
	 * @param DocProductDetail $detail
	 * @return boolean
	 */
	public function isCancellable(DocProductDetail $detail){
		return true;
	}
}


/**
 * Class making the entry transactions on the inventory.
 * @package Transaction
 * @author Roberto Oliveros
 */
class Entry extends Transaction{
	/**
	 * Makes an entry of product in the inventory.
	 *
	 * @param DocProductDetail $detail
	 */
	public function apply(DocProductDetail $detail){
		$lot = $detail->getLot();
		$product = $lot->getProduct();
		$available = Inventory::getAvailable($product);
		$lot->save();
		Inventory::increase($product, $detail->getQuantity());
		$new_price = $detail->getPrice();
		if($available <= 0 || $new_price > $product->getPrice()){
			$product->setPrice($new_price);
			$product->save();
		}
	}
	
	/**
	 * Undoes the apply action taken by the transaction.
	 *
	 * @param DocProductDetail $detail
	 */
	public function cancel(DocProductDetail $detail){
		$lot = $detail->getLot();
		$product = $lot->getProduct();
		
		/**
		 * @todo Check why can still cancel negative lots.
		 */
		if($lot instanceof NegativeLot){
			$negative = $lot->getNegativeQuantity();
			Inventory::decrease($product, (-1 * $negative));
			$lot->decrease(-1 * $negative);
			$lot->setNegativeQuantity(0);
		}
		else{
			Inventory::decrease($product, $lot->getQuantity());
			$lot->deactivate();
		}
	}
	
	/**
	 * Returns true if this transaction can be cancel.
	 *
	 * The transaction can be cancel when the detail's lot is an instance of a NegativeLot or its quantity is
	 * equal to the detail's quantity.
	 * @param DocProductDetail $detail
	 * @return boolean
	 */
	public function isCancellable(DocProductDetail $detail){
		$lot = $detail->getLot();
		
		/**
		 * @todo Verify if the condition with NegativeLot is correct.
		 */
		if($lot instanceof NegativeLot || $detail->getQuantity() != $lot->getQuantity())
			return false;
		else
			return true;
	}
}
?>