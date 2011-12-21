DELIMITER $$

ALTER TABLE bonus ADD COLUMN user_account_username varchar(10) collate utf8_unicode_ci NOT NULL AFTER bonus_id$$

ALTER TABLE company MODIFY corporate_name varchar(100) collate utf8_unicode_ci NOT NULL$$

ALTER TABLE company ADD COLUMN warehouse_name varchar(20) collate utf8_unicode_ci NOT NULL AFTER address$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `bonus_created_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT COUNT(*) FROM bonus WHERE created_date BETWEEN inFirstDate AND inLastDate;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `bonus_created_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.bar_code, man.name AS manufacturer, pro.name, bon.quantity, bon.percentage,

         DATE_FORMAT(bon.created_date, '%d/%m/%Y') AS created_date, DATE_FORMAT(bon.expiration_date, '%d/%m/%Y') AS expiration_date, 

         bon.user_account_username AS username FROM bonus bon

       INNER JOIN product pro ON bon.product_id = pro.product_id

       INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE created_date BETWEEN ? AND ? ORDER BY bonus_id LIMIT ?, ?";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4;
  
END$$

DROP PROCEDURE IF EXISTS `bonus_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `bonus_get`(IN inBonusId INT)
BEGIN

  SELECT user_account_username, product_id, quantity, percentage, DATE_FORMAT(created_date, '%d/%m/%Y') AS created_date,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date FROM bonus

    WHERE bonus_id = inBonusId;

END$$

DROP PROCEDURE IF EXISTS `bonus_insert`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `bonus_insert`(IN inUserName VARCHAR(10), IN inProductId INT, IN inQuantity INT, IN inPercentage DECIMAL(4, 2), IN inCreatedDate DATE,

  IN inExpirationDate DATE)
BEGIN

  INSERT INTO bonus (user_account_username, product_id, quantity, percentage, created_date, expiration_date)

    VALUES (inUserName, inProductId, inQuantity, inPercentage, inCreatedDate, inExpirationDate);

END$$

DROP PROCEDURE IF EXISTS `cancel_cash_document_log_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `cancel_cash_document_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT SUM(count_rows) FROM

       (SELECT COUNT(*) AS count_rows FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id WHERE inv_can.date BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM deposit dep INNER JOIN deposit_cancelled dep_can ON dep.deposit_id = dep_can.deposit_id

            WHERE dep_can.date BETWEEN inFirstDate AND inLastDate) AS documents_cancelled;

END$$

DROP PROCEDURE IF EXISTS `cancel_cash_document_log_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `cancel_cash_document_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT date, cancelled_date, username, document, number, total FROM

       (SELECT inv_can.date, DATE_FORMAT(inv_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, inv_can.user_account_username AS username, 'Factura' AS document,

            CONCAT(cor.serial_number, '-', inv.number) AS number, inv.total FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id WHERE inv_can.date BETWEEN ? AND ?

        UNION

        SELECT dep_can.date, DATE_FORMAT(dep_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, dep_can.user_account_username AS username, 'Deposito' AS document,

            dep.deposit_id AS number, dep.total FROM deposit dep INNER JOIN deposit_cancelled dep_can ON dep.deposit_id = dep_can.deposit_id

            WHERE dep_can.date BETWEEN ? AND ?) AS documents_cancelled ORDER BY date

      LIMIT ?, ?";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inStartItem;

  SET @p6 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6;

END$$

DROP PROCEDURE IF EXISTS `cancel_document_log_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `cancel_document_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT SUM(count_rows) FROM

       (SELECT COUNT(*) AS count_rows FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id WHERE inv_can.date BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM receipt rec INNER JOIN receipt_cancelled rec_can ON rec.receipt_id = rec_can.receipt_id

            WHERE rec_can.date BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM purchase_return pur INNER JOIN purchase_return_cancelled pur_can

            ON pur.purchase_return_id = pur_can.purchase_return_id WHERE pur_can.date BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM shipment shi INNER JOIN shipment_cancelled shi_can ON shi.shipment_id = shi_can.shipment_id

            WHERE shi_can.date BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM entry_adjustment ent INNER JOIN entry_adjustment_cancelled ent_can ON

            ent.entry_adjustment_id = ent_can.entry_adjustment_id WHERE ent_can.date BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_cancelled wit_can

            ON wit.withdraw_adjustment_id = wit_can.withdraw_adjustment_id WHERE wit_can.date BETWEEN inFirstDate AND inLastDate) AS documents_cancelled;

END$$

DROP PROCEDURE IF EXISTS `cancel_document_log_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `cancel_document_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT date, cancelled_date, username, document, number, total FROM

       (SELECT inv_can.date, DATE_FORMAT(inv_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, inv_can.user_account_username AS username, 'Factura' AS document,

            CONCAT(cor.serial_number, '-', inv.number) AS number, inv.total FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id WHERE inv_can.date BETWEEN ? AND ?

        UNION

        SELECT rec_can.date, DATE_FORMAT(rec_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, rec_can.user_account_username AS username, 'Recibo' AS document,

            rec.receipt_id AS number, rec.total FROM receipt rec INNER JOIN receipt_cancelled rec_can ON rec.receipt_id = rec_can.receipt_id

            WHERE rec_can.date BETWEEN ? AND ?

        UNION

        SELECT pur_can.date, DATE_FORMAT(pur_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, pur_can.user_account_username AS username, 'Devolucion' AS document,

            pur.purchase_return_id AS number, pur.total FROM purchase_return pur INNER JOIN purchase_return_cancelled pur_can

            ON pur.purchase_return_id = pur_can.purchase_return_id WHERE pur_can.date BETWEEN ? AND ?

        UNION

        SELECT shi_can.date, DATE_FORMAT(shi_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, shi_can.user_account_username AS username, 'Envio' AS document,

            shi.shipment_id AS number, shi.total FROM shipment shi INNER JOIN shipment_cancelled shi_can ON shi.shipment_id = shi_can.shipment_id

            WHERE shi_can.date BETWEEN ? AND ?

        UNION

        SELECT ent_can.date, DATE_FORMAT(ent_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, ent_can.user_account_username AS username, 'Vale Entrada' AS document,

            ent.entry_adjustment_id AS number, ent.total FROM entry_adjustment ent INNER JOIN entry_adjustment_cancelled ent_can ON

            ent.entry_adjustment_id = ent_can.entry_adjustment_id WHERE ent_can.date BETWEEN ? AND ?

        UNION

        SELECT wit_can.date, DATE_FORMAT(wit_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, wit_can.user_account_username AS username, 'Vale Salida' AS document,

            wit.withdraw_adjustment_id AS number, wit.total FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_cancelled wit_can

            ON wit.withdraw_adjustment_id = wit_can.withdraw_adjustment_id WHERE wit_can.date BETWEEN ? AND ?) AS documents_cancelled ORDER BY date

      LIMIT ?, ?";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inFirstDate;

  SET @p6 = inLastDate;

  SET @p7 = inFirstDate;

  SET @p8 = inLastDate;

  SET @p9 = inFirstDate;

  SET @p10 = inLastDate;

  SET @p11 = inFirstDate;

  SET @p12 = inLastDate;

  SET @p13 = inStartItem;

  SET @p14 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6, @p7, @p8, @p9, @p10, @p11, @p12, @p13, @p14;

END$$

DROP PROCEDURE IF EXISTS `change_price_log_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `change_price_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT COUNT(*) FROM change_price_log

    WHERE date BETWEEN inFirstDate AND inLastDate;

END$$

DROP PROCEDURE IF EXISTS `change_price_log_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `change_price_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT DATE_FORMAT(log.date, '%d/%m/%Y %H:%i:%s') AS logged_date, log.user_account_username AS username, pro.bar_code, man.name AS

         manufacturer, pro.name, log.last_price, log.new_price FROM change_price_log log INNER JOIN product pro ON log.product_id = pro.product_id

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE date BETWEEN ? AND ?

     ORDER BY log.entry_id

     LIMIT ?, ?";



  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `company_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `company_get`()
BEGIN

  SELECT nit, name, corporate_name, telephone, address, warehouse_name FROM company;

END$$

DROP PROCEDURE IF EXISTS `company_update`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `company_update`(IN inNit VARCHAR(10), IN inName VARCHAR(50), IN inCorporateName VARCHAR(80),
  IN inTelephone VARCHAR(50), IN inAddress VARCHAR(100), IN inWarehouseName VARCHAR(20))
BEGIN

  UPDATE company

    SET nit = inNit, name = inName, corporate_name = inCorporateName, telephone = inTelephone, address = inAddress,

      warehouse_name = inWareHouseName;

END$$

DROP PROCEDURE IF EXISTS `comparison_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `comparison_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM comparison

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `comparison_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `comparison_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT comparison_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM comparison

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `count_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `count_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM count

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `count_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `count_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT count_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM count

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `deposit_by_working_day_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `deposit_by_working_day_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM deposit dep

      INNER JOIN cash_register cr ON dep.cash_register_id = cr.cash_register_id

    WHERE working_day BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `deposit_by_working_day_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `deposit_by_working_day_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT deposit_id AS id, DATE_FORMAT(working_day, '%d/%m/%Y') AS working_day FROM deposit dep

      INNER JOIN cash_register cr ON dep.cash_register_id = cr.cash_register_id

      WHERE working_day BETWEEN ? AND ?

      ORDER BY deposit_id

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `entry_adjustment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM entry_adjustment

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `entry_adjustment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT entry_adjustment_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM entry_adjustment

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `invoice_by_working_day_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `invoice_by_working_day_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

    WHERE working_day BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `invoice_by_working_day_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `invoice_by_working_day_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT invoice_id, serial_number, number, DATE_FORMAT(working_day, '%d/%m/%Y') AS working_day FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

      WHERE working_day BETWEEN ? AND ?

      ORDER BY invoice_id

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `invoice_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `invoice_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM invoice

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `invoice_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `invoice_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT invoice_id, serial_number, number, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM invoice inv

      INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `invoice_transaction_log_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `invoice_transaction_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT COUNT(*) FROM invoice_transaction_log WHERE date BETWEEN inFirstDate AND inLastDate;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_purchases_stadistics_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `manufacturer_purchases_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (

       SELECT IFNULL(SUM(rec.quantity), 0) AS purchases FROM 

         (SELECT product_id, name, manufacturer FROM (SELECT pro.product_id, man.name AS manufacturer, pro.name FROM product pro INNER JOIN 

          manufacturer man ON pro.manufacturer_id = man.manufacturer_id

          WHERE pro.deactivated = 0) AS product_sort WHERE manufacturer BETWEEN ? AND ?) AS pro

       LEFT JOIN

         (SELECT pro.product_id, rec_lot.quantity FROM product pro INNER JOIN lot ON pro.product_id = lot.product_id INNER JOIN receipt_lot 

          rec_lot ON lot.lot_id = rec_lot.lot_id INNER JOIN receipt rec ON rec_lot.receipt_id = rec.receipt_id WHERE rec.status = 1 AND 

          rec.date >= ? AND rec.date < ?) AS rec

       ON pro.product_id = rec.product_id GROUP BY pro.product_id ORDER BY pro.manufacturer, pro.name

     ) AS purchases_stadistics LIMIT ?, ?";
    
  SET @p1 = inFirst;

  SET @p2 = inLast;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inStartItem;

  SET @p6 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_sales_stadistics_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `manufacturer_sales_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (

       SELECT IFNULL(SUM(inv.quantity), 0) AS sales FROM 

         (SELECT product_id, name, manufacturer FROM (SELECT pro.product_id, man.name AS manufacturer, pro.name FROM product pro INNER JOIN 

          manufacturer man ON pro.manufacturer_id = man.manufacturer_id

          WHERE pro.deactivated = 0) AS product_sort WHERE manufacturer BETWEEN ? AND ?) AS pro

       LEFT JOIN

         (SELECT pro.product_id, inv_lot.quantity FROM product pro INNER JOIN lot ON pro.product_id = lot.product_id INNER JOIN invoice_lot 

          inv_lot ON lot.lot_id = inv_lot.lot_id INNER JOIN invoice inv ON inv_lot.invoice_id = inv.invoice_id WHERE inv.status = 1 AND 

          inv.date >= ? AND inv.date < ?) AS inv

       ON pro.product_id = inv.product_id GROUP BY pro.product_id ORDER BY pro.manufacturer, pro.name

     ) AS sales_stadistics LIMIT ?, ?";
    
  SET @p1 = inFirst;

  SET @p2 = inLast;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inStartItem;

  SET @p6 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6;

END$$

DROP PROCEDURE IF EXISTS `product_purchases_stadistics_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `product_purchases_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (

       SELECT IFNULL(SUM(rec.quantity), 0) AS purchases FROM 

         (SELECT product_id, name, manufacturer FROM (SELECT pro.product_id, man.name AS manufacturer, pro.name FROM product pro INNER JOIN 

          manufacturer man ON pro.manufacturer_id = man.manufacturer_id

          WHERE pro.deactivated = 0) AS product_sort WHERE name BETWEEN ? AND ?) AS pro

       LEFT JOIN

         (SELECT pro.product_id, rec_lot.quantity FROM product pro INNER JOIN lot ON pro.product_id = lot.product_id INNER JOIN receipt_lot 

          rec_lot ON lot.lot_id = rec_lot.lot_id INNER JOIN receipt rec ON rec_lot.receipt_id = rec.receipt_id WHERE rec.status = 1 AND 

          rec.date >= ? AND rec.date < ?) AS rec

       ON pro.product_id = rec.product_id GROUP BY pro.product_id ORDER BY pro.name, pro.manufacturer

     ) AS purchases_stadistics LIMIT ?, ?";
    
  SET @p1 = inFirst;

  SET @p2 = inLast;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inStartItem;

  SET @p6 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6;

END$$

DROP PROCEDURE IF EXISTS `product_sales_stadistics_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `product_sales_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (

       SELECT IFNULL(SUM(inv.quantity), 0) AS sales FROM 

         (SELECT product_id, name, manufacturer FROM (SELECT pro.product_id, man.name AS manufacturer, pro.name FROM product pro INNER JOIN 

          manufacturer man ON pro.manufacturer_id = man.manufacturer_id

          WHERE pro.deactivated = 0) AS product_sort WHERE name BETWEEN ? AND ?) AS pro

       LEFT JOIN

         (SELECT pro.product_id, inv_lot.quantity FROM product pro INNER JOIN lot ON pro.product_id = lot.product_id INNER JOIN invoice_lot 

          inv_lot ON lot.lot_id = inv_lot.lot_id INNER JOIN invoice inv ON inv_lot.invoice_id = inv.invoice_id WHERE inv.status = 1 AND 

          inv.date >= ? AND inv.date < ?) AS inv

       ON pro.product_id = inv.product_id GROUP BY pro.product_id ORDER BY pro.name, pro.manufacturer

     ) AS sales_stadistics LIMIT ?, ?";
    
  SET @p1 = inFirst;

  SET @p2 = inLast;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inStartItem;

  SET @p6 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `purchases_summary_product_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT COUNT(*) FROM (

    SELECT 1 FROM receipt rec

        INNER JOIN receipt_lot rec_lot ON rec.receipt_id = rec_lot.receipt_id

        INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

        INNER JOIN product pro ON lot.product_id = pro.product_id

    WHERE rec.status = 1 AND rec.date BETWEEN inFirstDate AND inLastDate GROUP BY pro.product_id) AS purchases_summary;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `purchases_summary_product_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (

       SELECT @rank := @rank + 1 AS rank, pro.bar_code, man.name AS manufacturer, pro.name AS name, pro.price AS actual_price,

           AVG(rec_lot.price) AS avg_price,  SUM(rec_lot.quantity) AS quantity, SUM(rec_lot.quantity * rec_lot.price) AS total FROM receipt rec

         INNER JOIN receipt_lot rec_lot ON rec.receipt_id = rec_lot.receipt_id

         INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

         INNER JOIN product pro ON lot.product_id = pro.product_id

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

       WHERE rec.status = 1 AND rec.date BETWEEN ? AND ? GROUP BY pro.product_id ORDER BY quantity DESC, name) AS purchases_summary

  LIMIT ?, ?;";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `purchases_summary_total_get`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT SUM(total) FROM receipt WHERE date BETWEEN inFirstDate AND inLastDate AND status = 1;

END$$

DROP PROCEDURE IF EXISTS `purchase_return_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `purchase_return_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM purchase_return

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `purchase_return_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `purchase_return_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT purchase_return_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM purchase_return

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `receipt_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `receipt_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM receipt

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `receipt_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `receipt_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT receipt_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM receipt

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `resolution_log_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `resolution_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT COUNT(*) FROM resolution_log WHERE created_date BETWEEN inFirstDate AND inLastDate;

END$$

DROP PROCEDURE IF EXISTS `sales_ledger_sum_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_ledger_sum_get`(IN inCorrelativeId INT, IN inFirstNumber BIGINT, IN inLastNumber BIGINT)
BEGIN

  SELECT IFNULL(SUM(inv.total - (inv.total * (IFNULL(dis.percentage, 0) / 100))), 0) AS total FROM invoice inv LEFT JOIN discount dis
      ON inv.invoice_id = dis.invoice_id
    WHERE inv.correlative_id = inCorrelativeId AND inv.number BETWEEN inFirstNumber AND inLastNumber AND inv.status = 1;

END$$

DROP PROCEDURE IF EXISTS `sales_ranking_count`$$

DROP PROCEDURE IF EXISTS `sales_ranking_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_ranking_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (SELECT @rank := @rank + 1 AS rank, pro.bar_code, man.name AS manufacturer, pro.name AS name,

         SUM(inv_lot.quantity) AS quantity

         FROM invoice inv INNER JOIN invoice_lot inv_lot

         ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id INNER JOIN product pro ON

         lot.product_id = pro.product_id INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         WHERE inv.status = 1 AND inv.date BETWEEN ? AND ? GROUP BY pro.product_id ORDER BY quantity DESC, name) AS sales_ranking

      LIMIT ?, ?";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_ranking_summary_product_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

       SELECT COUNT(*) FROM (SELECT 1 FROM invoice inv INNER JOIN invoice_lot inv_lot

         ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id INNER JOIN product pro ON

         lot.product_id = pro.product_id WHERE inv.status = 1 AND

       inv.date BETWEEN inFirstDate AND inLastDate GROUP BY pro.product_id) AS sales_ranking;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_summary_discount_total_get`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT IFNULL(SUM(inv.total * (dis.percentage / 100)), 0) FROM invoice inv INNER JOIN discount dis

    ON inv.invoice_id = dis.invoice_id WHERE inv.date BETWEEN inFirstDate AND inLastDate AND inv.status = 1;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_summary_product_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (

  SELECT @rank := @rank + 1 AS rank, bar_code, manufacturer, name, actual_price, avg_price, quantity, subtotal, IFNULL(bonus_total, 0) AS 

      bonus_total, subtotal + IFNULL(bonus_total, 0) AS total FROM

    (SELECT pro.bar_code, man.name AS manufacturer, pro.name AS name, pro.product_id, pro.price AS actual_price,

         AVG(inv_lot.price) AS avg_price,  SUM(inv_lot.quantity) AS quantity, SUM(inv_lot.quantity * inv_lot.price) AS subtotal

         FROM invoice inv

       INNER JOIN invoice_lot inv_lot ON inv.invoice_id = inv_lot.invoice_id

       INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

       INNER JOIN product pro ON lot.product_id = pro.product_id

       INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE inv.status = 1 AND inv.date BETWEEN ? AND ? GROUP BY pro.product_id) AS pro_sum

  LEFT JOIN

    (SELECT bon.product_id, SUM(IFNULL(inv_bon.price, 0)) AS bonus_total FROM invoice_bonus inv_bon

       INNER JOIN bonus bon ON inv_bon.bonus_id = bon.bonus_id

       INNER JOIN invoice inv ON inv_bon.invoice_id = inv.invoice_id

     WHERE inv.status = 1 AND inv.date BETWEEN ? AND ? GROUP BY bon.product_id) AS bon_sum

  ON pro_sum.product_id = bon_sum.product_id ORDER BY quantity DESC, name

) AS sales_summary_product LIMIT ?, ?;";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inFirstDate;

  SET @p4 = inLastDate;

  SET @p5 = inStartItem;

  SET @p6 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_summary_product_subtotal_get`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT SUM(subtotal + IFNULL(bonus_total, 0)) FROM

    (SELECT pro.product_id, SUM(inv_lot.quantity * inv_lot.price) AS subtotal

         FROM invoice inv

       INNER JOIN invoice_lot inv_lot ON inv.invoice_id = inv_lot.invoice_id

       INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

       INNER JOIN product pro ON lot.product_id = pro.product_id

     WHERE inv.status = 1 AND inv.date BETWEEN inFirstDate AND inLastDate GROUP BY pro.product_id) AS pro_sum

  LEFT JOIN

    (SELECT bon.product_id, SUM(IFNULL(inv_bon.price, 0)) AS bonus_total FROM invoice_bonus inv_bon

       INNER JOIN bonus bon ON inv_bon.bonus_id = bon.bonus_id

       INNER JOIN invoice inv ON inv_bon.invoice_id = inv.invoice_id

     WHERE inv.status = 1 AND inv.date BETWEEN inFirstDate AND inLastDate GROUP BY bon.product_id) AS bon_sum

  ON pro_sum.product_id = bon_sum.product_id;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_summary_total_get`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT SUM(inv.total - (inv.total * (IFNULL(dis.percentage, 0) / 100))) FROM invoice inv LEFT JOIN discount dis

    ON inv.invoice_id = dis.invoice_id WHERE inv.date BETWEEN inFirstDate AND inLastDate AND inv.status = 1;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_summary_user_account_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

       SELECT COUNT(*) FROM (SELECT 1 FROM invoice inv INNER JOIN user_account use_acc ON inv.user_account_username = use_acc.user_account_username

         WHERE inv.status = 1 AND inv.date BETWEEN inFirstDate AND inLastDate GROUP BY inv.user_account_username) AS sales_summary;

END$$

CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `sales_summary_user_account_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (SELECT @rank := @rank + 1 AS rank, inv.user_account_username AS username,

         CONCAT(use_acc.first_name, ' ', use_acc.last_name) AS name,

         SUM(inv.total) AS subtotal, SUM(inv.total * (IFNULL(dis.percentage, 0) / 100)) AS discount_total,

         SUM(inv.total - (inv.total * (IFNULL(dis.percentage, 0) / 100))) AS total FROM invoice inv

       INNER JOIN user_account use_acc ON inv.user_account_username = use_acc.user_account_username 

       LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

     WHERE inv.date BETWEEN ? AND ? AND inv.status = 1 GROUP BY inv.user_account_username ORDER BY total DESC, username) AS sales_summary

      LIMIT ?, ?";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `shipment_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `shipment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM shipment

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `shipment_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `shipment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT shipment_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM shipment

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_search_count`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `withdraw_adjustment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM withdraw_adjustment

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_search_get`$$
CREATE DEFINER=`@db_user@`@`localhost` PROCEDURE `withdraw_adjustment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT withdraw_adjustment_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM withdraw_adjustment

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$
