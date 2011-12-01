<?php
set_include_path(get_include_path() . ';c:\\Users\\pc\\999_project\\999_middle\\' .
		';c:\\Users\\pc\\999_project\\999_tests\\');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

//require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('product_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('ManufacturerDAMTest');
		$ts->addTestSuite('ManufacturerDeleteProductDependencyTest');
		$ts->addTestSuite('UnitOfMeasureDAMTest');
		$ts->addTestSuite('UnitOfMeasureDeleteProductDependencyTest');
		$ts->addTestSuite('ProductSupplierDAMTest');
		$ts->addTestSuite('ProductDAMTest');
		$ts->addTestSuite('ProductDeleteTest');
		$ts->addTestSuite('ProductDeleteLotDependencyTest');
		$ts->addTestSuite('ProductDeleteBonusDependencyTest');
		$ts->addTestSuite('ProductDeleteComparisonProductDependencyTest');
		$ts->addTestSuite('ProductDeleteCountProductDependencyTest');
		$ts->addTestSuite('LotDAMTest');
		$ts->addTestSuite('BonusDAMTest');
		$ts->addTestSuite('BonusDeleteInvoiceBonusDependencyTest');
		$ts->addTestSuite('ChangePriceLogDAMTest');
		$ts->addTestSuite('ProductSearchDAMTest');
		$ts->addTestSuite('ManufacturerProductListDAMTest');
		$ts->addTestSuite('InventoryDAMTest');
		$ts->addTestSuite('InventoryDAMGetLotsTest');
		$ts->addTestSuite('KardexDAMTest');
		$ts->addTestSuite('ProductBonusListDAMTest');
		$ts->addTestSuite('ExpiredLotListDAMTest');
		$ts->addTestSuite('NearExpirationLotListDAMTest');
		$ts->addTestSuite('InactiveProductListDAMTest');
		$ts->addTestSuite('SupplierProductListDAMTest');
		$ts->addTestSuite('ReserveListDAMTest');
		$ts->addTestSuite('ProductDistinctListDAMTest');
		$ts->addTestSuite('ManufacturerDistinctListDAMTest');
		$ts->addTestSuite('InStockListDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>