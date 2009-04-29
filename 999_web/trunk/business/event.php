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
 * Event class for creating entry type documents which can adjust negative lots in the inventory.
 * @package Event
 * @author Roberto Oliveros
 */
class EntryAdjustmentEvent extends EntryEvent{
	
	static public function apply(Product $product, Document $document, $quantity, $price, $expirationDate, &$msg){
		
	}
}
?>