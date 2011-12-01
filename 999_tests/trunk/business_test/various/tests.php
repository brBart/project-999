<?php
define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('various_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('ClosingEventTest');
		$ts->addTestSuite('BackupEventTest');
		$ts->addTestSuite('CompanyTest');
		$ts->addTestSuite('ChangePriceListTest');
		$ts->addTestSuite('DiscountListTest');
		$ts->addTestSuite('CancelDocumentListTest');
		$ts->addTestSuite('CancelCashDocumentListTest');
		$ts->addTestSuite('SalesRankingListTest');
		$ts->addTestSuite('SalesAndPurchasesStadisticsListTest');
		$ts->addTestSuite('SalesLedgerTest');
		$ts->addTestSuite('InvoiceTransactionListTest');
		$ts->addTestSuite('ResolutionListTest');
		$ts->addTestSuite('SalesSummaryListTest');
		$ts->addTestSuite('PurchasesSummaryListTest');
		$ts->addTestSuite('BonusCreatedListTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>