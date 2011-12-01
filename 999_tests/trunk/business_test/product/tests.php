<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('product_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('UnitOfMeasureTest');
		$ts->addTestSuite('ManufacturerTest');
		$ts->addTestSuite('ProductSupplierTest');
		$ts->addTestSuite('ProductTest');
		$ts->addTestSuite('BonusTest');
		$ts->addTestSuite('LotTest');
		$ts->addTestSuite('InventoryTest');
		$ts->addTestSuite('ChangePriceLogTest');
		$ts->addTestSuite('ProductSearchTest');
		$ts->addTestSuite('ManufacturerProductListTest');
		$ts->addTestSuite('KardexTest');
		$ts->addTestSuite('ProductBonusListTest');
		$ts->addTestSuite('ExpiredLotListTest');
		$ts->addTestSuite('NearExpirationLotListTest');
		$ts->addTestSuite('InactiveProductListTest');
		$ts->addTestSuite('SupplierProductListTest');
		$ts->addTestSuite('ReserveListTest');
		$ts->addTestSuite('ProductDistinctListTest');
		$ts->addTestSuite('ManufacturerDistinctListTest');
		$ts->addTestSuite('InStockListTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>