<?php
/**
 * Utility library with class for showing a document's details.
 * @package Itemized
 * @author Roberto Oliveros
 */

/**
 * Define the interface to the documents that contains details and needs paging.
 * @package Itemized
 * @author Roberto Oliveros
 */
interface Itemized{
	/**
	 * Returns the details of the document.
	 * @return array<variant>
	 */
	public function getDetails();
}

/**
 * Class for showing a document's details.
 * @package Itemized
 * @author Roberto Oliveros
 */
class DetailsPrinter{
	/**
	 * Returns the array with the details of the requested page of the provided object.
	 *
	 * If no page argument or cero is passed all the details are returned. The total_pages and
	 * total_items arguments are necessary to return their respective values.
	 * @param Itemized $obj
	 * @param integer &$total_pages
	 * @param integer &$total_items
	 * @param integer $page
	 * @return array
	 */
	static public function showPage(Itemized $obj, &$total_pages = 0, &$total_items = 0, $page = 0){
		if($page !== 0)
			Number::validatePositiveInteger($page, 'Pagina inv&aacute;lida.');
		
		$obj_details = $obj->getDetails();
		$details = array();
		
		// If there are no details.
		if(empty($obj_details))
			return $details;
		
		if($page == 0)
			// Return all the details available.
			foreach($obj_details as $detail)
				$details[] = $detail->show();
		else{
			$total_items = count($obj_details);
			$total_pages = ceil($total_items / ITEMS_PER_PAGE);
			
			$first_item = ($page - 1) * ITEMS_PER_PAGE;
			
			if($page == $total_pages)
				$last_item = $total_items;
			else
				$last_item = $first_item + ITEMS_PER_PAGE;
				
			for($i = $first_item; $i <= $last_item; $i++)
				$details[] = $obj_details[$i]->show();
		}
		
		return $details;
	}
}
?>