<?php
/**
 * Library with event classes for creating documents.
 * @package Event
 * @author Roberto Oliveros
 */

/**
 * Event class for creating an entry type documents.
 * @package Event
 * @author Roberto Oliveros
 */
class EntryEvent{
	/**
	 * Adds a detail to the provided document with the provided data.
	 *
	 * Also creates a new lot of the provided product.
	 * Date format: 'dd/mm/yyyy'.
	 * @param Product $product
	 * @param Document $document
	 * @param integer $quantity
	 * @param float $price
	 * @param string $expirationDate
	 */
	static public function apply(Product $product, Document $document, $quantity, $price,
			$expirationDate = NULL){
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
		// Validation is not necessary because the document has already validated.
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
	static public function apply(Product $product, Document $document, $quantity, $price,
			$expirationDate = NULL, &$msg = NULL){
		Persist::validateNewObject($document);
				
		$lots = Inventory::getNegativeLots($product, $quantity);
		
		foreach($lots as &$lot){
			$negative = $lot->getQuantity();
			if($negative < 0){
				
				if(abs($negative) <= $quantity){
					$lot_quantity = abs($negative);
					$quantity -= $lot_quantity;
					// Adjust the lot's quantity
					$lot->increase($lot_quantity);
					// Set the negative quantity in case the action will be cancel.
					$lot->setNegativeQuantity($negative);
				}
				else{
					$lot_quantity = $quantity;
					$lot->increase($lot_quantity);
					$lot->setNegativeQuantity(-1 * $lot_quantity);
				}
				
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


/**
 * Event class for creating withdraw type documents.
 * @package Event
 * @author Roberto Oliveros
 */
class WithdrawEvent{
	/**
	 * Adds detail(s) to the provided document with the provided quantity.
	 *
	 * It creates a reserve on each necessary lot until it fulfills the requested quantity.
	 * @param Product $product
	 * @param Document $document
	 * @param integer $quantity
	 */
	static public function apply(Product $product, Document $document, $quantity){
		Persist::validateNewObject($document);
		
		$lots = Inventory::getLots($product, $quantity);
		$price = $product->getPrice();
		
		foreach($lots as &$lot){
			$available = $lot->getAvailable();
			if($available >= $quantity || $available == 0){
				$reserve = Reserve::create($lot, $quantity);
				$detail_quantity = $quantity;
				$quantity = 0;
			}
			else{
				$reserve = Reserve::create($lot, $available);
				$detail_quantity = $available;
				$quantity -= $available;
			}
			
			$document->addDetail(new DocProductDetail($lot, new Withdraw(), $detail_quantity, $price,
					$reserve));
		}
	}
	
	/**
	 * Deletes the detail from the provided document.
	 *
	 * It also deletes the reserve previously created.
	 * @param Document $document
	 * @param DocProductDetail $detail
	 */
	static public function cancel(Document $document, DocProductDetail $detail){
		// Validation is not necessary because the document has already validated.
		$reserve = $detail->getReserve();
		Reserve::delete($reserve);
		$document->deleteDetail($detail);
	}
}


/**
 * Event class for creating withdraw type documents, it verifies the inventory stock.
 * @package Event
 * @author Roberto Oliveros
 */
class StrictWithdrawEvent extends WithdrawEvent{
	/**
	 * Adds detail(s) to the provided document with the provided quantity.
	 *
	 * It creates a reserve on each necessary lot until it fulfills the requested quantity. It also verifies
	 * the inventory stock before taking action, if there is sufficient stock it proceeds.
	 * @param Product $product
	 * @param Document $document
	 * @param integer $quantity
	 * @throws Exception
	 */
	static public function apply(Product $product, Document $document, $quantity){
		// Validation of the document will be taken by the parent.
		Persist::validateObjectFromDatabase($product);
		Number::validatePositiveInteger($quantity, 'Cantidad inv&aacute;lida.');
		
		if(Inventory::getAvailable($product) < $quantity)
			throw new Exception('No hay suficiente cantidad disponible.');
			
		parent::apply($product, $document, $quantity);
	}
}


/**
 * Event class for creating sale invoice documents.
 * @package Event
 * @author Roberto Oliveros
 */
class RetailEvent{
	/**
	 * Adds detail(s) to the provided invoice with the provided quantity.
	 *
	 * It also check if the provided product has any sales.
	 * @param Product $product
	 * @param Invoice $invoice
	 * @param integer $quantity
	 */
	static public function apply(Product $product, Invoice $invoice, $quantity){
		// Validation of the document will be taken by the parent.
		WithdrawEvent::apply($product, $invoice, $quantity);
		self::applySales($invoice, $product);
	}
	
	/**
	 * Deletes the detail from the invoice document.
	 *
	 * It also removes the sales of the detail's product.
	 * @param Invoice $invoice
	 * @param DocumentDetail $detail
	 */
	static public function cancel(Invoice $invoice, DocumentDetail $detail){
		if($detail instanceof DocProductDetail){
			WithdrawEvent::cancel($invoice, $detail);
			$lot = $detail->getLot();
			$product = $lot->getProduct();
			self::applySales($invoice, $product);
		}
		else
			$invoice->deleteDetail($detail);
	}
	
	/**
	 * Applies sales bonifications to an invoice.
	 *
	 * @param Invoice $invoice
	 * @param Product $product
	 */
	static private function applySales(Invoice $invoice, Product $product){
		$bonus_detail = $invoice->getBonusDetail($product);
		if(!is_null($bonus_detail))
			self::cancel($invoice, $bonus_detail);
			
		$quantity = $invoice->getProductQuantity($product);
		if($quantity > 0){
			$bonus = Bonus::getInstanceByProduct($product, $quantity);
			if(!is_null($bonus))
				/**
			 	* @todo Verify if the result needs rounding.
			 	*/
				$invoice->addDetail(new DocBonusDetail($bonus,
						(float)number_format(-1 * (($product->getPrice() * $bonus->getQuantity()) *
						($bonus->getPercentage() / 100)), 2)));
		}
	}
}
?>