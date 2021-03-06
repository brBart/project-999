<?php
require_once('../../config/config.php');

define('PHPUnit_MAIN_METHOD', 'AppTests::main');

require_once('PHPUnit/Framework/TestSuite.php');
require_once('PHPUnit/TextUI/TestRunner.php');

require_once('various_test.php');

class AppTests{
	public static function main(){
		$ts = new PHPUnit_Framework_TestSuite('User Classes');
		$ts->addTestSuite('CompanyDAMTest');
		$ts->addTestSuite('ChangePriceListDAMTest');
		$ts->addTestSuite('DiscountListDAMTest');
		$ts->addTestSuite('CancelDocumentListDAMTest');
		$ts->addTestSuite('CancelCashDocumentListDAMTest');
		$ts->addTestSuite('SalesRankingListDAMTest');
		$ts->addTestSuite('SalesAndPurchasesStadisticsListDAMTest');
		$ts->addTestSuite('ClosingEventDAMTest');
		$ts->addTestSuite('BackupEventDAMTest');
		$ts->addTestSuite('SalesLedgerDAMTest');
		$ts->addTestSuite('InvoiceTransactionListDAMTest');
		$ts->addTestSuite('ResolutionListDAMTest');
		$ts->addTestSuite('SalesSummaryListDAMTest');
		$ts->addTestSuite('PurchasesSummaryListDAMTest');
		$ts->addTestSuite('BonusListDAMTest');
		PHPUnit_TextUI_TestRunner::run($ts);
	}
}

Apptests::main();
?>