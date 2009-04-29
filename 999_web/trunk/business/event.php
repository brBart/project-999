<?php
/**
 * Library with event classes for creating documents.
 * @package Event
 * @author Roberto Oliveros
 */

/**
 * Event class for creating an entry type document.
 * @package Event
 * @author Roberto Oliveros
 */
class EntryEvent{
	/**
	 * Adds a detail to the provided document with the provided data.
	 *
	 * Date format: 'dd/mm/yyyy'.
	 * @param Product $product
	 * @param Document $document
	 * @param integer $quantity
	 * @param float $price
	 * @param string $expirationDate
	 */
	static public function apply(Product $product, Document $document, $quantity, $price, $expirationDate){
		Persist::validateNewObject($document);
		
		$lot = new Lot($product, $quantity, $price, $expirationDate);
		$document->addDetail(new DocProductDetail($lot, new Entry(), $quantity, $price));
	}
	
	/**
	 * Deletes the detail from the provided document.
	 *
	 * @param Document $document
	 * @param DocProductDetail $detail
	 */
	static public function cancel(Document $document, DocProductDetail $detail){
		$document->deleteDetail($detail);
	}
}


/**
 * Event class for creating entry type documents and adjust negative lots in the inventory.
 * @package Event
 * @author Roberto Oliveros
 */
class EntryAdjustmentEvent extends EntryEvent{
	/**
	 * Adds a detail to the provided document with the provided data.
	 *
	 * If there are negative lots of the product provided in the inventory, these will be adjusted and no lots
	 * will be created. The msg parameter is to pass back the notification to the user. Date format: 'dd/mm/yyyy'.
	 * @param Product $product
	 * @param Document $document
	 * @param integer $quantity
	 * @param float $price
	 * @param string $expirationDate
	 * @param string &$msg
	 */
	static public function apply(Product $product, Document $document, $quantity, $price, $expirationDate, &$msg){
		$lots = Inventory::getNegativeLots($product, $quantity);
		
		foreach($lots as &$lot){
			$negative = $lot->getQuantity();
			if($negative < 0){
				$lot_quantity = abs($negative);
				$quantity -= $lot_quantity;
				// Adjust the lot's quantity
				$lot->increase($lot_quantity);
				// Set the negative quantity in case the action will be cancel.
				$lot->setNegativeQuantity($negative);
				$msg = 'Lote ' . $lot->getId() . ' del producto agregado contiene un saldo negativo. No se ha ' .
						'creado un nuevo lote para poder este ser ajustado.';
			}
			else{
				// Lot is not a NegativeLot.
				$lot_quantity = $quantity;
				$lot->increase($lot_quantity);
			}
			
			$lot->setPrice($price);
			$lot->setExpirationDate($expirationDate);
			$document->addDetail(new DocProductDetail($lot, new Entry(), $lot_quantity, $price));
		}
	}
}
?>