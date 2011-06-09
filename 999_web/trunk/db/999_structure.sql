-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-06-2011 a las 12:02:56
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `999_store`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `access_management`
--
DROP VIEW IF EXISTS `access_management`;
CREATE TABLE IF NOT EXISTS `access_management` (
`role` varchar(50)
,`subject` varchar(50)
,`action` varchar(50)
,`value` tinyint(1)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `action_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `bank_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_account`
--

DROP TABLE IF EXISTS `bank_account`;
CREATE TABLE IF NOT EXISTS `bank_account` (
  `bank_account_number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `bank_id` int(11) NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`bank_account_number`),
  KEY `idx_bank_account_bank_id` (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonus`
--

DROP TABLE IF EXISTS `bonus`;
CREATE TABLE IF NOT EXISTS `bonus` (
  `bonus_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `percentage` decimal(4,2) NOT NULL,
  `created_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY  (`bonus_id`),
  KEY `idx_bonus_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch`
--

DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `branch_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(50) collate utf8_unicode_ci NOT NULL,
  `address` varchar(100) collate utf8_unicode_ci NOT NULL,
  `email` varchar(50) collate utf8_unicode_ci default NULL,
  `contact` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_receipt`
--

DROP TABLE IF EXISTS `cash_receipt`;
CREATE TABLE IF NOT EXISTS `cash_receipt` (
  `cash_receipt_id` int(11) NOT NULL,
  `change_amount` decimal(13,2) NOT NULL,
  `cash` decimal(13,2) NOT NULL,
  `total_vouchers` decimal(13,2) NOT NULL,
  `reserved` decimal(13,2) NOT NULL default '0.00',
  `deposited` decimal(13,2) NOT NULL default '0.00',
  PRIMARY KEY  (`cash_receipt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_register`
--

DROP TABLE IF EXISTS `cash_register`;
CREATE TABLE IF NOT EXISTS `cash_register` (
  `cash_register_id` int(11) NOT NULL auto_increment,
  `working_day` date NOT NULL,
  `shift_id` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`cash_register_id`),
  UNIQUE KEY `unique_working_day_shift_id` (`working_day`,`shift_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `change_price_log`
--

DROP TABLE IF EXISTS `change_price_log`;
CREATE TABLE IF NOT EXISTS `change_price_log` (
  `entry_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `last_price` decimal(6,2) NOT NULL,
  `new_price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`entry_id`),
  KEY `idx_change_price_log_user_account_username` (`user_account_username`),
  KEY `idx_change_price_log_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `nit` varchar(10) collate utf8_unicode_ci NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `corporate_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(50) collate utf8_unicode_ci NOT NULL,
  `address` varchar(100) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comparison`
--

DROP TABLE IF EXISTS `comparison`;
CREATE TABLE IF NOT EXISTS `comparison` (
  `comparison_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(100) collate utf8_unicode_ci NOT NULL,
  `general` tinyint(1) NOT NULL,
  `physical_total` int(11) NOT NULL,
  `system_total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`comparison_id`),
  KEY `idx_comparison_user_account_username` (`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comparison_product`
--

DROP TABLE IF EXISTS `comparison_product`;
CREATE TABLE IF NOT EXISTS `comparison_product` (
  `comparison_product_id` int(11) NOT NULL auto_increment,
  `comparison_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `physical` int(11) NOT NULL,
  `system` int(11) NOT NULL,
  PRIMARY KEY  (`comparison_product_id`),
  UNIQUE KEY `unique_comparison_id_product_id` (`comparison_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correlative`
--

DROP TABLE IF EXISTS `correlative`;
CREATE TABLE IF NOT EXISTS `correlative` (
  `correlative_id` int(11) NOT NULL auto_increment,
  `serial_number` varchar(10) collate utf8_unicode_ci NOT NULL,
  `resolution_number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `resolution_date` date NOT NULL,
  `regime` varchar(50) collate utf8_unicode_ci NOT NULL,
  `initial_number` bigint(20) NOT NULL,
  `final_number` bigint(20) NOT NULL,
  `current` bigint(20) NOT NULL default '0',
  `is_default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`correlative_id`),
  UNIQUE KEY `unique_serial_number_initial_number_final_number` (`serial_number`,`initial_number`,`final_number`),
  UNIQUE KEY `unique_resolution_number` (`resolution_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `count`
--

DROP TABLE IF EXISTS `count`;
CREATE TABLE IF NOT EXISTS `count` (
  `count_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(100) collate utf8_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY  (`count_id`),
  KEY `idx_count_user_account_username` (`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `count_product`
--

DROP TABLE IF EXISTS `count_product`;
CREATE TABLE IF NOT EXISTS `count_product` (
  `count_product_id` int(11) NOT NULL auto_increment,
  `count_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY  (`count_product_id`),
  UNIQUE KEY `unique_count_id_product_id` (`count_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`nit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposit`
--

DROP TABLE IF EXISTS `deposit`;
CREATE TABLE IF NOT EXISTS `deposit` (
  `deposit_id` int(11) NOT NULL auto_increment,
  `bank_account_number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `cash_register_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `total` decimal(13,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`deposit_id`),
  KEY `idx_deposit_bank_account_number` (`bank_account_number`),
  KEY `idx_deposit_cash_register_id` (`cash_register_id`),
  KEY `idx_deposit_user_account_username` (`user_account_username`),
  KEY `idx_deposit_date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposit_cancelled`
--

DROP TABLE IF EXISTS `deposit_cancelled`;
CREATE TABLE IF NOT EXISTS `deposit_cancelled` (
  `deposit_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`deposit_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposit_cash_receipt`
--

DROP TABLE IF EXISTS `deposit_cash_receipt`;
CREATE TABLE IF NOT EXISTS `deposit_cash_receipt` (
  `deposit_cash_receipt_id` int(11) NOT NULL auto_increment,
  `deposit_id` int(11) NOT NULL,
  `cash_receipt_id` int(11) NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  PRIMARY KEY  (`deposit_cash_receipt_id`),
  UNIQUE KEY `unique_deposit_id_cash_receipt_id` (`deposit_id`,`cash_receipt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE IF NOT EXISTS `discount` (
  `invoice_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `percentage` decimal(4,2) NOT NULL,
  PRIMARY KEY  (`invoice_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_adjustment`
--

DROP TABLE IF EXISTS `entry_adjustment`;
CREATE TABLE IF NOT EXISTS `entry_adjustment` (
  `entry_adjustment_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(100) collate utf8_unicode_ci NOT NULL,
  `total` decimal(13,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`entry_adjustment_id`),
  KEY `idx_entry_adjustment_user_account_username` (`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_adjustment_cancelled`
--

DROP TABLE IF EXISTS `entry_adjustment_cancelled`;
CREATE TABLE IF NOT EXISTS `entry_adjustment_cancelled` (
  `entry_adjustment_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`entry_adjustment_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_adjustment_lot`
--

DROP TABLE IF EXISTS `entry_adjustment_lot`;
CREATE TABLE IF NOT EXISTS `entry_adjustment_lot` (
  `entry_adjustment_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`entry_adjustment_id`,`lot_id`),
  UNIQUE KEY `unique_entry_adjustment_id_number` (`entry_adjustment_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice`
--

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE IF NOT EXISTS `invoice` (
  `invoice_id` int(11) NOT NULL auto_increment,
  `correlative_id` int(11) NOT NULL,
  `number` bigint(20) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci default NULL,
  `total` decimal(13,2) NOT NULL,
  `vat` decimal(4,2) NOT NULL,
  `cash_register_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`invoice_id`),
  UNIQUE KEY `unique_correlative_id_number` (`correlative_id`,`number`),
  KEY `idx_invoice_user_account_username` (`user_account_username`),
  KEY `idx_invoice_cash_register_id` (`cash_register_id`),
  KEY `idx_invoice_correlative_id` (`correlative_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_bonus`
--

DROP TABLE IF EXISTS `invoice_bonus`;
CREATE TABLE IF NOT EXISTS `invoice_bonus` (
  `invoice_id` int(11) NOT NULL,
  `bonus_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`invoice_id`,`bonus_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_cancelled`
--

DROP TABLE IF EXISTS `invoice_cancelled`;
CREATE TABLE IF NOT EXISTS `invoice_cancelled` (
  `invoice_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`invoice_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_lot`
--

DROP TABLE IF EXISTS `invoice_lot`;
CREATE TABLE IF NOT EXISTS `invoice_lot` (
  `invoice_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`invoice_id`,`lot_id`),
  UNIQUE KEY `unique_invoice_id_number` (`invoice_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_transaction_log`
--

DROP TABLE IF EXISTS `invoice_transaction_log`;
CREATE TABLE IF NOT EXISTS `invoice_transaction_log` (
  `entry_id` int(11) NOT NULL auto_increment,
  `serial_number` varchar(10) NOT NULL,
  `number` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `total` decimal(13,2) NOT NULL,
  `state` varchar(10) NOT NULL,
  PRIMARY KEY  (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lot`
--

DROP TABLE IF EXISTS `lot`;
CREATE TABLE IF NOT EXISTS `lot` (
  `lot_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `expiration_date` date default NULL,
  `price` decimal(6,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reserved` int(11) NOT NULL default '0',
  PRIMARY KEY  (`lot_id`),
  KEY `idx_lot_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manufacturer`
--

DROP TABLE IF EXISTS `manufacturer`;
CREATE TABLE IF NOT EXISTS `manufacturer` (
  `manufacturer_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`manufacturer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_card_brand`
--

DROP TABLE IF EXISTS `payment_card_brand`;
CREATE TABLE IF NOT EXISTS `payment_card_brand` (
  `payment_card_brand_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`payment_card_brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_card_type`
--

DROP TABLE IF EXISTS `payment_card_type`;
CREATE TABLE IF NOT EXISTS `payment_card_type` (
  `payment_card_type_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`payment_card_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL auto_increment,
  `bar_code` varchar(50) collate utf8_unicode_ci default NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci,
  `unit_of_measure_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `deactivated` tinyint(1) NOT NULL,
  `quantity` int(11) NOT NULL default '0',
  `reserved` int(11) NOT NULL default '0',
  `balance_forward` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`),
  UNIQUE KEY `unique_bar_code` (`bar_code`),
  KEY `idx_product_unit_of_measure_id` (`unit_of_measure_id`),
  KEY `idx_product_manufacturer_id` (`manufacturer_id`),
  KEY `idx_product_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_supplier`
--

DROP TABLE IF EXISTS `product_supplier`;
CREATE TABLE IF NOT EXISTS `product_supplier` (
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `sku` varchar(30) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`product_id`,`supplier_id`,`sku`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return`
--

DROP TABLE IF EXISTS `purchase_return`;
CREATE TABLE IF NOT EXISTS `purchase_return` (
  `purchase_return_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(100) collate utf8_unicode_ci NOT NULL,
  `contact` varchar(50) collate utf8_unicode_ci default NULL,
  `total` decimal(13,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`purchase_return_id`),
  KEY `idx_purchase_return_user_account_username` (`user_account_username`),
  KEY `idx_purchase_return_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return_cancelled`
--

DROP TABLE IF EXISTS `purchase_return_cancelled`;
CREATE TABLE IF NOT EXISTS `purchase_return_cancelled` (
  `purchase_return_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`purchase_return_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return_lot`
--

DROP TABLE IF EXISTS `purchase_return_lot`;
CREATE TABLE IF NOT EXISTS `purchase_return_lot` (
  `purchase_return_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`purchase_return_id`,`lot_id`),
  UNIQUE KEY `unique_purchase_return_id_number` (`purchase_return_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipt`
--

DROP TABLE IF EXISTS `receipt`;
CREATE TABLE IF NOT EXISTS `receipt` (
  `receipt_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `shipment_number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `total` decimal(13,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`receipt_id`),
  KEY `idx_receipt_user_account_username` (`user_account_username`),
  KEY `idx_receipt_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipt_cancelled`
--

DROP TABLE IF EXISTS `receipt_cancelled`;
CREATE TABLE IF NOT EXISTS `receipt_cancelled` (
  `receipt_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`receipt_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipt_lot`
--

DROP TABLE IF EXISTS `receipt_lot`;
CREATE TABLE IF NOT EXISTS `receipt_lot` (
  `receipt_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`receipt_id`,`lot_id`),
  UNIQUE KEY `unique_receipt_id_number` (`receipt_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserve`
--

DROP TABLE IF EXISTS `reserve`;
CREATE TABLE IF NOT EXISTS `reserve` (
  `reserve_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `lot_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY  (`reserve_id`),
  KEY `idx_reserve_user_account_username` (`user_account_username`),
  KEY `idx_reserve_lot_id` (`lot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resolution_log`
--

DROP TABLE IF EXISTS `resolution_log`;
CREATE TABLE IF NOT EXISTS `resolution_log` (
  `entry_id` int(11) NOT NULL auto_increment,
  `resolution_number` varchar(50) NOT NULL,
  `resolution_date` date NOT NULL,
  `serial_number` varchar(10) NOT NULL,
  `initial_number` bigint(20) NOT NULL,
  `final_number` bigint(20) NOT NULL,
  `created_date` date NOT NULL,
  `document_type` varchar(10) NOT NULL,
  PRIMARY KEY  (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_subject_action`
--

DROP TABLE IF EXISTS `role_subject_action`;
CREATE TABLE IF NOT EXISTS `role_subject_action` (
  `role_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `value` tinyint(1) NOT NULL,
  PRIMARY KEY  (`role_id`,`subject_id`,`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `root`
--

DROP TABLE IF EXISTS `root`;
CREATE TABLE IF NOT EXISTS `root` (
  `password` varchar(50) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shift`
--

DROP TABLE IF EXISTS `shift`;
CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int(11) NOT NULL auto_increment,
  `name` varchar(15) collate utf8_unicode_ci NOT NULL,
  `time_table` varchar(30) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`shift_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment`
--

DROP TABLE IF EXISTS `shipment`;
CREATE TABLE IF NOT EXISTS `shipment` (
  `shipment_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `contact` varchar(50) collate utf8_unicode_ci default NULL,
  `total` decimal(13,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`shipment_id`),
  KEY `idx_shipment_user_account_username` (`user_account_username`),
  KEY `idx_shipment_branch_id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_cancelled`
--

DROP TABLE IF EXISTS `shipment_cancelled`;
CREATE TABLE IF NOT EXISTS `shipment_cancelled` (
  `shipment_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`shipment_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_lot`
--

DROP TABLE IF EXISTS `shipment_lot`;
CREATE TABLE IF NOT EXISTS `shipment_lot` (
  `shipment_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`shipment_id`,`lot_id`),
  UNIQUE KEY `unique_shipment_id_number` (`shipment_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `supplier_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(50) collate utf8_unicode_ci NOT NULL,
  `address` varchar(100) collate utf8_unicode_ci NOT NULL,
  `email` varchar(50) collate utf8_unicode_ci default NULL,
  `contact` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unit_of_measure`
--

DROP TABLE IF EXISTS `unit_of_measure`;
CREATE TABLE IF NOT EXISTS `unit_of_measure` (
  `unit_of_measure_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`unit_of_measure_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_account`
--

DROP TABLE IF EXISTS `user_account`;
CREATE TABLE IF NOT EXISTS `user_account` (
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `password` varchar(50) collate utf8_unicode_ci NOT NULL,
  `deactivated` tinyint(1) NOT NULL,
  PRIMARY KEY  (`user_account_username`),
  KEY `idx_user_account_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vat`
--

DROP TABLE IF EXISTS `vat`;
CREATE TABLE IF NOT EXISTS `vat` (
  `percentage` decimal(4,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher`
--

DROP TABLE IF EXISTS `voucher`;
CREATE TABLE IF NOT EXISTS `voucher` (
  `voucher_id` int(11) NOT NULL auto_increment,
  `cash_receipt_id` int(11) NOT NULL,
  `transaction` varchar(50) collate utf8_unicode_ci NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `payment_card_number` int(11) NOT NULL,
  `payment_card_type_id` int(11) NOT NULL,
  `payment_card_brand_id` int(11) NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY  (`voucher_id`),
  UNIQUE KEY `unique_cash_receipt_id_transaction` (`cash_receipt_id`,`transaction`),
  KEY `idx_voucher_payment_card_type_id` (`payment_card_type_id`),
  KEY `idx_voucher_payment_card_brand_id` (`payment_card_brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdraw_adjustment`
--

DROP TABLE IF EXISTS `withdraw_adjustment`;
CREATE TABLE IF NOT EXISTS `withdraw_adjustment` (
  `withdraw_adjustment_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(100) collate utf8_unicode_ci NOT NULL,
  `total` decimal(13,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`withdraw_adjustment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdraw_adjustment_cancelled`
--

DROP TABLE IF EXISTS `withdraw_adjustment_cancelled`;
CREATE TABLE IF NOT EXISTS `withdraw_adjustment_cancelled` (
  `withdraw_adjustment_id` int(11) NOT NULL,
  `user_account_username` varchar(10) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`withdraw_adjustment_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdraw_adjustment_lot`
--

DROP TABLE IF EXISTS `withdraw_adjustment_lot`;
CREATE TABLE IF NOT EXISTS `withdraw_adjustment_lot` (
  `withdraw_adjustment_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  PRIMARY KEY  (`withdraw_adjustment_id`,`lot_id`),
  UNIQUE KEY `unique_withdraw_adjustment_id_number` (`withdraw_adjustment_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `working_day`
--

DROP TABLE IF EXISTS `working_day`;
CREATE TABLE IF NOT EXISTS `working_day` (
  `working_day` date NOT NULL,
  `open` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`working_day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura para la vista `access_management`
--
DROP TABLE IF EXISTS `access_management`;

CREATE ALGORITHM=UNDEFINED DEFINER=`999_user`@`localhost` SQL SECURITY DEFINER VIEW `999_store`.`access_management` AS select `rol`.`name` AS `role`,`sub`.`name` AS `subject`,`act`.`name` AS `action`,`rsa`.`value` AS `value` from (((`999_store`.`role_subject_action` `rsa` join `999_store`.`role` `rol` on((`rsa`.`role_id` = `rol`.`role_id`))) join `999_store`.`subject` `sub` on((`rsa`.`subject_id` = `sub`.`subject_id`))) join `999_store`.`action` `act` on((`rsa`.`action_id` = `act`.`action_id`)));

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `action_id_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `action_id_get`(IN inName VARCHAR(50))
BEGIN
  SELECT action_id FROM action
    WHERE name = inName;
END$$

DROP PROCEDURE IF EXISTS `bank_account_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_delete`(IN inBankAccountNumber VARCHAR(50))
BEGIN

  DELETE FROM bank_account

    WHERE bank_account_number = inBankAccountNumber;

END$$

DROP PROCEDURE IF EXISTS `bank_account_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_dependencies`(IN inBankAccountNumber VARCHAR(50))
BEGIN

  DECLARE bankAccountRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM deposit

    WHERE bank_account_number = inBankAccountNumber

    INTO bankAccountRowsCount;



  SET dependenciesCount = dependenciesCount + bankAccountRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `bank_account_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_exists`(IN inBankAccountNumber VARCHAR(50))
BEGIN

  SELECT COUNT(*) FROM bank_account

  WHERE bank_account_number = inBankAccountNumber;

END$$

DROP PROCEDURE IF EXISTS `bank_account_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_get`(IN inBankAccountNumber VARCHAR(50))
BEGIN

  SELECT bank_id, name FROM bank_account

    WHERE bank_account_number = inBankAccountNumber;

END$$

DROP PROCEDURE IF EXISTS `bank_account_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_insert`(IN inBankAccountNumber VARCHAR(50), IN inBankId INT, IN inName VARCHAR(50))
BEGIN

  INSERT INTO bank_account (bank_account_number, bank_id, name)

    VALUES (inBankAccountNumber, inBankId, inName);

END$$

DROP PROCEDURE IF EXISTS `bank_account_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_list_count`()
BEGIN

  SELECT COUNT(*) FROM bank_account;

END$$

DROP PROCEDURE IF EXISTS `bank_account_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT bank_account_number AS id, name FROM bank_account

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `bank_account_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_update`(IN inBankAccountNumber VARCHAR(50), IN inBankId INT, IN inName VARCHAR(50))
BEGIN

  UPDATE bank_account

    SET bank_id = inBankId, name = inName

    WHERE bank_account_number = inBankAccountNumber;

END$$

DROP PROCEDURE IF EXISTS `bank_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_delete`(IN inBankId INT)
BEGIN

  DELETE FROM bank

    WHERE bank_id = inBankId;

END$$

DROP PROCEDURE IF EXISTS `bank_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_dependencies`(IN inBankId INT)
BEGIN

  DECLARE bankRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM bank_account

    WHERE bank_id = inBankId

    INTO bankRowsCount;



  SET dependenciesCount = dependenciesCount + bankRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `bank_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_get`(IN inBankId INT)
BEGIN

  SELECT name FROM bank

    WHERE bank_id = inBankId;

END$$

DROP PROCEDURE IF EXISTS `bank_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_insert`(IN inName VARCHAR(50))
BEGIN

  INSERT INTO bank (name)

    VALUES (inName);

END$$

DROP PROCEDURE IF EXISTS `bank_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_list_count`()
BEGIN

  SELECT COUNT(*) FROM bank;

END$$

DROP PROCEDURE IF EXISTS `bank_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT bank_id AS id, name FROM bank

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `bank_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_update`(IN inBankId INT, IN inName VARCHAR(50))
BEGIN

  UPDATE bank

    SET name = inName

    WHERE bank_id = inBankId;

END$$

DROP PROCEDURE IF EXISTS `bonus_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_delete`(IN inBonusId INT)
BEGIN

  DELETE FROM bonus

    WHERE bonus_id = inBonusId;

END$$

DROP PROCEDURE IF EXISTS `bonus_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_dependencies`(IN inBonusId INT)
BEGIN

  DECLARE bonusRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM invoice_bonus

    WHERE bonus_id = inBonusId

    INTO bonusRowsCount;



  SET dependenciesCount = dependenciesCount + bonusRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `bonus_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_exists`(IN inProductId INT, IN inQuantity INT)
BEGIN

  SELECT COUNT(*) FROM bonus

    WHERE product_id = inProductId AND quantity = inQuantity AND expiration_date > CURDATE();

END$$

DROP PROCEDURE IF EXISTS `bonus_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_get`(IN inBonusId INT)
BEGIN

  SELECT product_id, quantity, percentage, DATE_FORMAT(created_date, '%d/%m/%Y') AS created_date,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date FROM bonus

    WHERE bonus_id = inBonusId;

END$$

DROP PROCEDURE IF EXISTS `bonus_id_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_id_get`(IN inProductId INT, IN inQuantity INT)
BEGIN

  SELECT bonus_id FROM bonus

    WHERE product_id = inProductId AND quantity <= inQuantity AND expiration_date > CURDATE()

  ORDER BY quantity DESC;

END$$

DROP PROCEDURE IF EXISTS `bonus_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_insert`(IN inProductId INT, IN inQuantity INT, IN inPercentage DECIMAL(4, 2), IN inCreatedDate DATE,

  IN inExpirationDate DATE)
BEGIN

  INSERT INTO bonus (product_id, quantity, percentage, created_date, expiration_date)

    VALUES (inProductId, inQuantity, inPercentage, inCreatedDate, inExpirationDate);

END$$

DROP PROCEDURE IF EXISTS `branch_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_delete`(IN inBranchId INT)
BEGIN

  DELETE FROM branch

    WHERE branch_id = inBranchId;

END$$

DROP PROCEDURE IF EXISTS `branch_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_dependencies`(IN inBranchId INT)
BEGIN

  DECLARE branchRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM shipment

    WHERE branch_id = inBranchId

    INTO branchRowsCount;



  SET dependenciesCount = dependenciesCount + branchRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `branch_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_get`(IN inBranchId INT)
BEGIN

  SELECT name, nit, telephone, address, email, contact FROM branch

    WHERE branch_id = inBranchId;

END$$

DROP PROCEDURE IF EXISTS `branch_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_insert`(IN inName VARCHAR(50), IN inNit VARCHAR(15), IN inTelephone VARCHAR(50),

  IN inAddress VARCHAR(100), IN inEmail VARCHAR(50), IN inContact VARCHAR(50))
BEGIN

  INSERT INTO branch (name, nit, telephone, address, email, contact)

    VALUES (inName, inNit, inTelephone, inAddress, inEmail, inContact);

END$$

DROP PROCEDURE IF EXISTS `branch_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_list_count`()
BEGIN

  SELECT COUNT(*) FROM branch;

END$$

DROP PROCEDURE IF EXISTS `branch_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT branch_id AS id, name FROM branch

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `branch_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_update`(IN inBranchId INT, IN inName VARCHAR(50),

  IN inNit VARCHAR(15), IN inTelephone VARCHAR(50), IN inAddress VARCHAR(100),

  IN inEmail VARCHAR(50), IN inContact VARCHAR(50))
BEGIN

  UPDATE branch

  SET name = inName, nit = inNit, telephone = inTelephone, address = inAddress,

    email = inEmail, contact = inContact

  WHERE branch_id = inBranchId;

END$$

DROP PROCEDURE IF EXISTS `cancel_cash_document_log_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cancel_cash_document_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT SUM(count_rows) FROM

       (SELECT COUNT(*) AS count_rows FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id WHERE CAST(inv_can.date AS DATE) BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM deposit dep INNER JOIN deposit_cancelled dep_can ON dep.deposit_id = dep_can.deposit_id

            WHERE CAST(dep_can.date AS DATE) BETWEEN inFirstDate AND inLastDate) AS documents_cancelled;

END$$

DROP PROCEDURE IF EXISTS `cancel_cash_document_log_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cancel_cash_document_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT date, cancelled_date, username, document, number, total FROM

       (SELECT inv_can.date, DATE_FORMAT(inv_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, inv_can.user_account_username AS username, 'Factura' AS document,

            CONCAT(cor.serial_number, '-', inv.number) AS number, inv.total FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id WHERE CAST(inv_can.date AS DATE) BETWEEN ? AND ?

        UNION

        SELECT dep_can.date, DATE_FORMAT(dep_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, dep_can.user_account_username AS username, 'Deposito' AS document,

            dep.deposit_id AS number, dep.total FROM deposit dep INNER JOIN deposit_cancelled dep_can ON dep.deposit_id = dep_can.deposit_id

            WHERE CAST(dep_can.date AS DATE) BETWEEN ? AND ?) AS documents_cancelled ORDER BY date

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
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cancel_document_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

    SELECT SUM(count_rows) FROM

       (SELECT COUNT(*) AS count_rows FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id WHERE CAST(inv_can.date AS DATE) BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM receipt rec INNER JOIN receipt_cancelled rec_can ON rec.receipt_id = rec_can.receipt_id

            WHERE CAST(rec_can.date AS DATE) BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM purchase_return pur INNER JOIN purchase_return_cancelled pur_can

            ON pur.purchase_return_id = pur_can.purchase_return_id WHERE CAST(pur_can.date AS DATE) BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM shipment shi INNER JOIN shipment_cancelled shi_can ON shi.shipment_id = shi_can.shipment_id

            WHERE CAST(shi_can.date AS DATE) BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM entry_adjustment ent INNER JOIN entry_adjustment_cancelled ent_can ON

            ent.entry_adjustment_id = ent_can.entry_adjustment_id WHERE CAST(ent_can.date AS DATE) BETWEEN inFirstDate AND inLastDate

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_cancelled wit_can

            ON wit.withdraw_adjustment_id = wit_can.withdraw_adjustment_id WHERE CAST(wit_can.date AS DATE) BETWEEN inFirstDate AND inLastDate) AS documents_cancelled;

END$$

DROP PROCEDURE IF EXISTS `cancel_document_log_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cancel_document_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT date, cancelled_date, username, document, number, total FROM

       (SELECT inv_can.date, DATE_FORMAT(inv_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, inv_can.user_account_username AS username, 'Factura' AS document,

            CONCAT(cor.serial_number, '-', inv.number) AS number, inv.total FROM invoice inv INNER JOIN invoice_cancelled inv_can

            ON inv.invoice_id = inv_can.invoice_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id WHERE CAST(inv_can.date AS DATE) BETWEEN ? AND ?

        UNION

        SELECT rec_can.date, DATE_FORMAT(rec_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, rec_can.user_account_username AS username, 'Recibo' AS document,

            rec.receipt_id AS number, rec.total FROM receipt rec INNER JOIN receipt_cancelled rec_can ON rec.receipt_id = rec_can.receipt_id

            WHERE CAST(rec_can.date AS DATE) BETWEEN ? AND ?

        UNION

        SELECT pur_can.date, DATE_FORMAT(pur_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, pur_can.user_account_username AS username, 'Devolucion' AS document,

            pur.purchase_return_id AS number, pur.total FROM purchase_return pur INNER JOIN purchase_return_cancelled pur_can

            ON pur.purchase_return_id = pur_can.purchase_return_id WHERE CAST(pur_can.date AS DATE) BETWEEN ? AND ?

        UNION

        SELECT shi_can.date, DATE_FORMAT(shi_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, shi_can.user_account_username AS username, 'Envio' AS document,

            shi.shipment_id AS number, shi.total FROM shipment shi INNER JOIN shipment_cancelled shi_can ON shi.shipment_id = shi_can.shipment_id

            WHERE CAST(shi_can.date AS DATE) BETWEEN ? AND ?

        UNION

        SELECT ent_can.date, DATE_FORMAT(ent_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, ent_can.user_account_username AS username, 'Vale Entrada' AS document,

            ent.entry_adjustment_id AS number, ent.total FROM entry_adjustment ent INNER JOIN entry_adjustment_cancelled ent_can ON

            ent.entry_adjustment_id = ent_can.entry_adjustment_id WHERE CAST(ent_can.date AS DATE) BETWEEN ? AND ?

        UNION

        SELECT wit_can.date, DATE_FORMAT(wit_can.date, '%d/%m/%Y %H:%i:%s') AS cancelled_date, wit_can.user_account_username AS username, 'Vale Salida' AS document,

            wit.withdraw_adjustment_id AS number, wit.total FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_cancelled wit_can

            ON wit.withdraw_adjustment_id = wit_can.withdraw_adjustment_id WHERE CAST(wit_can.date AS DATE) BETWEEN ? AND ?) AS documents_cancelled ORDER BY date

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

DROP PROCEDURE IF EXISTS `cash_receipt_available_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_available_list_get`(IN inCashRegisterId INT)
BEGIN

  SELECT cr.cash_receipt_id AS id, cor.serial_number, inv.number, cr.cash AS received_cash,

      (cr.cash - (cr.reserved + cr.deposited)) AS available_cash FROM cash_receipt cr

      INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

    WHERE inv.cash_register_id = inCashRegisterId AND (cr.cash - (cr.reserved + cr.deposited)) > 0 AND inv.status = 1;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_cash_available_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_cash_available_get`(IN inCashReceiptId INT)
BEGIN

  SELECT (cash - (reserved + deposited)) AS available FROM cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_cash_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_cash_get`(IN inCashReceiptId INT)
BEGIN

  SELECT cr.cash, cor.serial_number, inv.number FROM cash_receipt cr INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id

     INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_decrease_deposited`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_decrease_deposited`(IN inCashReceiptId INT, IN inAmount DECIMAL(13, 2))
BEGIN

  UPDATE cash_receipt

    SET deposited = deposited - inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_decrease_reserved`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_decrease_reserved`(IN inCashReceiptId INT, IN inAmount DECIMAL(13, 2))
BEGIN

  UPDATE cash_receipt

    SET reserved = reserved - inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_get`(IN inCashReceiptId INT)
BEGIN

  SELECT cash, change_amount, total_vouchers FROM cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_increase_deposited`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_increase_deposited`(IN inCashReceiptId INT, IN inAmount DECIMAL(13, 2))
BEGIN

  UPDATE cash_receipt

    SET deposited = deposited + inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_increase_reserved`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_increase_reserved`(IN inCashReceiptId INT, IN inAmount DECIMAL(13, 2))
BEGIN

  UPDATE cash_receipt

    SET reserved = reserved + inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `cash_receipt_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_insert`(IN inCashReceiptId INT, IN inChange DECIMAL(13, 2), IN inCash DECIMAL(13, 2),

  IN inTotalVouchers DECIMAL(13, 2))
BEGIN

  INSERT INTO cash_receipt (cash_receipt_id, change_amount, cash, total_vouchers)

    VALUES (inCashReceiptId, inChange, inCash, inTotalVouchers);

END$$

DROP PROCEDURE IF EXISTS `cash_register_close`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_close`(IN inCashRegisterId INT)
BEGIN

  UPDATE cash_register

    SET open = 0

    WHERE cash_register_id = inCashRegisterId;

END$$

DROP PROCEDURE IF EXISTS `cash_register_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_get`(IN inCashRegisterId INT)
BEGIN

  SELECT shift_id FROM cash_register

    WHERE cash_register_id = inCashRegisterId;

END$$

DROP PROCEDURE IF EXISTS `cash_register_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_insert`(IN inWorkingDay DATE, IN inShiftId INT)
BEGIN

  INSERT INTO cash_register (working_day, shift_id)

    VALUES (inWorkingDay, inShiftId);

END$$

DROP PROCEDURE IF EXISTS `cash_register_is_open`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_is_open`(IN inCashRegisterId INT)
BEGIN

  SELECT open FROM cash_register

    WHERE cash_register_id = inCashRegisterId;

END$$

DROP PROCEDURE IF EXISTS `cash_register_working_day_shift_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_working_day_shift_get`(IN inWorkingDay DATE, IN inShiftId INT)
BEGIN

  SELECT cash_register_id FROM cash_register

    WHERE working_day = inWorkingDay AND shift_id = inShiftId;

END$$

DROP PROCEDURE IF EXISTS `change_price_log_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `change_price_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT COUNT(*) FROM change_price_log

    WHERE CAST(date AS DATE) BETWEEN inFirstDate AND inLastDate;

END$$

DROP PROCEDURE IF EXISTS `change_price_log_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `change_price_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT DATE_FORMAT(log.date, '%d/%m/%Y %H:%i:%s') AS logged_date, log.user_account_username AS username, pro.bar_code, man.name AS

         manufacturer, pro.name, log.last_price, log.new_price FROM change_price_log log INNER JOIN product pro ON log.product_id = pro.product_id

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE CAST(date AS DATE) BETWEEN ? AND ?

     ORDER BY log.entry_id

     LIMIT ?, ?";



  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `change_price_log_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `change_price_log_insert`(IN inUserName VARCHAR(10), IN inProductId INT, IN inDate DATETIME,

  IN inLastPrice DECIMAL(6, 2), IN inNewPrice DECIMAL(6, 2))
BEGIN

  INSERT INTO change_price_log (user_account_username, product_id, date, last_price, new_price)

    VALUES (inUserName, inProductId, inDate, inLastPrice, inNewPrice);

END$$

DROP PROCEDURE IF EXISTS `company_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `company_get`()
BEGIN

  SELECT nit, name, corporate_name, telephone, address FROM company;

END$$

DROP PROCEDURE IF EXISTS `company_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `company_update`(IN inNit VARCHAR(10), IN inName VARCHAR(50), IN inCorporateName VARCHAR(50),
  IN inTelephone VARCHAR(50), IN inAddress VARCHAR(100))
BEGIN

  UPDATE company

    SET nit = inNit, name = inName, corporate_name = inCorporateName, telephone = inTelephone, address = inAddress;

END$$

DROP PROCEDURE IF EXISTS `comparison_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_get`(IN inComparisonId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, general, physical_total, system_total

      FROM comparison

    WHERE comparison_id = inComparisonId;

END$$

DROP PROCEDURE IF EXISTS `comparison_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_insert`(IN inUserName VARCHAR(10), IN inDate DATETIME, IN inReason VARCHAR(100), IN inGeneral TINYINT,

  IN inPhysicalTotal INT)
BEGIN

  INSERT INTO comparison (user_account_username, date, reason, general, physical_total)

    VALUES (inUserName, inDate, inReason, inGeneral, inPhysicalTotal);

END$$

DROP PROCEDURE IF EXISTS `comparison_product_general_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_product_general_insert`(IN inComparisonId INT, IN inCountId INT)
BEGIN

  DECLARE totalSystem INT;



  INSERT INTO comparison_product

    SELECT @comparison_product_id := NULL, @comparison_id := inComparisonId, pro.product_id, IFNULL(cnt.quantity, 0),

        pro.quantity FROM product pro

        LEFT JOIN (SELECT product_id, quantity FROM count_product WHERE count_id = inCountId) AS cnt

        ON pro.product_id = cnt.product_id

      ORDER BY pro.name;



  SELECT SUM(quantity) FROM product

    INTO totalSystem;



  UPDATE comparison

    SET system_total = totalSystem

    WHERE comparison_id = inComparisonId;

END$$

DROP PROCEDURE IF EXISTS `comparison_product_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_product_get`(IN inComparisonId INT)
BEGIN

  SELECT product_id, physical, system FROM comparison_product

      WHERE comparison_id = inComparisonId

      ORDER BY comparison_product_id;

END$$

DROP PROCEDURE IF EXISTS `comparison_product_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_product_insert`(IN inComparisonId INT, IN inCountId INT)
BEGIN

  DECLARE totalSystem INT;



  INSERT INTO comparison_product

    SELECT @comparison_product_id := NULL, @comparison_id := inComparisonId, cnt.product_id, cnt.quantity, pro.quantity

        FROM count_product cnt

        INNER JOIN product pro ON cnt.product_id = pro.product_id

      WHERE cnt.count_id = inCountId

      ORDER BY count_product_id;



  SELECT SUM(system) FROM comparison_product

    WHERE comparison_id = inComparisonId

    INTO totalSystem;



  UPDATE comparison

    SET system_total = totalSystem

    WHERE comparison_id = inComparisonId;

END$$

DROP PROCEDURE IF EXISTS `comparison_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM comparison

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `comparison_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT comparison_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM comparison

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `correlative_default_id`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_default_id`()
BEGIN

  SELECT correlative_id FROM correlative

    WHERE is_default = 1;

END$$

DROP PROCEDURE IF EXISTS `correlative_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_delete`(IN inCorrelativeId INT)
BEGIN

  DELETE FROM correlative

    WHERE correlative_id = inCorrelativeId;

END$$

DROP PROCEDURE IF EXISTS `correlative_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_dependencies`(IN inCorrelativeId INT)
BEGIN

  DECLARE correlativeRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM invoice

    WHERE correlative_id = inCorrelativeId

    INTO correlativeRowsCount;



  SET dependenciesCount = dependenciesCount + correlativeRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `correlative_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_exists`(IN inSerialNumber VARCHAR(10), IN inInitialNumber BIGINT,
IN inFinalNumber BIGINT)
BEGIN

  SELECT COUNT(*) FROM (SELECT * FROM correlative WHERE serial_number = inSerialNumber) cor
      WHERE (inInitialNumber BETWEEN cor.initial_number AND cor.final_number OR inFinalNumber BETWEEN cor.initial_number AND cor.final_number)
          OR (cor.initial_number BETWEEN inInitialNumber AND inFinalNumber OR cor.final_number BETWEEN inInitialNumber AND inFinalNumber);

END$$

DROP PROCEDURE IF EXISTS `correlative_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_get`(IN inCorrelativeId INT)
BEGIN

  SELECT serial_number, resolution_number, DATE_FORMAT(resolution_date, '%d/%m/%Y') AS resolution_date, regime, initial_number, final_number,

      current, is_default FROM correlative

    WHERE correlative_id = inCorrelativeId;

END$$

DROP PROCEDURE IF EXISTS `correlative_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_insert`(IN inSerialNumber VARCHAR(10), IN inResolutionNumber VARCHAR(50), IN inResolutionDate Date, IN inRegime VARCHAR(50),

  IN inInitialNumber BIGINT, IN inFinalNumber BIGINT)
BEGIN

  INSERT INTO correlative (serial_number, resolution_number, resolution_date, regime, initial_number, final_number)

    VALUES (inSerialNumber, inResolutionNumber, inResolutionDate, inRegime, inInitialNumber, inFinalNumber);

END$$

DROP PROCEDURE IF EXISTS `correlative_is_empty`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_is_empty`()
BEGIN

  DECLARE rowsCount INT;



  SELECT COUNT(*) FROM correlative

    INTO rowsCount;



  IF rowsCount > 0 THEN

    SELECT 0;

  ELSE

    SELECT 1;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `correlative_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_list_count`()
BEGIN

  SELECT COUNT(*) FROM correlative;

END$$

DROP PROCEDURE IF EXISTS `correlative_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT correlative_id AS id, serial_number, is_default, initial_number, final_number FROM correlative

      ORDER BY serial_number, initial_number

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `correlative_make_default`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_make_default`(IN inCorrelativeId INT)
BEGIN

  UPDATE correlative

    SET is_default = 0;



  UPDATE correlative

    SET is_default = 1

    WHERE correlative_id = inCorrelativeId;

END$$

DROP PROCEDURE IF EXISTS `correlative_next_number`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_next_number`(IN inCorrelativeId INT)
BEGIN

  DECLARE initialNumber BIGINT;

  DECLARE currentNumber BIGINT;



  SELECT initial_number, current FROM correlative

    WHERE correlative_id = inCorrelativeId

    INTO initialNumber, currentNumber;



  IF currentNumber = 0 THEN

    UPDATE correlative

      SET current = initialNumber

      WHERE correlative_id = inCorrelativeId;

    

    SELECT initialNumber;

  ELSE

    SELECT currentNumber + 1;



    UPDATE correlative

      SET current = current + 1

      WHERE correlative_id = inCorrelativeId;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `correlative_resolution_number_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_resolution_number_exists`(IN inResolutionNumber VARCHAR(50))
BEGIN

  SELECT COUNT(*) FROM correlative

  WHERE resolution_number = inResolutionNumber;

END$$

DROP PROCEDURE IF EXISTS `count_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_delete`(IN inCountId INT)
BEGIN

  DELETE FROM count_product

    WHERE count_id = inCountId;



  DELETE FROM count

    WHERE count_id = inCountId;

END$$

DROP PROCEDURE IF EXISTS `count_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_get`(IN inCountId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, total FROM count

    WHERE count_id = inCountId;

END$$

DROP PROCEDURE IF EXISTS `count_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_insert`(IN inUserName VARCHAR(10), IN inDate DATETIME, IN inReason VARCHAR(100), IN inTotal INT)
BEGIN

  INSERT INTO count (user_account_username, date, reason, total)

    VALUES (inUserName, inDate, inReason, inTotal);

END$$

DROP PROCEDURE IF EXISTS `count_product_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_product_delete`(IN inCountId INT, IN inProductId INT)
BEGIN

  DELETE FROM count_product

    WHERE count_id = inCountId AND product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `count_product_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_product_get`(IN inCountId INT)
BEGIN

  SELECT product_id, quantity FROM count_product

    WHERE count_id = inCountId

    ORDER BY count_product_id;

END$$

DROP PROCEDURE IF EXISTS `count_product_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_product_insert`(IN inCountId INT, IN inProductId INT, IN inQuantity INT)
BEGIN

  INSERT INTO count_product (count_id, product_id, quantity)

    VALUES (inCountId, inProductId, inQuantity);

END$$

DROP PROCEDURE IF EXISTS `count_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM count

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `count_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT count_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM count

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `count_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_update`(IN inCountId INT, IN inTotal INT)
BEGIN

  UPDATE count

    SET total = inTotal

    WHERE count_id = inCountId;

END$$

DROP PROCEDURE IF EXISTS `customer_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_exists`(IN inNit VARCHAR(15))
BEGIN

  SELECT COUNT(*) FROM customer

  WHERE nit = inNit;

END$$

DROP PROCEDURE IF EXISTS `customer_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_get`(IN inNit VARCHAR(15))
BEGIN

  SELECT name FROM customer

    WHERE nit = inNit;

END$$

DROP PROCEDURE IF EXISTS `customer_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_insert`(IN inNit VARCHAR(15), IN inName VARCHAR(50))
BEGIN

  INSERT INTO customer (nit, name)

    VALUES(inNit, inName);

END$$

DROP PROCEDURE IF EXISTS `customer_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_update`(IN inNit VARCHAR(15), IN inName VARCHAR(50))
BEGIN

  UPDATE customer

  SET name = inName

  WHERE nit = inNit;

END$$

DROP PROCEDURE IF EXISTS `delete_all_movements`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `delete_all_movements`(IN inDate DATE)
BEGIN

  DELETE FROM deposit_cancelled

    WHERE deposit_id IN (SELECT deposit_id FROM deposit WHERE date < inDate);



  DELETE FROM deposit_cash_receipt

    WHERE deposit_id IN (SELECT deposit_id FROM deposit WHERE date < inDate);



  DELETE FROM deposit WHERE date < inDate;



  DELETE FROM voucher

    WHERE cash_receipt_id IN (SELECT invoice_id FROM invoice WHERE date < inDate);



  DELETE FROM cash_receipt

    WHERE cash_receipt_id IN (SELECT invoice_id FROM invoice WHERE date < inDate);



  DELETE FROM discount

    WHERE invoice_id IN (SELECT invoice_id FROM invoice WHERE date < inDate);



  DELETE FROM invoice_cancelled

    WHERE invoice_id IN (SELECT invoice_id FROM invoice WHERE date < inDate);



  DELETE FROM invoice_bonus

    WHERE invoice_id IN (SELECT invoice_id FROM invoice WHERE date < inDate);



  DELETE FROM invoice_lot

    WHERE invoice_id IN (SELECT invoice_id FROM invoice WHERE date < inDate);



  DELETE FROM invoice WHERE date < inDate;



  DELETE FROM bonus WHERE expiration_date < inDate;



  DELETE FROM change_price_log WHERE date < inDate;



  DELETE FROM comparison_product

    WHERE comparison_id IN (SELECT comparison_id FROM comparison WHERE date < inDate);



  DELETE FROM comparison WHERE date < inDate;



  DELETE FROM count_product

    WHERE count_id IN (SELECT count_id FROM count WHERE date < inDate);



  DELETE FROM count WHERE date < inDate;



  DELETE FROM entry_adjustment_cancelled

    WHERE entry_adjustment_id IN (SELECT entry_adjustment_id FROM entry_adjustment WHERE date < inDate);



  DELETE FROM entry_adjustment_lot

    WHERE entry_adjustment_id IN (SELECT entry_adjustment_id FROM entry_adjustment WHERE date < inDate);



  DELETE FROM entry_adjustment WHERE date < inDate;



  DELETE FROM purchase_return_cancelled

    WHERE purchase_return_id IN (SELECT purchase_return_id FROM purchase_return WHERE date < inDate);



  DELETE FROM purchase_return_lot

    WHERE purchase_return_id IN (SELECT purchase_return_id FROM purchase_return WHERE date < inDate);



  DELETE FROM purchase_return WHERE date < inDate;



  DELETE FROM receipt_cancelled

    WHERE receipt_id IN (SELECT receipt_id FROM receipt WHERE date < inDate);



  DELETE FROM receipt_lot

    WHERE receipt_id IN (SELECT receipt_id FROM receipt WHERE date < inDate);



  DELETE FROM receipt WHERE date < inDate;



  DELETE FROM shipment_cancelled

    WHERE shipment_id IN (SELECT shipment_id FROM shipment WHERE date < inDate);



  DELETE FROM shipment_lot

    WHERE shipment_id IN (SELECT shipment_id FROM shipment WHERE date < inDate);



  DELETE FROM shipment WHERE date < inDate;



  DELETE FROM withdraw_adjustment_cancelled

    WHERE withdraw_adjustment_id IN (SELECT withdraw_adjustment_id FROM withdraw_adjustment WHERE date < inDate);



  DELETE FROM withdraw_adjustment_lot

    WHERE withdraw_adjustment_id IN (SELECT withdraw_adjustment_id FROM withdraw_adjustment WHERE date < inDate);



  DELETE FROM withdraw_adjustment WHERE date < inDate;

END$$

DROP PROCEDURE IF EXISTS `deposit_by_working_day_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_by_working_day_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM deposit dep

      INNER JOIN cash_register cr ON dep.cash_register_id = cr.cash_register_id

    WHERE CAST(working_day AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `deposit_by_working_day_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_by_working_day_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT deposit_id AS id, DATE_FORMAT(working_day, '%d/%m/%Y') AS working_day FROM deposit dep

      INNER JOIN cash_register cr ON dep.cash_register_id = cr.cash_register_id

      WHERE CAST(working_day AS DATE) BETWEEN ? AND ?

      ORDER BY CAST(working_day AS DATE), deposit_id

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `deposit_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cancel`(IN inDepositId INT, IN inUserName VARCHAR(10), IN inDate DATETIME)
BEGIN

  UPDATE deposit

    SET status = 2

    WHERE deposit_id = inDepositId;



  INSERT INTO deposit_cancelled (deposit_id, user_account_username, date)

    VALUES (inDepositId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `deposit_cash_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_list_get`(IN inCashReceiptId INT)
BEGIN

  SELECT deposit_id FROM deposit_cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

DROP PROCEDURE IF EXISTS `deposit_cash_receipt_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_receipt_count`(IN inDepositId INT)
BEGIN

  SELECT COUNT(*) FROM deposit_cash_receipt

    WHERE deposit_id = inDepositId;

END$$

DROP PROCEDURE IF EXISTS `deposit_cash_receipt_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_receipt_get`(IN inDepositId INT, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT cash_receipt_id, amount FROM deposit_cash_receipt

      WHERE deposit_id = ?

      ORDER BY deposit_cash_receipt_id

      LIMIT ?, ?";



  SET @p1 = inDepositId;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

DROP PROCEDURE IF EXISTS `deposit_cash_receipt_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_receipt_insert`(IN inDepositId INT, IN inCashReceiptId INT, IN inAmount DECIMAL(13, 2))
BEGIN

  INSERT INTO deposit_cash_receipt (deposit_id, cash_receipt_id, amount)

    VALUES (inDepositId, inCashReceiptId, inAmount);

END$$

DROP PROCEDURE IF EXISTS `deposit_confirm`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_confirm`(IN inDepositId INT)
BEGIN

  UPDATE deposit

    SET status = 3

    WHERE deposit_id = inDepositId;

END$$

DROP PROCEDURE IF EXISTS `deposit_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_get`(IN inDepositId INT)
BEGIN

  SELECT bank_account_number, cash_register_id, user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, number,

    total, status FROM deposit

    WHERE deposit_id = inDepositId;

END$$

DROP PROCEDURE IF EXISTS `deposit_id_get_by_working_day`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_id_get_by_working_day`(IN inWorkingDay DATE, IN inId INT)
BEGIN

  SELECT deposit_id FROM deposit dep INNER JOIN cash_register cr ON dep.cash_register_id = cr.cash_register_id

    WHERE cr.working_day = inWorkingDay AND dep.deposit_id = inId;

END$$

DROP PROCEDURE IF EXISTS `deposit_id_get_by_working_day_slip`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_id_get_by_working_day_slip`(IN inWorkingDay DATE, IN inBankId INT, IN inNumber VARCHAR(50))
BEGIN

  SELECT deposit_id FROM deposit dep INNER JOIN cash_register cr ON dep.cash_register_id = cr.cash_register_id

    INNER JOIN bank_account ba ON dep.bank_account_number = ba.bank_account_number

    WHERE cr.working_day = inWorkingDay AND ba.bank_id = inBankId AND dep.number = inNumber AND dep.status = 1;

END$$

DROP PROCEDURE IF EXISTS `deposit_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_insert`(IN inBankAccountNumber VARCHAR(50), IN inCashRegisterId INT, IN inUserName VARCHAR(10),

  IN inDate DATETIME, IN inNumber VARCHAR(50), IN inTotal DECIMAL(13, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO deposit (bank_account_number, cash_register_id, user_account_username, date, number, total, status)

    VALUES (inBankAccountNumber, inCashRegisterId, inUserName, inDate, inNumber, inTotal, inStatus);

END$$

DROP PROCEDURE IF EXISTS `deposit_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_list_get`(IN inCashRegisterId INT)
BEGIN

  SELECT deposit_id AS id, bank_id, number, status FROM deposit dep INNER JOIN bank_account ba ON dep.bank_account_number = ba.bank_account_number

    WHERE cash_register_id = inCashRegisterId ORDER BY deposit_id;

END$$

DROP PROCEDURE IF EXISTS `deposit_number_bank_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_number_bank_exists`(IN inNumber VARCHAR(50), IN inBankId INT)
BEGIN

  SELECT COUNT(*) FROM deposit dep INNER JOIN bank_account ba ON dep.bank_account_number = ba.bank_account_number

  WHERE dep.number = inNumber AND ba.bank_id = inBankId AND status = 1;

END$$

DROP PROCEDURE IF EXISTS `deposit_pending_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_pending_list_count`()
BEGIN

  SELECT COUNT(*) FROM deposit

    WHERE status = 1;

END$$

DROP PROCEDURE IF EXISTS `deposit_pending_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_pending_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT dep.deposit_id AS id, DATE_FORMAT(dep.date, '%d/%m/%Y') AS created_date, dep.bank_account_number, dep.number, 

        ban.name AS bank, dep.total FROM deposit dep

        INNER JOIN bank_account bac ON dep.bank_account_number = bac.bank_account_number

        INNER JOIN bank ban ON bac.bank_id = ban.bank_id

      WHERE dep.status = 1

      ORDER BY dep.date

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `deposit_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM deposit

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `deposit_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT deposit_id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM deposit

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `discount_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_get`(IN inInvoiceId INT)
BEGIN

  SELECT user_account_username, percentage FROM discount

    WHERE invoice_id = inInvoiceId;

END$$

DROP PROCEDURE IF EXISTS `discount_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_insert`(IN inInvoiceId INT, IN inUserName VARCHAR(10), IN inPercentage DECIMAL(4, 2))
BEGIN

  INSERT INTO discount (invoice_id, user_account_username, percentage)

    VALUES (inInvoiceId, inUserName, inPercentage);

END$$

DROP PROCEDURE IF EXISTS `discount_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_list_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT COUNT(*) FROM discount dis INNER JOIN invoice inv ON dis.invoice_id = inv.invoice_id

    WHERE inv.status = 1 AND inv.date BETWEEN inFirstDate AND inLastDate;

END$$

DROP PROCEDURE IF EXISTS `discount_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_list_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT DATE_FORMAT(inv.date, '%d/%m/%Y %H:%i:%s') AS created_date, dis.user_account_username AS username, cor.serial_number,

         inv.number, inv.total AS subtotal, dis.percentage, inv.total * (dis.percentage / 100) AS amount,

         inv.total - inv.total * (dis.percentage / 100) AS total FROM discount dis INNER JOIN invoice inv 

         ON dis.invoice_id = inv.invoice_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

     WHERE inv.status = 1 AND inv.date BETWEEN ? AND ?

     ORDER BY inv.invoice_id

     LIMIT ?, ?";



  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_cancel`(IN inEntryAdjustmentId INT, IN inUserName VARCHAR(10),

  IN inDate DATETIME)
BEGIN

  UPDATE entry_adjustment

    SET status = 2

    WHERE entry_adjustment_id = inEntryAdjustmentId;



  INSERT INTO entry_adjustment_cancelled (entry_adjustment_id, user_account_username, date)

    VALUES (inEntryAdjustmentId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_get`(IN inEntryAdjustmentId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, total, status

      FROM entry_adjustment

    WHERE entry_adjustment_id = inEntryAdjustmentId;

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_insert`(IN inUserName VARCHAR(10), IN inDate DATETIME,

  IN inReason VARCHAR(100), IN inTotal DECIMAL(13, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO entry_adjustment (user_account_username, date, reason, total, status)

    VALUES (inUserName, inDate, inReason, inTotal, inStatus);

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_lot_get`(IN inEntryAdjustmentId INT)
BEGIN

  SELECT lot_id, quantity, price FROM entry_adjustment_lot

      WHERE entry_adjustment_id =  inEntryAdjustmentId

      ORDER BY number;

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(6, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO entry_adjustment_lot (entry_adjustment_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM entry_adjustment

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `entry_adjustment_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT entry_adjustment_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM entry_adjustment

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `general_closure`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `general_closure`(IN inDays INT)
BEGIN

  DECLARE productId INT;

  DECLARE balanceForward INT;

  DECLARE finalBalance INT;

  DECLARE fromDate DATE;



  DECLARE done INT DEFAULT 0;

  DECLARE c1 CURSOR FOR SELECT product_id FROM product;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;



  SET fromDate = DATE_ADD(CURDATE(), INTERVAL -1 * inDays day);



  OPEN c1;

  FETCH c1 INTO productId;

  WHILE NOT done DO

    SELECT balance_forward FROM product WHERE product_id = productId INTO balanceForward;

    CALL kardex_balance_forward_from_date_get(productId, balanceForward, fromDate, finalBalance);

    IF finalBalance != 0 THEN
      UPDATE product
        SET balance_forward = finalBalance
        WHERE product_id = productId;
    END IF;

    FETCH c1 INTO productId;

  END WHILE;

  CLOSE c1;



  CALL delete_all_movements(fromDate);

END$$

DROP PROCEDURE IF EXISTS `general_sales_report_cash_registers_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `general_sales_report_cash_registers_get`(IN inWorkingDay DATE)
BEGIN

  SELECT cr.cash_register_id AS id, sf.name, sf.time_table,

      SUM(inv.total - inv.total * (IFNULL(dis.percentage, 0) / 100)) AS total FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      INNER JOIN shift sf ON cr.shift_id = sf.shift_id

      LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cr.working_day = inWorkingDay AND inv.status = 1

    GROUP BY cr.cash_register_id;

END$$

DROP PROCEDURE IF EXISTS `general_sales_report_total`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `general_sales_report_total`(IN inWorkingDay DATE)
BEGIN

  SELECT SUM(inv.total - inv.total * (IFNULL(dis.percentage, 0) / 100)) FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cr.working_day = inWorkingDay AND inv.status = 1;

END$$

DROP PROCEDURE IF EXISTS `get_last_insert_id`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `get_last_insert_id`()
BEGIN

  SELECT LAST_INSERT_ID();

END$$

DROP PROCEDURE IF EXISTS `invoice_bonus_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_bonus_insert`(IN inInvoiceId INT, IN inBonusId INT, IN inNumber INT, IN inPrice DECIMAL(13, 2))
BEGIN

  INSERT INTO invoice_bonus (invoice_id, bonus_id, number, price)

    VALUES (inInvoiceId, inBonusId, inNumber, inPrice);

END$$

DROP PROCEDURE IF EXISTS `invoice_by_working_day_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_by_working_day_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

    WHERE CAST(working_day AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `invoice_by_working_day_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_by_working_day_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT invoice_id, serial_number, number, DATE_FORMAT(working_day, '%d/%m/%Y') AS working_day FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

      WHERE CAST(working_day AS DATE) BETWEEN ? AND ?

      ORDER BY CAST(working_day AS DATE), invoice_id

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `invoice_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_cancel`(IN inInvoiceId INT, IN inUserName VARCHAR(10),

  IN inDate DATETIME)
BEGIN

  UPDATE invoice

    SET status = 2

    WHERE invoice_id = inInvoiceId;



  INSERT INTO invoice_cancelled (invoice_id, user_account_username, date)

    VALUES (inInvoiceId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `invoice_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_get`(IN inInvoiceId INT)
BEGIN

  SELECT correlative_id, number, user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, nit, name, total, vat,

      cash_register_id, status FROM invoice

    WHERE invoice_id = inInvoiceId;

END$$

DROP PROCEDURE IF EXISTS `invoice_id_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_id_get`(IN inSerialNumber VARCHAR(10), IN inNumber BIGINT)
BEGIN

  SELECT invoice_id FROM invoice inv INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

    WHERE cor.serial_number = inSerialNumber AND inv.number = inNumber;

END$$

DROP PROCEDURE IF EXISTS `invoice_id_get_by_working_day`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_id_get_by_working_day`(IN inWorkingDay DATE, IN inSerialNumber VARCHAR(10), IN inNumber BIGINT)
BEGIN

  SELECT invoice_id FROM invoice inv INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

    WHERE cr.working_day = inWorkingDay AND cor.serial_number = inSerialNumber AND inv.number = inNumber;

END$$

DROP PROCEDURE IF EXISTS `invoice_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_insert`(IN inCorrelativeId INT, IN inNumber BIGINT,

  IN inUserName VARCHAR(10), IN inDate DATETIME, IN inNit VARCHAR(15), IN inName VARCHAR(50), IN inTotal DECIMAL(13, 2),

  IN inVat DECIMAL(4, 2), IN inCashRegisterId INT, IN inStatus TINYINT)
BEGIN

  INSERT INTO invoice (correlative_id, number, user_account_username, date, nit, name, total, vat, cash_register_id, status)

    VALUES (inCorrelativeId, inNumber, inUserName, inDate, inNit, inName, inTotal, inVat, inCashRegisterId, inStatus);

END$$

DROP PROCEDURE IF EXISTS `invoice_items_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_items_get`(IN inInvoiceId INT)
BEGIN

  (SELECT invoice_id, lot_id, NULL AS bonus_id, number, quantity, price FROM invoice_lot

        WHERE invoice_id = inInvoiceId)

      UNION

     (SELECT invoice_id, NULL AS lot_id , bonus_id, number, 1 AS quantity, price FROM invoice_bonus

        WHERE invoice_id = inInvoiceId)

      ORDER BY number;

END$$

DROP PROCEDURE IF EXISTS `invoice_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_list_get`(IN inCashRegisterId INT)
BEGIN

  SELECT invoice_id AS id, serial_number, number  FROM invoice inv INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

    WHERE cash_register_id = inCashRegisterId ORDER BY invoice_id;

END$$

DROP PROCEDURE IF EXISTS `invoice_lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(6, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO invoice_lot (invoice_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

DROP PROCEDURE IF EXISTS `invoice_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM invoice

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `invoice_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT invoice_id, serial_number, number, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM invoice inv

      INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `invoice_transaction_log_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_transaction_log_insert`(IN inSerialNumber VARCHAR(10), IN inNumber BIGINT, IN inDate DATE,

  IN inTotal DECIMAL(13, 2), IN inState VARCHAR(10))
BEGIN

  INSERT INTO invoice_transaction_log (serial_number, number, date, total, state)

    VALUES (inSerialNumber, inNumber, inDate, inTotal, inState);

END$$

DROP PROCEDURE IF EXISTS `kardex_balance_forward_from_date_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_balance_forward_from_date_get`(IN inProductId INT, IN inBalanceForward INT,

  IN inDate DATE, OUT finalBalance INT)
BEGIN

  SET @balance := inBalanceForward;



  SELECT balance FROM (SELECT created_date, item_number, @balance := @balance + entry - withdraw AS balance FROM

      (SELECT inv.date AS created_date, 0 AS entry, inv_lot.quantity AS withdraw, inv_lot.number AS item_number FROM

           invoice inv INNER JOIN invoice_lot inv_lot

           ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

         WHERE inv.status = 1 AND lot.product_id = inProductId AND inv.date < inDate

       UNION

       SELECT rec.date AS created_date, rec_lot.quantity AS entry, 0 AS withdraw, rec_lot.number AS item_number FROM

           receipt rec INNER JOIN receipt_lot rec_lot

           ON rec.receipt_id = rec_lot.receipt_id INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

         WHERE rec.status = 1 AND lot.product_id = inProductId AND rec.date < inDate

       UNION

       SELECT pur.date AS created_date, 0 AS entry, pur_lot.quantity AS withdraw, pur_lot.number AS item_number FROM

           purchase_return pur

           INNER JOIN purchase_return_lot pur_lot ON pur.purchase_return_id = pur_lot.purchase_return_id INNER JOIN lot

           ON pur_lot.lot_id = lot.lot_id 

         WHERE pur.status = 1 AND lot.product_id = inProductId AND pur.date < inDate

       UNION

       SELECT shi.date AS created_date, 0 AS entry, shi_lot.quantity AS withdraw, shi_lot.number AS item_number FROM

           shipment shi INNER JOIN shipment_lot shi_lot

           ON shi.shipment_id = shi_lot.shipment_id INNER JOIN lot ON shi_lot.lot_id = lot.lot_id

         WHERE shi.status = 1 AND lot.product_id = inProductId AND shi.date < inDate

       UNION

       SELECT ent.date AS created_date, ent_lot.quantity AS entry, 0 AS withdraw, ent_lot.number AS item_number FROM

           entry_adjustment ent INNER JOIN entry_adjustment_lot ent_lot

           ON ent.entry_adjustment_id = ent_lot.entry_adjustment_id INNER JOIN lot ON ent_lot.lot_id = lot.lot_id

         WHERE ent.status = 1 AND lot.product_id = inProductId AND ent.date < inDate

       UNION

       SELECT wit.date AS created_date, 0 AS entry, wit_lot.quantity AS withdraw, wit_lot.number AS item_number FROM

           withdraw_adjustment wit INNER JOIN withdraw_adjustment_lot wit_lot

           ON wit.withdraw_adjustment_id = wit_lot.withdraw_adjustment_id INNER JOIN lot ON wit_lot.lot_id = lot.lot_id

         WHERE wit.status = 1 AND lot.product_id = inProductId AND wit.date < inDate) AS kardex

       ORDER BY created_date, item_number) AS final_balance

  ORDER BY created_date DESC, item_number DESC

  LIMIT 1 INTO finalBalance;

END$$

DROP PROCEDURE IF EXISTS `kardex_balance_forward_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_balance_forward_get`(IN inProductId INT, IN inLastItem INT,

  IN inBalanceForward INT)
BEGIN

  SET @balance := inBalanceForward;



  PREPARE statement FROM

    "SELECT balance FROM

       (SELECT @balance := @balance + entry - withdraw AS balance FROM

       (SELECT inv.date AS created_date, 0 AS entry, inv_lot.quantity AS withdraw, inv_lot.number AS item_number FROM invoice inv INNER JOIN invoice_lot inv_lot

            ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

        WHERE inv.status = 1 AND lot.product_id = ?

        UNION

        SELECT rec.date AS created_date, rec_lot.quantity AS entry, 0 AS withdraw, rec_lot.number AS item_number FROM receipt rec INNER JOIN receipt_lot rec_lot

            ON rec.receipt_id = rec_lot.receipt_id INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

        WHERE rec.status = 1 AND lot.product_id = ?

        UNION

        SELECT pur.date AS created_date, 0 AS entry, pur_lot.quantity AS withdraw, pur_lot.number AS item_number FROM purchase_return pur

            INNER JOIN purchase_return_lot pur_lot ON pur.purchase_return_id = pur_lot.purchase_return_id INNER JOIN lot

            ON pur_lot.lot_id = lot.lot_id

        WHERE pur.status = 1 AND lot.product_id = ?

        UNION

        SELECT shi.date AS created_date, 0 AS entry, shi_lot.quantity AS withdraw, shi_lot.number AS item_number FROM shipment shi INNER JOIN shipment_lot shi_lot

            ON shi.shipment_id = shi_lot.shipment_id INNER JOIN lot ON shi_lot.lot_id = lot.lot_id

        WHERE shi.status = 1 AND lot.product_id = ?

        UNION

        SELECT ent.date AS created_date, ent_lot.quantity AS entry, 0 AS withdraw, ent_lot.number AS item_number FROM entry_adjustment ent INNER JOIN entry_adjustment_lot ent_lot

            ON ent.entry_adjustment_id = ent_lot.entry_adjustment_id INNER JOIN lot ON ent_lot.lot_id = lot.lot_id

        WHERE ent.status = 1 AND lot.product_id = ?

        UNION

        SELECT wit.date AS created_date, 0 AS entry, wit_lot.quantity AS withdraw, wit_lot.number AS item_number FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_lot wit_lot

            ON wit.withdraw_adjustment_id = wit_lot.withdraw_adjustment_id INNER JOIN lot ON wit_lot.lot_id = lot.lot_id

        WHERE wit.status = 1 AND lot.product_id = ?) AS kardex ORDER BY created_date, item_number

      LIMIT 0, ?) AS final_balance LIMIT ?, 1";



  SET @p1 = inProductId;

  SET @p2 = inProductId;

  SET @p3 = inProductId;

  SET @p4 = inProductId;

  SET @p5 = inProductId;

  SET @p6 = inProductId;

  SET @p7 = inLastItem;

  SET @p8 = inLastItem - 1;



  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6, @p7, @p8;

END$$

DROP PROCEDURE IF EXISTS `kardex_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_count`(IN inProductId INT)
BEGIN

  SELECT SUM(count_rows) FROM (SELECT COUNT(*) AS count_rows FROM invoice inv INNER JOIN invoice_lot inv_lot

            ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

        WHERE inv.status = 1 AND lot.product_id = inProductId

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM receipt rec INNER JOIN receipt_lot rec_lot

            ON rec.receipt_id = rec_lot.receipt_id INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

        WHERE rec.status = 1 AND lot.product_id = inProductId

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM purchase_return pur

            INNER JOIN purchase_return_lot pur_lot ON pur.purchase_return_id = pur_lot.purchase_return_id INNER JOIN lot

            ON pur_lot.lot_id = lot.lot_id

        WHERE pur.status = 1 AND lot.product_id = inProductId

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM shipment shi INNER JOIN shipment_lot shi_lot

            ON shi.shipment_id = shi_lot.shipment_id INNER JOIN lot ON shi_lot.lot_id = lot.lot_id

        WHERE shi.status = 1 AND lot.product_id = inProductId

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM entry_adjustment ent INNER JOIN entry_adjustment_lot ent_lot

            ON ent.entry_adjustment_id = ent_lot.entry_adjustment_id INNER JOIN lot ON ent_lot.lot_id = lot.lot_id

        WHERE ent.status = 1 AND lot.product_id = inProductId

        UNION ALL

        SELECT COUNT(*) AS count_rows FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_lot wit_lot

            ON wit.withdraw_adjustment_id = wit_lot.withdraw_adjustment_id INNER JOIN lot ON wit_lot.lot_id = lot.lot_id

        WHERE wit.status = 1 AND lot.product_id = inProductId) AS kardex;

END$$

DROP PROCEDURE IF EXISTS `kardex_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_get`(IN inProductId INT, IN inStartItem INT, IN inItemsPerPage INT,

  IN inBalanceForward INT)
BEGIN

  SET @balance := inBalanceForward;



  PREPARE statement FROM

    "SELECT created_date, document, number, lot_id, entry, withdraw,

         @balance := @balance + CAST(entry AS SIGNED) - CAST(withdraw AS SIGNED) AS balance FROM

       (SELECT date, DATE_FORMAT(inv.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Factura' AS document,

            CONCAT(cor.serial_number, '-', inv.number) AS number, inv_lot.lot_id, '' AS entry, inv_lot.quantity AS withdraw,

            inv_lot.number AS item_number FROM invoice inv INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

            INNER JOIN invoice_lot inv_lot

            ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

        WHERE inv.status = 1 AND lot.product_id = ?

        UNION

        SELECT date, DATE_FORMAT(rec.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Recibo' AS document, rec.receipt_id AS number,

            rec_lot.lot_id, rec_lot.quantity AS entry, '' AS withdraw, rec_lot.number AS item_number FROM receipt rec INNER

            JOIN receipt_lot rec_lot ON rec.receipt_id = rec_lot.receipt_id INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

        WHERE rec.status = 1 AND lot.product_id = ?

        UNION

        SELECT date, DATE_FORMAT(pur.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Devolucion' AS document,

            pur.purchase_return_id AS number, pur_lot.lot_id, '' AS entry, pur_lot.quantity AS withdraw,

            pur_lot.number AS item_number FROM purchase_return pur INNER JOIN purchase_return_lot pur_lot ON

            pur.purchase_return_id = pur_lot.purchase_return_id INNER JOIN lot ON pur_lot.lot_id = lot.lot_id

        WHERE pur.status = 1 AND lot.product_id = ?

        UNION

        SELECT date, DATE_FORMAT(shi.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Envio' AS document, shi.shipment_id AS number,

            shi_lot.lot_id, '' AS entry, shi_lot.quantity AS withdraw, shi_lot.number AS item_number FROM shipment shi INNER

            JOIN shipment_lot shi_lot ON shi.shipment_id = shi_lot.shipment_id INNER JOIN lot ON shi_lot.lot_id = lot.lot_id

        WHERE shi.status = 1 AND lot.product_id = ?

        UNION

        SELECT date, DATE_FORMAT(ent.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Vale Entrada' AS document, ent.entry_adjustment_id

            AS number, ent_lot.lot_id, ent_lot.quantity AS entry, '' AS withdraw, ent_lot.number AS item_number FROM

            entry_adjustment ent INNER JOIN entry_adjustment_lot ent_lot

            ON ent.entry_adjustment_id = ent_lot.entry_adjustment_id INNER JOIN lot ON ent_lot.lot_id = lot.lot_id

        WHERE ent.status = 1 AND lot.product_id = ?

        UNION

        SELECT date, DATE_FORMAT(wit.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Vale Salida' AS document,

            wit.withdraw_adjustment_id AS number, wit_lot.lot_id, '' AS entry, wit_lot.quantity AS withdraw,

            wit_lot.number AS item_number FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_lot wit_lot

            ON wit.withdraw_adjustment_id = wit_lot.withdraw_adjustment_id INNER JOIN lot ON wit_lot.lot_id = lot.lot_id

        WHERE wit.status = 1 AND lot.product_id = ?) AS kardex ORDER BY date, item_number

      LIMIT ?, ?";



  SET @p1 = inProductId;

  SET @p2 = inProductId;

  SET @p3 = inProductId;

  SET @p4 = inProductId;

  SET @p5 = inProductId;

  SET @p6 = inProductId;

  SET @p7 = inStartItem;

  SET @p8 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5, @p6, @p7, @p8;

END$$

DROP PROCEDURE IF EXISTS `lot_available_quantity_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_available_quantity_get`(IN inLotId INT)
BEGIN

  SELECT quantity - reserved FROM lot

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_deactivate`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_deactivate`(IN inLotId INT)
BEGIN

  UPDATE lot

    SET quantity = 0, reserved = 0

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_decrease_quantity`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_decrease_quantity`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET quantity = quantity - inQuantity

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_decrease_reserved`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_decrease_reserved`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET reserved = reserved - inQuantity

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_expiration_date_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_expiration_date_update`(IN inLotId INT, IN inExpirationDate DATE)
BEGIN

  UPDATE lot

    SET expiration_date = inExpirationDate

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_expired_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_expired_count`(IN inDate DATE)
BEGIN

  SELECT COUNT(*) FROM lot WHERE expiration_date <= inDate AND quantity > 0;

END$$

DROP PROCEDURE IF EXISTS `lot_expired_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_expired_get`(IN inDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT lot.lot_id, pro.bar_code, man.name AS manufacturer, pro.name, DATE_FORMAT(lot.expiration_date, '%d/%m/%Y') AS 

          expiration_date, lot.quantity,

         (lot.quantity - lot.reserved) AS available FROM lot INNER JOIN product pro ON lot.product_id = pro.product_id

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE lot.expiration_date <= ? AND lot.quantity > 0 ORDER BY lot.expiration_date, pro.name

     LIMIT ?, ?";

  SET @p1 = inDate;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2, @p3;

END$$

DROP PROCEDURE IF EXISTS `lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_get`(IN inLotId INT)
BEGIN

  SELECT product_id, DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date,

      DATE_FORMAT(entry_date, '%d/%m/%Y') AS entry_date, price FROM lot

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_increase_quantity`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_increase_quantity`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET quantity = quantity + inQuantity

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_increase_reserved`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_increase_reserved`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET reserved = reserved + inQuantity

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_insert`(IN inProductId INT, IN inEntryDate DATE, IN inExpirationDate DATE, IN inPrice DECIMAL(6, 2),

  IN inQuantity INT)
BEGIN

  INSERT INTO lot (product_id, entry_date, expiration_date, price, quantity)

    VALUES (inProductId, inEntryDate, inExpirationDate, inPrice, inQuantity);

END$$

DROP PROCEDURE IF EXISTS `lot_near_expiration_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_near_expiration_count`(IN inDate DATE, IN inDays INT)
BEGIN

  SELECT COUNT(*) FROM lot WHERE expiration_date > inDate AND expiration_date <= DATE_ADD(inDate, INTERVAL inDays DAY) AND quantity > 0;

END$$

DROP PROCEDURE IF EXISTS `lot_near_expiration_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_near_expiration_get`(IN inDate DATE, IN inDays INT, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT lot.lot_id, pro.bar_code, man.name AS manufacturer, pro.name, DATE_FORMAT(lot.expiration_date, '%d/%m/%Y') AS 

          expiration_date, lot.quantity,

         (lot.quantity - lot.reserved) AS available FROM lot INNER JOIN product pro ON lot.product_id = pro.product_id

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE lot.expiration_date > ? AND lot.expiration_date <= DATE_ADD(?, INTERVAL ? DAY) AND lot.quantity > 0

     ORDER BY lot.expiration_date, pro.name

     LIMIT ?, ?";

  SET @p1 = inDate;

  SET @p2 = inDate;

  SET @p3 = inDays;

  SET @p4 = inStartItem;

  SET @p5 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2, @p3, @p4, @p5;

END$$

DROP PROCEDURE IF EXISTS `lot_price_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_price_update`(IN inLotId INT, IN inPrice DECIMAL(6, 2))
BEGIN

  UPDATE lot

    SET price = inPrice

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `lot_quantity_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_quantity_get`(IN inLotId INT)
BEGIN

  SELECT quantity FROM lot

    WHERE lot_id = inLotId;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_counting_template_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_counting_template_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50))
BEGIN

  SELECT * FROM

    (SELECT pro.product_id AS id, pro.bar_code, man.name AS manufacturer, pro.name AS product FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro.deactivated = 0 ORDER BY man.name, pro.name) AS counting_template

  WHERE manufacturer BETWEEN inFirst AND inLast;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_delete`(IN inManufacturerId INT)
BEGIN

  DELETE FROM manufacturer

    WHERE manufacturer_id = inManufacturerId;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_dependencies`(IN inManufacturerId INT)
BEGIN

  DECLARE manufacturerRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM product

    WHERE manufacturer_id = inManufacturerId

    INTO manufacturerRowsCount;



  SET dependenciesCount = dependenciesCount + manufacturerRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_distinct_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_distinct_list_get`()
BEGIN

    SELECT DISTINCT name FROM manufacturer

      ORDER BY name;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_get`(IN inManufacturerId INT)
BEGIN

  SELECT name FROM manufacturer

    WHERE manufacturer_id = inManufacturerId;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_insert`(IN inName VARCHAR(50))
BEGIN

  INSERT INTO manufacturer (name)

    VALUES (inName);

END$$

DROP PROCEDURE IF EXISTS `manufacturer_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_list_count`()
BEGIN

  SELECT COUNT(*) FROM manufacturer;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT manufacturer_id AS id, name FROM manufacturer

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_product_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_product_list_get`(IN inManufacturerId INT)
BEGIN

  SELECT product_id AS id, name FROM product

      WHERE manufacturer_id = inManufacturerId

      ORDER BY name;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_purchases_stadistics_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_purchases_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
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

          CAST(rec.date AS DATE) >= ? AND CAST(rec.date AS DATE) < ?) AS rec

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
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_sales_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
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

          CAST(inv.date AS DATE) >= ? AND CAST(inv.date AS DATE) < ?) AS inv

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

DROP PROCEDURE IF EXISTS `manufacturer_stadistics_labels_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_stadistics_labels_count`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50))
BEGIN

       SELECT COUNT(*) FROM (SELECT man.name FROM product pro INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         WHERE pro.deactivated = 0 ORDER BY man.name, pro.name) AS sales_stadistics WHERE name BETWEEN inFirst AND inLast;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_stadistics_labels_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_stadistics_labels_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

  "SELECT * FROM

    (SELECT pro.product_id AS id, pro.bar_code, man.name AS manufacturer, pro.name AS product FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro.deactivated = 0 ORDER BY man.name, pro.name) AS sales_stadistics

  WHERE manufacturer BETWEEN ? AND ? LIMIT ?, ?";

  SET @p1 = inFirst;

  SET @p2 = inLast;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `manufacturer_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_update`(IN inManufacturerId INT, IN inName VARCHAR(50))
BEGIN

  UPDATE manufacturer

    SET name = inName

    WHERE manufacturer_id = inManufacturerId;

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_delete`(IN inPaymentCardBrandId INT)
BEGIN

  DELETE FROM payment_card_brand

    WHERE payment_card_brand_id = inPaymentCardBrandId;

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_dependencies`(IN inPaymentCardBrandId INT)
BEGIN

  DECLARE paymentCardBrandRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM voucher

    WHERE payment_card_brand_id = inPaymentCardBrandId

    INTO paymentCardBrandRowsCount;



  SET dependenciesCount = dependenciesCount + paymentCardBrandRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_get`(IN inPaymentCardBrandId INT)
BEGIN

  SELECT name FROM payment_card_brand

    WHERE payment_card_brand_id = inPaymentCardBrandId;

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_insert`(IN inName VARCHAR(50))
BEGIN

  INSERT INTO payment_card_brand (name)

    VALUES (inName);

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_list_count`()
BEGIN

  SELECT COUNT(*) FROM payment_card_brand;

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT payment_card_brand_id AS id, name FROM payment_card_brand

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `payment_card_brand_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_update`(IN inPaymentCardBrandId INT, IN inName VARCHAR(50))
BEGIN

  UPDATE payment_card_brand

    SET name = inName

    WHERE payment_card_brand_id = inPaymentCardBrandId;

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_delete`(IN inPaymentCardTypeId INT)
BEGIN

  DELETE FROM payment_card_type

    WHERE payment_card_type_id = inPaymentCardTypeId;

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_dependencies`(IN inPaymentCardTypeId INT)
BEGIN

  DECLARE paymentCardTypeRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM voucher

    WHERE payment_card_type_id = inPaymentCardTypeId

    INTO paymentCardTypeRowsCount;



  SET dependenciesCount = dependenciesCount + paymentCardTypeRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_get`(IN inPaymentCardTypeId INT)
BEGIN

  SELECT name FROM payment_card_type

    WHERE payment_card_type_id = inPaymentCardTypeId;

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_insert`(IN inName VARCHAR(50))
BEGIN

  INSERT INTO payment_card_type (name)

    VALUES (inName);

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_list_count`()
BEGIN

  SELECT COUNT(*) FROM payment_card_type;

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT payment_card_type_id AS id, name FROM payment_card_type

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `payment_card_type_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_update`(IN inPaymentCardTypeId INT, IN inName VARCHAR(50))
BEGIN

  UPDATE payment_card_type

    SET name = inName

    WHERE payment_card_type_id = inPaymentCardTypeId;

END$$

DROP PROCEDURE IF EXISTS `product_available_quantity_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_available_quantity_get`(IN inProductId INT)
BEGIN

  SELECT quantity - reserved FROM product

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_balance_forward_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_balance_forward_get`(IN inProductId INT)
BEGIN

  SELECT balance_forward FROM product

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_bar_code_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_bar_code_exists`(IN inProductId INT, IN inBarCode VARCHAR(50))
BEGIN

  SELECT COUNT(*) FROM product

    WHERE product_id != inProductId AND bar_code = inBarCode;

END$$

DROP PROCEDURE IF EXISTS `product_bar_code_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_bar_code_update`(IN inProductId INT, IN inBarCode VARCHAR(50))
BEGIN
  UPDATE product
    SET bar_code = inBarCode
    WHERE product_id = inProductId;
END$$

DROP PROCEDURE IF EXISTS `product_bonus_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_bonus_list_get`(IN inProductId INT)
BEGIN

  SELECT bonus_id AS id, quantity, percentage, DATE_FORMAT(created_date, '%d/%m/%Y') AS created_date,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expired_date FROM bonus

    WHERE product_id = inProductId AND expiration_date > CURDATE()

    ORDER BY quantity;

END$$

DROP PROCEDURE IF EXISTS `product_counting_template_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_counting_template_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50))
BEGIN

  SELECT * FROM

    (SELECT pro.product_id AS id, pro.bar_code, man.name AS manufacturer, pro.name AS product FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro.deactivated = 0 ORDER BY pro.name, man.name) AS counting_template

  WHERE product BETWEEN inFirst AND inLast;

END$$

DROP PROCEDURE IF EXISTS `product_decrease_quantity`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_decrease_quantity`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET quantity = quantity - inQuantity

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_decrease_reserved`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_decrease_reserved`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET reserved = reserved - inQuantity

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_delete`(IN inProductId INT)
BEGIN

  DELETE FROM change_price_log

    WHERE product_id = inProductId;



  DELETE FROM product_supplier

    WHERE product_id = inProductId;



  DELETE FROM product

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_dependencies`(IN inProductId INT)
BEGIN

  DECLARE productRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM lot

    WHERE product_id = inProductId

    INTO productRowsCount;



  SET dependenciesCount = dependenciesCount + productRowsCount;



  SELECT COUNT(*) FROM bonus

    WHERE product_id = inProductId

    INTO productRowsCount;



  SET dependenciesCount = dependenciesCount + productRowsCount;



  SELECT COUNT(*) FROM comparison_product

    WHERE product_id = inProductId

    INTO productRowsCount;



  SET dependenciesCount = dependenciesCount + productRowsCount;



  SELECT COUNT(*) FROM count_product

    WHERE product_id = inProductId

    INTO productRowsCount;



  SET dependenciesCount = dependenciesCount + productRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `product_distinct_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_distinct_list_get`()
BEGIN

    SELECT DISTINCT name FROM product

      ORDER BY name;

END$$

DROP PROCEDURE IF EXISTS `product_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_get`(IN inProductId INT)
BEGIN

  SELECT bar_code, name, description, unit_of_measure_id, manufacturer_id, price, deactivated FROM product

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_id_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_id_get`(IN inBarCode VARCHAR(50))
BEGIN

  SELECT product_id FROM product

    WHERE bar_code = inBarCode AND deactivated != 1;

END$$

DROP PROCEDURE IF EXISTS `product_id_get_include_deactivated`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_id_get_include_deactivated`(IN inBarCode VARCHAR(50))
BEGIN

  SELECT product_id FROM product

    WHERE bar_code = inBarCode;

END$$

DROP PROCEDURE IF EXISTS `product_inactive_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_inactive_count`(IN inDate DATE, IN inDays INT)
BEGIN

  SELECT COUNT(*) FROM (SELECT 1 FROM product pro

      INNER JOIN lot ON lot.product_id = pro.product_id

      INNER JOIN invoice_lot inv_lot ON lot.lot_id = inv_lot.lot_id

      INNER JOIN invoice inv ON inv_lot.invoice_id = inv.invoice_id

      INNER JOIN (SELECT MAX(inv.invoice_id) AS invoice_id, lot.product_id FROM invoice inv

                      INNER JOIN invoice_lot inv_lot ON inv.invoice_id = inv_lot.invoice_id

                      INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

                    WHERE lot.product_id IN (SELECT product_id FROM product WHERE deactivated = 0) AND inv.status = 1

                      AND inv.date <= DATE_ADD(inDate, INTERVAL -1 * inDays DAY)

                    GROUP BY lot.product_id) AS last_inv

      ON pro.product_id = last_inv.product_id AND inv.invoice_id = last_inv.invoice_id

  GROUP BY pro.product_id) AS inactive_products;

END$$

DROP PROCEDURE IF EXISTS `product_inactive_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_inactive_get`(IN inDate DATE, IN inDays INT, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.bar_code, man.name AS manufacturer, pro.name, pro.quantity,

         DATE_FORMAT(inv.date, '%d/%m/%Y %H:%i:%s') AS last_sale, SUM(inv_lot.quantity) AS sale_quantity FROM product pro

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         INNER JOIN lot ON lot.product_id = pro.product_id

         INNER JOIN invoice_lot inv_lot ON lot.lot_id = inv_lot.lot_id

         INNER JOIN invoice inv ON inv_lot.invoice_id = inv.invoice_id

         INNER JOIN (SELECT MAX(inv.invoice_id) AS invoice_id, lot.product_id FROM invoice inv

                         INNER JOIN invoice_lot inv_lot ON inv.invoice_id = inv_lot.invoice_id

                         INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

                     WHERE lot.product_id IN (SELECT product_id FROM product WHERE deactivated = 0) AND inv.status = 1

                         AND inv.date <= DATE_ADD(?, INTERVAL -1 * ? DAY)

			 GROUP BY lot.product_id) AS last_inv

         ON pro.product_id = last_inv.product_id AND inv.invoice_id = last_inv.invoice_id

     GROUP BY pro.product_id

     ORDER BY inv.invoice_id, pro.name

     LIMIT ?, ?";

  SET @p1 = inDate;

  SET @p2 = inDays;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `product_increase_quantity`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_increase_quantity`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET quantity = quantity + inQuantity

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_increase_reserved`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_increase_reserved`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET reserved = reserved + inQuantity

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_insert`(IN inBarCode VARCHAR(50), IN inName VARCHAR(50),

  IN inDescription TEXT, IN inUnitOfMeasureId INT, IN inManufacturerId INT,

  IN inPrice DECIMAL(6, 2), IN inDeactivated TINYINT)
BEGIN

  INSERT INTO product (bar_code, name, description, unit_of_measure_id, manufacturer_id, price, deactivated)

    VALUES (inBarCode, inName, inDescription, inUnitOfMeasureId, inManufacturerId, inPrice, inDeactivated);

END$$

DROP PROCEDURE IF EXISTS `product_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_list_count`()
BEGIN

  SELECT COUNT(*) FROM product;

END$$

DROP PROCEDURE IF EXISTS `product_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.product_id AS id, pro.name, man.name AS manufacturer FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

      ORDER BY pro.name, man.name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `product_lot_available_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_available_get`(IN inProductId INT)
BEGIN

  SELECT lot_id, IFNULL(expiration_date, '9999-12-31') AS expiration_date FROM lot

    WHERE quantity - reserved > 0 AND product_id = inProductId

    ORDER BY expiration_date, lot_id;

END$$

DROP PROCEDURE IF EXISTS `product_lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_get`(IN inProductId INT)
BEGIN

  SELECT lot_id AS id, DATE_FORMAT(entry_date, '%d/%m/%Y') AS entry_date, DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date, price, 

      quantity, (quantity - reserved) AS available FROM lot

    WHERE quantity > 0 AND product_id = inProductId

    ORDER BY lot_id;

END$$

DROP PROCEDURE IF EXISTS `product_lot_total_available_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_total_available_get`(IN inProductId INT)
BEGIN

  SELECT SUM(quantity - reserved) FROM lot

    WHERE quantity > 0 AND product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_lot_total_quantity_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_total_quantity_get`(IN inProductId INT)
BEGIN

  SELECT SUM(quantity) FROM lot

    WHERE quantity > 0 AND product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_purchases_stadistics_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_purchases_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
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

          CAST(rec.date AS DATE) >= ? AND CAST(rec.date AS DATE) < ?) AS rec

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

DROP PROCEDURE IF EXISTS `product_quantity_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_quantity_get`(IN inProductId INT)
BEGIN

  SELECT quantity FROM product

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_sales_stadistics_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_sales_stadistics_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inFirstDate DATE, IN inLastDate DATE,
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

          CAST(inv.date AS DATE) >= ? AND CAST(inv.date AS DATE) < ?) AS inv

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

DROP PROCEDURE IF EXISTS `product_search`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_search`(IN inSearchString VARCHAR(50))
BEGIN

  SELECT pro.bar_code, pro.name, man.name AS manufacturer FROM product pro INNER JOIN manufacturer man

    ON pro.manufacturer_id = man.manufacturer_id

    WHERE pro.name LIKE CONCAT(inSearchString, '%') AND deactivated != 1

    ORDER BY pro.name, man.name;

END$$

DROP PROCEDURE IF EXISTS `product_search_include_deactivated`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_search_include_deactivated`(IN inSearchString VARCHAR(50))
BEGIN

  SELECT pro.bar_code, pro.name, man.name AS manufacturer FROM product pro INNER JOIN manufacturer man

    ON pro.manufacturer_id = man.manufacturer_id

    WHERE pro.name LIKE CONCAT(inSearchString, '%')

    ORDER BY pro.name, man.name;

END$$

DROP PROCEDURE IF EXISTS `product_stadistics_labels_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_stadistics_labels_count`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50))
BEGIN

       SELECT COUNT(*) FROM (SELECT pro.name FROM product pro INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         WHERE pro.deactivated = 0 ORDER BY pro.name, man.name) AS sales_stadistics WHERE name BETWEEN inFirst AND inLast;

END$$

DROP PROCEDURE IF EXISTS `product_stadistics_labels_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_stadistics_labels_get`(IN inFirst VARCHAR(50), IN inLast VARCHAR(50), IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

  "SELECT * FROM

    (SELECT pro.product_id AS id, pro.bar_code, man.name AS manufacturer, pro.name AS product FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro.deactivated = 0 ORDER BY pro.name, man.name) AS sales_stadistics

  WHERE product BETWEEN ? AND ? LIMIT ?, ?";

  SET @p1 = inFirst;

  SET @p2 = inLast;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `product_stock_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_stock_count`()
BEGIN

  SELECT COUNT(*) FROM product WHERE (quantity - reserved) > 0;

END$$

DROP PROCEDURE IF EXISTS `product_stock_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_stock_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.bar_code, man.name AS manufacturer, pro.name, (pro.quantity - pro.reserved) AS available
       FROM product pro INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id
       WHERE (pro.quantity - pro.reserved) > 0
       ORDER BY (pro.quantity - pro.reserved) DESC, pro.name, man.name LIMIT ?, ?";

  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `product_stock_include_monetary_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_stock_include_monetary_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.bar_code, man.name AS manufacturer, pro.name, (pro.quantity - pro.reserved) AS available, pro.price,
         (pro.price * (pro.quantity - pro.reserved)) AS total
       FROM product pro INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id
       WHERE (pro.quantity - pro.reserved) > 0
       ORDER BY (pro.quantity - pro.reserved) DESC, pro.name, man.name LIMIT ?, ?";

  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;

  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `product_stock_total_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_stock_total_get`()
BEGIN

  SELECT SUM((quantity - reserved) * price) AS total FROM product WHERE (quantity - reserved) > 0;

END$$

DROP PROCEDURE IF EXISTS `product_supplier_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_delete`(IN inProductId INT, IN inSupplierId INT,
IN inSku VARCHAR(50))
BEGIN

  DELETE FROM product_supplier

    WHERE product_id = inProductId AND supplier_id = inSupplierId AND sku = inSku;

END$$

DROP PROCEDURE IF EXISTS `product_supplier_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_exists`(IN inSupplierId INT, IN inSku VARCHAR(50))
BEGIN

  SELECT COUNT(*) FROM product_supplier

    WHERE supplier_id = inSupplierId AND sku = inSku;

END$$

DROP PROCEDURE IF EXISTS `product_supplier_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_get`(IN inProductId INT)
BEGIN

  SELECT supplier_id, sku FROM product_supplier

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `product_supplier_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_insert`(IN inProductId INT, IN inSupplierId INT, IN inSku VARCHAR(50))
BEGIN

  INSERT INTO product_supplier (product_id, supplier_id, sku)

    VALUES (inProductId, inSupplierId, inSku);

END$$

DROP PROCEDURE IF EXISTS `product_supplier_product_id_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_product_id_get`(IN inSupplierId INT, IN inSku VARCHAR(50))
BEGIN

  SELECT product_id FROM product_supplier

    WHERE supplier_id = inSupplierId AND sku = inSku;

END$$

DROP PROCEDURE IF EXISTS `product_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_update`(IN inProductId INT, IN inBarCode VARCHAR(50), IN inName VARCHAR(50),

  IN inDescription TEXT, IN inUnitOfMeasureId INT, IN inManufacturerId INT,

  IN inPrice DECIMAL(6, 2), IN inDeactivated TINYINT)
BEGIN

  UPDATE product

    SET bar_code = inBarCode, name = inName, description = inDescription,

      unit_of_measure_id = inUnitOfMeasureId, manufacturer_id = inManufacturerId, price = inPrice, deactivated = inDeactivated

    WHERE product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `purchase_return_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_cancel`(IN inPurchaseReturnId INT, IN inUserName VARCHAR(10), IN inDate DATETIME)
BEGIN

  UPDATE purchase_return

    SET status = 2

    WHERE purchase_return_id = inPurchaseReturnId;



  INSERT INTO purchase_return_cancelled (purchase_return_id, user_account_username, date)

    VALUES (inPurchaseReturnId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `purchase_return_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_get`(IN inPurchaseReturnId INT)
BEGIN

  SELECT user_account_username, supplier_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, contact, total, status

      FROM purchase_return

    WHERE purchase_return_id = inPurchaseReturnId;

END$$

DROP PROCEDURE IF EXISTS `purchase_return_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_insert`(IN inUserName VARCHAR(10), IN inSupplierId INT, IN inDate DATETIME,

  IN inReason VARCHAR(100), IN inContact VARCHAR(50), IN inTotal DECIMAL(13, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO purchase_return (user_account_username, supplier_id, date, reason, contact, total, status)

    VALUES (inUserName, inSupplierId, inDate, inReason, inContact, inTotal, inStatus);

END$$

DROP PROCEDURE IF EXISTS `purchase_return_lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_lot_get`(IN inPurchaseReturnId INT)
BEGIN

  SELECT lot_id, quantity, price FROM purchase_return_lot

      WHERE purchase_return_id = inPurchaseReturnId

      ORDER BY number;

END$$

DROP PROCEDURE IF EXISTS `purchase_return_lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(6, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO purchase_return_lot (purchase_return_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

DROP PROCEDURE IF EXISTS `purchase_return_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM purchase_return

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `purchase_return_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT purchase_return_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM purchase_return

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `receipt_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_cancel`(IN inReceiptId INT, IN inUserName VARCHAR(10),

  IN inDate DATETIME)
BEGIN

  UPDATE receipt

    SET status = 2

    WHERE receipt_id = inReceiptId;



  INSERT INTO receipt_cancelled (receipt_id, user_account_username, date)

    VALUES (inReceiptId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `receipt_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_get`(IN inReceiptId INT)
BEGIN

  SELECT user_account_username, supplier_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, shipment_number, total, status

      FROM receipt

    WHERE receipt_id = inReceiptId;

END$$

DROP PROCEDURE IF EXISTS `receipt_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_insert`(IN inUserName VARCHAR(10), IN inSupplierId INT, IN inDate DATETIME,

  IN inShipmentNumber VARCHAR(50), IN inTotal DECIMAL(13, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO receipt (user_account_username, supplier_id, date, shipment_number, total, status)

    VALUES (inUserName, inSupplierId, inDate, inShipmentNumber, inTotal, inStatus);

END$$

DROP PROCEDURE IF EXISTS `receipt_lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_lot_get`(IN inReceiptId INT)
BEGIN

  SELECT lot_id, quantity, price FROM receipt_lot

      WHERE receipt_id = inReceiptId

      ORDER BY number;

END$$

DROP PROCEDURE IF EXISTS `receipt_lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(6, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO receipt_lot (receipt_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

DROP PROCEDURE IF EXISTS `receipt_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM receipt

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `receipt_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT receipt_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM receipt

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `reserve_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_delete`(IN inReserveId INT)
BEGIN

  DELETE FROM reserve

    WHERE reserve_id = inReserveId;

END$$

DROP PROCEDURE IF EXISTS `reserve_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_get`(IN inReserveId INT)
BEGIN

  SELECT user_account_username, lot_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, quantity FROM reserve

    WHERE reserve_id = inReserveId;

END$$

DROP PROCEDURE IF EXISTS `reserve_increase_quantity`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_increase_quantity`(IN inReserveId INT, IN inQuantity INT)
BEGIN

  UPDATE reserve

    SET quantity = quantity + inQuantity

    WHERE reserve_id = inReserveId;

END$$

DROP PROCEDURE IF EXISTS `reserve_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_insert`(IN inUserName VARCHAR(10), IN inLotId INT, IN inDate DATETIME, IN inQuantity INT)
BEGIN

  INSERT INTO reserve (user_account_username, lot_id, date, quantity)

    VALUES (inUserName, inLotId, inDate, inQuantity);

END$$

DROP PROCEDURE IF EXISTS `reserve_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_list_get`(IN inProductId INT)
BEGIN

  SELECT res.reserve_id AS id, DATE_FORMAT(res.date, '%d/%m/%Y %H:%i:%s') AS created_date, res.user_account_username AS username, lot.lot_id,

      res.quantity FROM reserve res INNER JOIN lot ON res.lot_id = lot.lot_id

    WHERE lot.product_id = inProductId;

END$$

DROP PROCEDURE IF EXISTS `resolution_log_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `resolution_log_insert`(IN inResolutionNumber VARCHAR(50), IN inResolutionDate DATE, IN inSerialNumber VARCHAR(10), IN inInitialNumber BIGINT, IN inFinalNumber BIGINT, IN inCreatedDate DATE, IN inDocumentType VARCHAR(10))
BEGIN

  INSERT INTO resolution_log (resolution_number, resolution_date, serial_number, initial_number, final_number, created_date, document_type)

    VALUES (inResolutionNumber, inResolutionDate, inSerialNumber, inInitialNumber, inFinalNumber, inCreatedDate, inDocumentType);

END$$

DROP PROCEDURE IF EXISTS `role_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_get`(IN inRoleId INT)
BEGIN

  SELECT name FROM role

    WHERE role_id = inRoleId;

END$$

DROP PROCEDURE IF EXISTS `role_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_list_count`()
BEGIN

  SELECT COUNT(*) FROM role;

END$$

DROP PROCEDURE IF EXISTS `role_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT role_id AS id, name FROM role

      ORDER BY id

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `role_subject_action_value_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_subject_action_value_get`(IN inRoleId INT, IN inSubjectId INT, IN inActionId INT)
BEGIN
  SELECT value FROM role_subject_action
    WHERE role_id = inRoleId AND subject_id = inSubjectId AND action_id = inActionId;
END$$

DROP PROCEDURE IF EXISTS `root_change_password`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `root_change_password`(IN inPassword VARCHAR(50))
BEGIN

  UPDATE root

    SET password = inPassword;

END$$

DROP PROCEDURE IF EXISTS `root_is_valid`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `root_is_valid`(IN inPassword VARCHAR(50))
BEGIN

  DECLARE rootRowsCount INT;



  SELECT COUNT(*) FROM root

    WHERE password = inPassword

    INTO rootRowsCount;



  IF rootRowsCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `sales_ledger_correlatives_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ledger_correlatives_get`(IN inDate DATE)
BEGIN

  SELECT inv.correlative_id, cor.serial_number FROM invoice inv INNER JOIN correlative cor
      ON inv.correlative_id = cor.correlative_id
    WHERE DATE_FORMAT( inv.date, '%Y-%m-%d') = inDate
    GROUP BY inv.correlative_id
    ORDER BY cor.serial_number;

END$$

DROP PROCEDURE IF EXISTS `sales_ledger_dates_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ledger_dates_get`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT DISTINCT DATE_FORMAT(inv.date, '%Y-%m-%d') AS date, DATE_FORMAT(inv.date, '%d/%m/%Y') AS formatted_date FROM invoice inv
      WHERE DATE_FORMAT(inv.date, '%Y-%m-%d') >= inStartDate AND DATE_FORMAT(inv.date, '%Y-%m-%d') <= inEndDate
    GROUP BY inv.date
    ORDER BY inv.date;

END$$

DROP PROCEDURE IF EXISTS `sales_ledger_max_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ledger_max_get`(IN inDate DATE, IN inCorrelativeId INT)
BEGIN

  SELECT MAX(number) FROM invoice
    WHERE correlative_id = inCorrelativeId AND DATE_FORMAT(date, '%Y-%m-%d') = inDate;

END$$

DROP PROCEDURE IF EXISTS `sales_ledger_min_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ledger_min_get`(IN inDate DATE, IN inCorrelativeId INT)
BEGIN

  SELECT MIN(number) FROM invoice
    WHERE correlative_id = inCorrelativeId AND DATE_FORMAT(date, '%Y-%m-%d') = inDate;

END$$

DROP PROCEDURE IF EXISTS `sales_ledger_sum_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ledger_sum_get`(IN inCorrelativeId INT, IN inFirstNumber BIGINT, IN inLastNumber BIGINT)
BEGIN

  SELECT SUM(inv.total - (inv.total * (IFNULL(dis.percentage, 0) / 100))) AS total FROM invoice inv LEFT JOIN discount dis
      ON inv.invoice_id = dis.invoice_id
    WHERE inv.correlative_id = inCorrelativeId AND inv.number BETWEEN inFirstNumber AND inLastNumber;

END$$

DROP PROCEDURE IF EXISTS `sales_ranking_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ranking_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

       SELECT COUNT(*) FROM (SELECT 1 FROM invoice inv INNER JOIN invoice_lot inv_lot

         ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id INNER JOIN product pro ON

         lot.product_id = pro.product_id WHERE inv.status = 1 AND pro.deactivated = 0 AND

       CAST(inv.date AS DATE) BETWEEN inFirstDate AND inLastDate GROUP BY pro.product_id) AS sales_ranking;

END$$

DROP PROCEDURE IF EXISTS `sales_ranking_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_ranking_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  SET @rank := 0;

  PREPARE statement FROM

    "SELECT * FROM (SELECT @rank := @rank + 1 AS rank, pro.bar_code, man.name AS manufacturer, pro.name AS name,

         SUM(inv_lot.quantity) AS quantity

         FROM invoice inv INNER JOIN invoice_lot inv_lot

         ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id INNER JOIN product pro ON

         lot.product_id = pro.product_id INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         WHERE inv.status = 1 AND pro.deactivated = 0 AND

         CAST(inv.date AS DATE) BETWEEN ? AND ? GROUP BY pro.product_id ORDER BY quantity DESC, name) AS sales_ranking

      LIMIT ?, ?";

  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;


  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `sales_report_deposits_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_deposits_get`(IN inCashRegisterId INT)
BEGIN

  SELECT deposit_id AS id, bank_account_number, number, IF(status = 2, 0, total) as total, status FROM deposit

    WHERE cash_register_id = inCashRegisterId;

END$$

DROP PROCEDURE IF EXISTS `sales_report_invoices_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_invoices_get`(IN inCashRegisterId INT)
BEGIN

  SELECT cor.serial_number, inv.number, inv.name,

      IF(inv.status = 2, 0, inv.total) AS sub_total,

      IF(inv.status = 2, 0, @discount_percentage := IFNULL(dis.percentage, 0)) AS discount_percentage,

      IF(inv.status = 2, 0, @discount_value := inv.total * (@discount_percentage / 100)) AS discount_value,

      IF(inv.status = 2, 0, inv.total - @discount_value) AS total,

      IF(inv.status = 2, 0, cr.cash) AS cash,

      IF(inv.status = 2, 0, cr.total_vouchers) AS total_vouchers, status FROM invoice inv 

      INNER JOIN cash_receipt cr ON inv.invoice_id = cr.cash_receipt_id INNER JOIN correlative cor ON inv.correlative_id = cor.correlative_id

      LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cash_register_id = inCashRegisterId;

END$$

DROP PROCEDURE IF EXISTS `sales_report_total_cash`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_cash`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(cash) FROM cash_receipt cr

    INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId AND inv.status = 1;

END$$

DROP PROCEDURE IF EXISTS `sales_report_total_deposits`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_deposits`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(total) FROM deposit

    WHERE cash_register_id = inCashRegisterId AND status = 1;

END$$

DROP PROCEDURE IF EXISTS `sales_report_total_discount`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_discount`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(inv.total * (dis.percentage / 100)) FROM invoice inv

      INNER JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId AND inv.status = 1;

END$$

DROP PROCEDURE IF EXISTS `sales_report_total_vat`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_vat`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM((inv.total - inv.total * (IFNULL(dis.percentage, 0) / 100)) * (inv.vat / 100)) FROM invoice inv

    LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cash_register_id = inCashRegisterId AND status = 1;

END$$

DROP PROCEDURE IF EXISTS `sales_report_total_vouchers`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_vouchers`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(total_vouchers) FROM cash_receipt cr

    INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId AND inv.status = 1;

END$$

DROP PROCEDURE IF EXISTS `shift_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_delete`(IN inShiftId INT)
BEGIN

  DELETE FROM shift

    WHERE shift_id = inShiftId;

END$$

DROP PROCEDURE IF EXISTS `shift_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_dependencies`(IN inShiftId INT)
BEGIN

  DECLARE shiftRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM cash_register

    WHERE shift_id = inShiftId

    INTO shiftRowsCount;



  SET dependenciesCount = dependenciesCount + shiftRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `shift_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_get`(IN inShiftId INT)
BEGIN

  SELECT name, time_table FROM shift

    WHERE shift_id = inShiftId;

END$$

DROP PROCEDURE IF EXISTS `shift_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_insert`(IN inName VARCHAR(50), IN inTimeTable VARCHAR(50))
BEGIN

  INSERT INTO shift (name, time_table)

    VALUES (inName, inTimeTable);

END$$

DROP PROCEDURE IF EXISTS `shift_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_list_count`()
BEGIN

  SELECT COUNT(*) FROM shift;

END$$

DROP PROCEDURE IF EXISTS `shift_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT shift_id AS id, name, time_table FROM shift

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `shift_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_update`(IN inShiftId INT, IN inName VARCHAR(50), IN inTimeTable VARCHAR(50))
BEGIN

  UPDATE shift

    SET name = inName, time_table = inTimeTable

    WHERE shift_id = inShiftId;

END$$

DROP PROCEDURE IF EXISTS `shipment_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_cancel`(IN inShipmentId INT, IN inUserName VARCHAR(10),

  IN inDate DATETIME)
BEGIN

  UPDATE shipment

    SET status = 2

    WHERE shipment_id = inShipmentId;



  INSERT INTO shipment_cancelled (shipment_id, user_account_username, date)

    VALUES (inShipmentId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `shipment_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_get`(IN inShipmentId INT)
BEGIN

  SELECT user_account_username, branch_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, contact, total, status

      FROM shipment

    WHERE shipment_id = inShipmentId;

END$$

DROP PROCEDURE IF EXISTS `shipment_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_insert`(IN inUserName VARCHAR(10), IN inBranchId INT,

  IN inDate DATETIME, IN inContact VARCHAR(50), IN inTotal DECIMAL(13, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO shipment (user_account_username, branch_id, date, contact, total, status)

    VALUES (inUserName, inBranchId, inDate, inContact, inTotal, inStatus);

END$$

DROP PROCEDURE IF EXISTS `shipment_lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_lot_get`(IN inShipmentId INT)
BEGIN

  SELECT lot_id, quantity, price FROM shipment_lot

      WHERE shipment_id = inShipmentId

      ORDER BY number;

END$$

DROP PROCEDURE IF EXISTS `shipment_lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(6, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO shipment_lot (shipment_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

DROP PROCEDURE IF EXISTS `shipment_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM shipment

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `shipment_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT shipment_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM shipment

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `subject_id_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `subject_id_get`(IN inName VARCHAR(50))
BEGIN
  SELECT subject_id FROM subject
    WHERE name = inName;
END$$

DROP PROCEDURE IF EXISTS `supplier_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_delete`(IN inSupplierId INT)
BEGIN

  DELETE FROM supplier

    WHERE supplier_id = inSupplierId;

END$$

DROP PROCEDURE IF EXISTS `supplier_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_dependencies`(IN inSupplierId INT)
BEGIN

  DECLARE supplierRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM product_supplier

    WHERE supplier_id = inSupplierId

    INTO supplierRowsCount;



  SET dependenciesCount = dependenciesCount + supplierRowsCount;



  SELECT COUNT(*) FROM receipt

    WHERE supplier_id = inSupplierId

    INTO supplierRowsCount;



  SET dependenciesCount = dependenciesCount + supplierRowsCount;



  SELECT COUNT(*) FROM purchase_return

    WHERE supplier_id = inSupplierId

    INTO supplierRowsCount;



  SET dependenciesCount = dependenciesCount + supplierRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `supplier_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_get`(IN inSupplierId INT)
BEGIN

  SELECT name, nit, telephone, address, email, contact FROM supplier

    WHERE supplier_id = inSupplierId;

END$$

DROP PROCEDURE IF EXISTS `supplier_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_insert`(IN inName VARCHAR(50), IN inNit VARCHAR(15), IN inTelephone VARCHAR(50),

  IN inAddress VARCHAR(100), IN inEmail VARCHAR(50), IN inContact VARCHAR(50))
BEGIN

  INSERT INTO supplier (name, nit, telephone, address, email, contact)

    VALUES (inName, inNit, inTelephone, inAddress, inEmail, inContact);

END$$

DROP PROCEDURE IF EXISTS `supplier_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_list_count`()
BEGIN

  SELECT COUNT(*) FROM supplier;

END$$

DROP PROCEDURE IF EXISTS `supplier_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT supplier_id AS id, name FROM supplier

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `supplier_product_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_product_list_get`(IN inSupplierId INT)
BEGIN

  SELECT DISTINCT pro_sup.product_id AS id, pro.name, man.name AS manufacturer FROM product_supplier pro_sup INNER JOIN product pro

         ON pro_sup.product_id = pro.product_id INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro_sup.supplier_id = inSupplierId

     ORDER BY pro.name, man.name;

END$$

DROP PROCEDURE IF EXISTS `supplier_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_update`(IN inSupplierId INT, IN inName VARCHAR(50),

  IN inNit VARCHAR(15), IN inTelephone VARCHAR(50), IN inAddress VARCHAR(100),

  IN inEmail VARCHAR(50), IN inContact VARCHAR(50))
BEGIN

  UPDATE supplier

  SET name = inName, nit = inNit, telephone = inTelephone, address = inAddress,

    email = inEmail, contact = inContact

  WHERE supplier_id = inSupplierId;

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_delete`(IN inUnitOfMeasureId INT)
BEGIN

  DELETE FROM unit_of_measure

    WHERE unit_of_measure_id = inUnitOfMeasureId;

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_dependencies`(IN inUnitOfMeasureId INT)
BEGIN

  DECLARE unitOfMeasureRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM product

    WHERE unit_of_measure_id = inUnitOfMeasureId

    INTO unitOfMeasureRowsCount;



  SET dependenciesCount = dependenciesCount + unitOfMeasureRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_get`(IN inUnitOfMeasureId INT)
BEGIN

  SELECT name FROM unit_of_measure

    WHERE unit_of_measure_id = inUnitOfMeasureId;

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_insert`(IN inName VARCHAR(50))
BEGIN

  INSERT INTO unit_of_measure (name)

    VALUES (inName);

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_list_count`()
BEGIN

  SELECT COUNT(*) FROM unit_of_measure;

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT unit_of_measure_id AS id, name FROM unit_of_measure

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `unit_of_measure_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_update`(IN inUnitOfMeasureId INT, IN inName VARCHAR(50))
BEGIN

  UPDATE unit_of_measure

    SET name = inName

    WHERE unit_of_measure_id = inUnitOfMeasureId;

END$$

DROP PROCEDURE IF EXISTS `user_account_change_password`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_change_password`(IN inUserName VARCHAR(10), IN inPassword VARCHAR(50))
BEGIN

  UPDATE user_account

    SET password = inPassword

    WHERE user_account_username = inUserName;

END$$

DROP PROCEDURE IF EXISTS `user_account_delete`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_delete`(IN inUserName VARCHAR(10))
BEGIN

  DELETE FROM user_account

    WHERE user_account_username = inUserName;

END$$

DROP PROCEDURE IF EXISTS `user_account_dependencies`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_dependencies`(IN inUserName VARCHAR(10))
BEGIN

  DECLARE userAccountRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM change_price_log

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM purchase_return

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM purchase_return_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM shipment

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM shipment_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM invoice

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM invoice_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM count

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM reserve

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM comparison

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM deposit

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM deposit_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM entry_adjustment

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM entry_adjustment_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM receipt

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM receipt_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM withdraw_adjustment

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM withdraw_adjustment_cancelled

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  SELECT COUNT(*) FROM discount

    WHERE user_account_username = inUserName

    INTO userAccountRowsCount;



  SET dependenciesCount = dependenciesCount + userAccountRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `user_account_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_exists`(IN inUserName VARCHAR(10))
BEGIN

  SELECT COUNT(*) FROM user_account

  WHERE user_account_username = inUserName;

END$$

DROP PROCEDURE IF EXISTS `user_account_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_get`(IN inUserName VARCHAR(10))
BEGIN

  SELECT role_id, first_name, last_name, deactivated FROM user_account

    WHERE user_account_username = inUserName;

END$$

DROP PROCEDURE IF EXISTS `user_account_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_insert`(IN inUserName VARCHAR(10), IN inRoleId INT, IN inFirstName VARCHAR(50),

  IN inLastName VARCHAR(50), IN inPassword VARCHAR(50), IN inDeactivated TINYINT)
BEGIN

  INSERT INTO user_account (user_account_username, role_id, first_name, last_name, password, deactivated)

    VALUES (inUserName, inRoleId, inFirstName, inLastName, inPassword, inDeactivated);

END$$

DROP PROCEDURE IF EXISTS `user_account_is_valid`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_is_valid`(IN inUserName VARCHAR(10), IN inPassword VARCHAR(50))
BEGIN

  DECLARE userRowsCount INT;



  SELECT COUNT(*) FROM user_account

    WHERE user_account_username = inUserName AND password = inPassword AND deactivated = 0

    INTO userRowsCount;



  IF userRowsCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

DROP PROCEDURE IF EXISTS `user_account_list_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_list_count`()
BEGIN

  SELECT COUNT(*) FROM user_account;

END$$

DROP PROCEDURE IF EXISTS `user_account_list_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT user_account_username AS id, first_name, last_name FROM user_account

      ORDER BY first_name, last_name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

DROP PROCEDURE IF EXISTS `user_account_no_password_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_no_password_update`(IN inUserName VARCHAR(10), IN inRoleId INT, IN inFirstName VARCHAR(50),

  IN inLastName VARCHAR(50), IN inDeactivated TINYINT)
BEGIN

  UPDATE user_account

    SET role_id = inRoleId, first_name = inFirstName, last_name = inLastName, deactivated = inDeactivated

    WHERE user_account_username = inUserName;

END$$

DROP PROCEDURE IF EXISTS `user_account_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_update`(IN inUserName VARCHAR(10), IN inRoleId INT, IN inFirstName VARCHAR(50),

  IN inLastName VARCHAR(50), IN inPassword VARCHAR(50), IN inDeactivated TINYINT)
BEGIN

  UPDATE user_account

    SET role_id = inRoleId, first_name = inFirstName, last_name = inLastName, password = inPassword, deactivated = inDeactivated

    WHERE user_account_username = inUserName;

END$$

DROP PROCEDURE IF EXISTS `vat_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `vat_get`()
BEGIN

  SELECT percentage FROM vat;

END$$

DROP PROCEDURE IF EXISTS `vat_update`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `vat_update`(IN inPercentage DECIMAL(4, 2))
BEGIN

  UPDATE vat

    SET percentage = inPercentage;

END$$

DROP PROCEDURE IF EXISTS `voucher_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `voucher_get`(IN inCashReceiptId INT)
BEGIN

  SELECT transaction, amount, payment_card_number, payment_card_type_id, payment_card_brand_id, name,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date FROM voucher

    WHERE cash_receipt_id = inCashReceiptId

    ORDER BY voucher_id;

END$$

DROP PROCEDURE IF EXISTS `voucher_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `voucher_insert`(IN inCashReceiptId INT, IN inTransaction VARCHAR(50), IN inAmount DECIMAL(13, 2),

  IN inPaymentCardNumber INT, IN inPaymentCardTypeId INT, IN inPaymentCardBrandId INT, IN inName VARCHAR(50),

  IN inExpirationDate DATE)
BEGIN

  INSERT INTO voucher (cash_receipt_id, transaction, amount, payment_card_number, payment_card_type_id, payment_card_brand_id,

      name, expiration_date)

    VALUES (inCashReceiptId, inTransaction, inAmount, inPaymentCardNumber, inPaymentCardTypeId, inPaymentCardBrandId, inName,

      inExpirationDate);

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_cancel`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_cancel`(IN inWithdrawAdjustmentId INT, IN inUserName VARCHAR(10),

  IN inDate DATETIME)
BEGIN

  UPDATE withdraw_adjustment

    SET status = 2

    WHERE withdraw_adjustment_id = inWithdrawAdjustmentId;



  INSERT INTO withdraw_adjustment_cancelled (withdraw_adjustment_id, user_account_username, date)

    VALUES (inWithdrawAdjustmentId, inUserName, inDate);

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_get`(IN inWithdrawAdjustmentId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, total, status

      FROM withdraw_adjustment

    WHERE withdraw_adjustment_id = inWithdrawAdjustmentId;

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_insert`(IN inUserName VARCHAR(10), IN inDate DATETIME,

  IN inReason VARCHAR(100), IN inTotal DECIMAL(13, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO withdraw_adjustment (user_account_username, date, reason, total, status)

    VALUES (inUserName, inDate, inReason, inTotal, inStatus);

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_lot_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_lot_get`(IN inWithdrawAdjustmentId INT)
BEGIN

  SELECT lot_id, quantity, price FROM withdraw_adjustment_lot

      WHERE withdraw_adjustment_id = inWithdrawAdjustmentId

      ORDER BY number;

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_lot_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(6, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO withdraw_adjustment_lot (withdraw_adjustment_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_search_count`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM withdraw_adjustment

    WHERE CAST(date AS DATE) BETWEEN inStartDate AND inEndDate;

END$$

DROP PROCEDURE IF EXISTS `withdraw_adjustment_search_get`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT withdraw_adjustment_id AS id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM withdraw_adjustment

      WHERE CAST(date AS DATE) BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

DROP PROCEDURE IF EXISTS `working_day_close`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_close`(IN inWorkingDay DATE)
BEGIN

  UPDATE working_day

    SET open = 0

    WHERE working_day = inWorkingDay;

END$$

DROP PROCEDURE IF EXISTS `working_day_close_cash_registers`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_close_cash_registers`(IN inWorkingDay DATE)
BEGIN

  UPDATE cash_register

    SET open = 0

    WHERE working_day = inWorkingDay;

END$$

DROP PROCEDURE IF EXISTS `working_day_exists`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_exists`(IN inWorkingDay DATE)
BEGIN

  SELECT COUNT(*) FROM working_day

    WHERE working_day = inWorkingDay;

END$$

DROP PROCEDURE IF EXISTS `working_day_insert`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_insert`(IN inWorkingDay DATE)
BEGIN

  INSERT INTO working_day (working_day)

    VALUES (inWorkingDay);

END$$

DROP PROCEDURE IF EXISTS `working_day_is_open`$$
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_is_open`(IN inWorkingDay DATE)
BEGIN

  SELECT open FROM working_day

    WHERE working_day = inWorkingDay;

END$$

DELIMITER ;
