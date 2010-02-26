-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-11-2009 a las 13:39:40
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

CREATE TABLE IF NOT EXISTS `action` (
  `action_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `action`
--

INSERT INTO `action` (`action_id`, `name`) VALUES
(1, 'access'),
(2, 'write'),
(3, 'cancel');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank`
--

CREATE TABLE IF NOT EXISTS `bank` (
  `bank_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `bank`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_account`
--

CREATE TABLE IF NOT EXISTS `bank_account` (
  `bank_account_number` varchar(100) collate utf8_unicode_ci NOT NULL,
  `bank_id` int(11) NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`bank_account_number`),
  KEY `idx_bank_account_bank_id` (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `bank_account`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonus`
--

CREATE TABLE IF NOT EXISTS `bonus` (
  `bonus_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `created_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY  (`bonus_id`),
  KEY `idx_bonus_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `bonus`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `branch_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(50) collate utf8_unicode_ci NOT NULL,
  `address` varchar(150) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci default NULL,
  `contact` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `branch`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_receipt`
--

CREATE TABLE IF NOT EXISTS `cash_receipt` (
  `cash_receipt_id` int(11) NOT NULL,
  `change_amount` decimal(10,2) NOT NULL,
  `cash` decimal(10,2) NOT NULL,
  `total_vouchers` decimal(10,2) NOT NULL,
  `reserved` decimal(10,2) NOT NULL default '0.00',
  `deposited` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`cash_receipt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `cash_receipt`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_register`
--

CREATE TABLE IF NOT EXISTS `cash_register` (
  `cash_register_id` int(11) NOT NULL auto_increment,
  `working_day` date NOT NULL,
  `shift_id` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`cash_register_id`),
  UNIQUE KEY `unique_working_day_shift_id` (`working_day`,`shift_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `cash_register`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `change_price_log`
--

CREATE TABLE IF NOT EXISTS `change_price_log` (
  `entry_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `last_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`entry_id`),
  KEY `idx_change_price_log_user_account_username` (`user_account_username`),
  KEY `idx_change_price_log_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `change_price_log`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `nit` varchar(10) collate utf8_unicode_ci NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `company`
--

INSERT INTO `company` (`nit`, `name`) VALUES
('', '999');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comparison`
--

CREATE TABLE IF NOT EXISTS `comparison` (
  `comparison_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(150) collate utf8_unicode_ci NOT NULL,
  `general` tinyint(1) NOT NULL,
  `physical_total` int(11) NOT NULL,
  `system_total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`comparison_id`),
  KEY `idx_comparison_user_account_username` (`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `comparison`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comparison_product`
--

CREATE TABLE IF NOT EXISTS `comparison_product` (
  `comparison_product_id` int(11) NOT NULL auto_increment,
  `comparison_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `physical` int(11) NOT NULL,
  `system` int(11) NOT NULL,
  PRIMARY KEY  (`comparison_product_id`),
  UNIQUE KEY `unique_comparison_id_product_id` (`comparison_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `comparison_product`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correlative`
--

CREATE TABLE IF NOT EXISTS `correlative` (
  `serial_number` varchar(10) collate utf8_unicode_ci NOT NULL,
  `resolution_number` varchar(100) collate utf8_unicode_ci NOT NULL,
  `resolution_date` date NOT NULL,
  `initial_number` bigint(20) NOT NULL,
  `final_number` bigint(20) NOT NULL,
  `current` bigint(20) NOT NULL default '0',
  `is_default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`serial_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `correlative`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `count`
--

CREATE TABLE IF NOT EXISTS `count` (
  `count_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(150) collate utf8_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY  (`count_id`),
  KEY `idx_count_user_account_username` (`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `count`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `count_product`
--

CREATE TABLE IF NOT EXISTS `count_product` (
  `count_product_id` int(11) NOT NULL auto_increment,
  `count_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY  (`count_product_id`),
  UNIQUE KEY `unique_count_id_product_id` (`count_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `count_product`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`nit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `customer`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposit`
--

CREATE TABLE IF NOT EXISTS `deposit` (
  `deposit_id` int(11) NOT NULL auto_increment,
  `bank_account_number` varchar(100) collate utf8_unicode_ci NOT NULL,
  `cash_register_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`deposit_id`),
  KEY `idx_deposit_bank_account_number` (`bank_account_number`),
  KEY `idx_deposit_cash_register_id` (`cash_register_id`),
  KEY `idx_deposit_user_account_username` (`user_account_username`),
  KEY `idx_deposit_date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `deposit`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposit_cancelled`
--

CREATE TABLE IF NOT EXISTS `deposit_cancelled` (
  `deposit_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`deposit_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `deposit_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposit_cash_receipt`
--

CREATE TABLE IF NOT EXISTS `deposit_cash_receipt` (
  `deposit_cash_receipt_id` int(11) NOT NULL auto_increment,
  `deposit_id` int(11) NOT NULL,
  `cash_receipt_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`deposit_cash_receipt_id`),
  UNIQUE KEY `unique_deposit_id_cash_receipt_id` (`deposit_id`,`cash_receipt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `deposit_cash_receipt`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount`
--

CREATE TABLE IF NOT EXISTS `discount` (
  `invoice_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  PRIMARY KEY  (`invoice_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `discount`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_adjustment`
--

CREATE TABLE IF NOT EXISTS `entry_adjustment` (
  `entry_adjustment_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(150) collate utf8_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`entry_adjustment_id`),
  KEY `idx_entry_adjustment_user_account_username` (`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `entry_adjustment`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_adjustment_cancelled`
--

CREATE TABLE IF NOT EXISTS `entry_adjustment_cancelled` (
  `entry_adjustment_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`entry_adjustment_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `entry_adjustment_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_adjustment_lot`
--

CREATE TABLE IF NOT EXISTS `entry_adjustment_lot` (
  `entry_adjustment_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`entry_adjustment_id`,`lot_id`),
  UNIQUE KEY `unique_entry_adjustment_id_number` (`entry_adjustment_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `entry_adjustment_lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
  `invoice_id` int(11) NOT NULL auto_increment,
  `serial_number` varchar(10) collate utf8_unicode_ci NOT NULL,
  `number` bigint(20) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci default NULL,
  `total` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) NOT NULL,
  `cash_register_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`invoice_id`),
  UNIQUE KEY `unique_serial_number_number` (`serial_number`,`number`),
  KEY `idx_invoice_user_account_username` (`user_account_username`),
  KEY `idx_invoice_cash_register_id` (`cash_register_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `invoice`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_bonus`
--

CREATE TABLE IF NOT EXISTS `invoice_bonus` (
  `invoice_id` int(11) NOT NULL,
  `bonus_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`invoice_id`,`bonus_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `invoice_bonus`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_cancelled`
--

CREATE TABLE IF NOT EXISTS `invoice_cancelled` (
  `invoice_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`invoice_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `invoice_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_lot`
--

CREATE TABLE IF NOT EXISTS `invoice_lot` (
  `invoice_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`invoice_id`,`lot_id`),
  UNIQUE KEY `unique_invoice_id_number` (`invoice_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `invoice_lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lot`
--

CREATE TABLE IF NOT EXISTS `lot` (
  `lot_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `expiration_date` date default NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reserved` int(11) NOT NULL default '0',
  PRIMARY KEY  (`lot_id`),
  KEY `idx_lot_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manufacturer`
--

CREATE TABLE IF NOT EXISTS `manufacturer` (
  `manufacturer_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`manufacturer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `manufacturer`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_card_brand`
--

CREATE TABLE IF NOT EXISTS `payment_card_brand` (
  `payment_card_brand_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`payment_card_brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `payment_card_brand`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_card_type`
--

CREATE TABLE IF NOT EXISTS `payment_card_type` (
  `payment_card_type_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`payment_card_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `payment_card_type`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL auto_increment,
  `bar_code` varchar(100) collate utf8_unicode_ci default NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `packaging` varchar(150) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci,
  `unit_of_measure_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `deactivated` tinyint(1) NOT NULL,
  `quantity` int(11) NOT NULL default '0',
  `reserved` int(11) NOT NULL default '0',
  `balance_foward` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`),
  UNIQUE KEY `unique_bar_code` (`bar_code`),
  KEY `idx_product_unit_of_measure_id` (`unit_of_measure_id`),
  KEY `idx_product_manufacturer_id` (`manufacturer_id`),
  KEY `idx_product_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `product`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_supplier`
--

CREATE TABLE IF NOT EXISTS `product_supplier` (
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `sku` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`product_id`,`supplier_id`,`sku`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `product_supplier`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return`
--

CREATE TABLE IF NOT EXISTS `purchase_return` (
  `purchase_return_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(150) collate utf8_unicode_ci NOT NULL,
  `contact` varchar(100) collate utf8_unicode_ci default NULL,
  `total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`purchase_return_id`),
  KEY `idx_purchase_return_user_account_username` (`user_account_username`),
  KEY `idx_purchase_return_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `purchase_return`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return_cancelled`
--

CREATE TABLE IF NOT EXISTS `purchase_return_cancelled` (
  `purchase_return_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`purchase_return_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `purchase_return_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_return_lot`
--

CREATE TABLE IF NOT EXISTS `purchase_return_lot` (
  `purchase_return_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`purchase_return_id`,`lot_id`),
  UNIQUE KEY `unique_purchase_return_id_number` (`purchase_return_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `purchase_return_lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipt`
--

CREATE TABLE IF NOT EXISTS `receipt` (
  `receipt_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `shipment_number` varchar(50) collate utf8_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`receipt_id`),
  KEY `idx_receipt_user_account_username` (`user_account_username`),
  KEY `idx_receipt_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `receipt`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipt_cancelled`
--

CREATE TABLE IF NOT EXISTS `receipt_cancelled` (
  `receipt_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`receipt_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `receipt_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipt_lot`
--

CREATE TABLE IF NOT EXISTS `receipt_lot` (
  `receipt_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`receipt_id`,`lot_id`),
  UNIQUE KEY `unique_receipt_id_number` (`receipt_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `receipt_lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserve`
--

CREATE TABLE IF NOT EXISTS `reserve` (
  `reserve_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `lot_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY  (`reserve_id`),
  KEY `idx_reserve_user_account_username` (`user_account_username`),
  KEY `idx_reserve_lot_id` (`lot_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Volcar la base de datos para la tabla `reserve`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `role`
--

INSERT INTO `role` (`role_id`, `name`) VALUES
(1, 'Administrador'),
(2, 'Operador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_subject_action`
--

CREATE TABLE IF NOT EXISTS `role_subject_action` (
  `role_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `value` tinyint(1) NOT NULL,
  PRIMARY KEY  (`role_id`,`subject_id`,`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `role_subject_action`
--

INSERT INTO `role_subject_action` (`role_id`, `subject_id`, `action_id`, `value`) VALUES
(1, 1, 1, 1),
(1, 2, 2, 1),
(1, 3, 2, 1),
(1, 4, 2, 1),
(1, 5, 2, 1),
(1, 6, 2, 1),
(1, 7, 2, 1),
(1, 8, 2, 1),
(1, 7, 3, 1),
(1, 8, 3, 1),
(1, 9, 2, 1),
(1, 9, 3, 1),
(1, 10, 2, 1),
(1, 10, 3, 1),
(1, 11, 2, 1),
(1, 11, 3, 1),
(1, 12, 2, 1),
(1, 13, 2, 1),
(1, 14, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `root`
--

CREATE TABLE IF NOT EXISTS `root` (
  `password` varchar(50) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `root`
--

INSERT INTO `root` (`password`) VALUES
('4ba8ee3256001cc06025ac392d9b1c98051bff09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shift`
--

CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `time_table` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`shift_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `shift`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment`
--

CREATE TABLE IF NOT EXISTS `shipment` (
  `shipment_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `contact` varchar(100) collate utf8_unicode_ci default NULL,
  `total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`shipment_id`),
  KEY `idx_shipment_user_account_username` (`user_account_username`),
  KEY `idx_shipment_branch_id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `shipment`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_cancelled`
--

CREATE TABLE IF NOT EXISTS `shipment_cancelled` (
  `shipment_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`shipment_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `shipment_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_lot`
--

CREATE TABLE IF NOT EXISTS `shipment_lot` (
  `shipment_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`shipment_id`,`lot_id`),
  UNIQUE KEY `unique_shipment_id_number` (`shipment_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `shipment_lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`subject_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `subject`
--

INSERT INTO `subject` (`subject_id`, `name`) VALUES
(1, 'operations'),
(2, 'manufacturer'),
(3, 'unit_of_measure'),
(4, 'product'),
(5, 'supplier'),
(6, 'branch'),
(7, 'receipt'),
(8, 'entry_adjustment'),
(9, 'withdraw_adjustment'),
(10, 'shipment'),
(11, 'purchase_return'),
(12, 'bonus'),
(13, 'count'),
(14, 'comparison');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `supplier_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `nit` varchar(15) collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(50) collate utf8_unicode_ci NOT NULL,
  `address` varchar(150) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci default NULL,
  `contact` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `supplier`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unit_of_measure`
--

CREATE TABLE IF NOT EXISTS `unit_of_measure` (
  `unit_of_measure_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`unit_of_measure_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `unit_of_measure`
--

INSERT INTO `unit_of_measure` (`unit_of_measure_id`, `name`) VALUES
(1, 'Unidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_account`
--

CREATE TABLE IF NOT EXISTS `user_account` (
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `password` varchar(50) collate utf8_unicode_ci NOT NULL,
  `deactivated` tinyint(1) NOT NULL,
  PRIMARY KEY  (`user_account_username`),
  KEY `idx_user_account_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `user_account`
--

INSERT INTO `user_account` (`user_account_username`, `role_id`, `first_name`, `last_name`, `password`, `deactivated`) VALUES
('roboli', 1, 'Roberto', 'Oliveros', '36a9fc86e20ec727f782d1e27f877303d866fbbd', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vat`
--

CREATE TABLE IF NOT EXISTS `vat` (
  `percentage` decimal(5,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `vat`
--

INSERT INTO `vat` (`percentage`) VALUES
(12.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher`
--

CREATE TABLE IF NOT EXISTS `voucher` (
  `voucher_id` int(11) NOT NULL auto_increment,
  `cash_receipt_id` int(11) NOT NULL,
  `transaction` varchar(100) collate utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_card_number` int(11) NOT NULL,
  `payment_card_type_id` int(11) NOT NULL,
  `payment_card_brand_id` int(11) NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY  (`voucher_id`),
  UNIQUE KEY `unique_cash_receipt_id_transaction` (`cash_receipt_id`,`transaction`),
  KEY `idx_voucher_payment_card_type_id` (`payment_card_type_id`),
  KEY `idx_voucher_payment_card_brand_id` (`payment_card_brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `voucher`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdraw_adjustment`
--

CREATE TABLE IF NOT EXISTS `withdraw_adjustment` (
  `withdraw_adjustment_id` int(11) NOT NULL auto_increment,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `reason` varchar(150) collate utf8_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`withdraw_adjustment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `withdraw_adjustment`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdraw_adjustment_cancelled`
--

CREATE TABLE IF NOT EXISTS `withdraw_adjustment_cancelled` (
  `withdraw_adjustment_id` int(11) NOT NULL,
  `user_account_username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`withdraw_adjustment_id`,`user_account_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `withdraw_adjustment_cancelled`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdraw_adjustment_lot`
--

CREATE TABLE IF NOT EXISTS `withdraw_adjustment_lot` (
  `withdraw_adjustment_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`withdraw_adjustment_id`,`lot_id`),
  UNIQUE KEY `unique_withdraw_adjustment_id_number` (`withdraw_adjustment_id`,`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `withdraw_adjustment_lot`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `working_day`
--

CREATE TABLE IF NOT EXISTS `working_day` (
  `working_day` date NOT NULL,
  `open` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`working_day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcar la base de datos para la tabla `working_day`
--


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
CREATE DEFINER=`999_user`@`localhost` PROCEDURE `action_id_get`(IN inName VARCHAR(50))
BEGIN
  SELECT action_id FROM action
    WHERE name = inName;
END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_delete`(IN inBankAccountNumber VARCHAR(100))
BEGIN

  DELETE FROM bank_account

    WHERE bank_account_number = inBankAccountNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_dependencies`(IN inBankAccountNumber VARCHAR(100))
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_exists`(IN inBankAccountNumber VARCHAR(100))
BEGIN

  SELECT COUNT(*) FROM bank_account

  WHERE bank_account_number = inBankAccountNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_get`(IN inBankAccountNumber VARCHAR(100))
BEGIN

  SELECT bank_id, name FROM bank_account

    WHERE bank_account_number = inBankAccountNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_insert`(IN inBankAccountNumber VARCHAR(100), IN inBankId INT, IN inName VARCHAR(100))
BEGIN

  INSERT INTO bank_account (bank_account_number, bank_id, name)

    VALUES (inBankAccountNumber, inBankId, inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_list_count`()
BEGIN

  SELECT COUNT(*) FROM bank_account;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT bank_account_number, name FROM bank_account

      ORDER BY bank_account_number

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_account_update`(IN inBankAccountNumber VARCHAR(100), IN inBankId INT, IN inName VARCHAR(100))
BEGIN

  UPDATE bank_account

    SET bank_id = inBankId, name = inName

    WHERE bank_account_number = inBankAccountNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_delete`(IN inBankId INT)
BEGIN

  DELETE FROM bank

    WHERE bank_id = inBankId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_get`(IN inBankId INT)
BEGIN

  SELECT name FROM bank

    WHERE bank_id = inBankId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_insert`(IN inName VARCHAR(100))
BEGIN

  INSERT INTO bank (name)

    VALUES (inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_list_count`()
BEGIN

  SELECT COUNT(*) FROM bank;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT bank_id, name FROM bank

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bank_update`(IN inBankId INT, IN inName VARCHAR(100))
BEGIN

  UPDATE bank

    SET name = inName

    WHERE bank_id = inBankId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_delete`(IN inBonusId INT)
BEGIN

  DELETE FROM bonus

    WHERE bonus_id = inBonusId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_exists`(IN inProductId INT, IN inQuantity INT)
BEGIN

  SELECT COUNT(*) FROM bonus

    WHERE product_id = inProductId AND quantity = inQuantity AND expiration_date > CURDATE();

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_get`(IN inBonusId INT)
BEGIN

  SELECT product_id, quantity, percentage, DATE_FORMAT(created_date, '%d/%m/%Y') AS created_date,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date FROM bonus

    WHERE bonus_id = inBonusId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_id_get`(IN inProductId INT, IN inQuantity INT)
BEGIN

  SELECT bonus_id FROM bonus

    WHERE product_id = inProductId AND quantity = inQuantity AND expiration_date > CURDATE();

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `bonus_insert`(IN inProductId INT, IN inQuantity INT, IN inPercentage DECIMAL(10, 2), IN inCreatedDate DATE,

  IN inExpirationDate DATE)
BEGIN

  INSERT INTO bonus (product_id, quantity, percentage, created_date, expiration_date)

    VALUES (inProductId, inQuantity, inPercentage, inCreatedDate, inExpirationDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_delete`(IN inBranchId INT)
BEGIN

  DELETE FROM branch

    WHERE branch_id = inBranchId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_get`(IN inBranchId INT)
BEGIN

  SELECT name, nit, telephone, address, email, contact FROM branch

    WHERE branch_id = inBranchId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_insert`(IN inName VARCHAR(100), IN inNit VARCHAR(15), IN inTelephone VARCHAR(50),

  IN inAddress VARCHAR(150), IN inEmail VARCHAR(100), IN inContact VARCHAR(100))
BEGIN

  INSERT INTO branch (name, nit, telephone, address, email, contact)

    VALUES (inName, inNit, inTelephone, inAddress, inEmail, inContact);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_list_count`()
BEGIN

  SELECT COUNT(*) FROM branch;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `branch_update`(IN inBranchId INT, IN inName VARCHAR(100),

  IN inNit VARCHAR(15), IN inTelephone VARCHAR(50), IN inAddress VARCHAR(150),

  IN inEmail VARCHAR(100), IN inContact VARCHAR(100))
BEGIN

  UPDATE branch

  SET name = inName, nit = inNit, telephone = inTelephone, address = inAddress,

    email = inEmail, contact = inContact

  WHERE branch_id = inBranchId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_available_list_get`(IN inCashRegisterId INT)
BEGIN

  SELECT cash_receipt_id, cash AS received_cash, (cash - (reserved + deposited)) AS available_cash FROM cash_receipt cr

      INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_cash_available_get`(IN inCashReceiptId INT)
BEGIN

  SELECT (cash - (reserved + deposited)) AS available FROM cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_cash_get`(IN inCashReceiptId INT)
BEGIN

  SELECT cash FROM cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_decrease_deposited`(IN inCashReceiptId INT, IN inAmount DECIMAL(10, 2))
BEGIN

  UPDATE cash_receipt

    SET deposited = deposited - inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_decrease_reserved`(IN inCashReceiptId INT, IN inAmount DECIMAL(10, 2))
BEGIN

  UPDATE cash_receipt

    SET reserved = reserved - inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_exists`(IN inCashReceiptId INT)
BEGIN

  SELECT COUNT(*) FROM cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_get`(IN inCashReceiptId INT)
BEGIN

  SELECT change_amount, total_vouchers FROM cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_increase_deposited`(IN inCashReceiptId INT, IN inAmount DECIMAL(10, 2))
BEGIN

  UPDATE cash_receipt

    SET deposited = deposited + inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_increase_reserved`(IN inCashReceiptId INT, IN inAmount DECIMAL(10, 2))
BEGIN

  UPDATE cash_receipt

    SET reserved = reserved + inAmount

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_receipt_insert`(IN inCashReceiptId INT, IN inChange DECIMAL(10, 2), IN inCash DECIMAL(10, 2),

  IN inTotalVouchers DECIMAL(10, 2))
BEGIN

  INSERT INTO cash_receipt (cash_receipt_id, change_amount, cash, total_vouchers)

    VALUES (inCashReceiptId, inChange, inCash, inTotalVouchers);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_close`(IN inCashRegisterId INT)
BEGIN

  UPDATE cash_register

    SET open = 0

    WHERE cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_get`(IN inCashRegisterId INT)
BEGIN

  SELECT shift_id FROM cash_register

    WHERE cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_insert`(IN inWorkingDay DATE, IN inShiftId INT)
BEGIN

  INSERT INTO cash_register (working_day, shift_id)

    VALUES (inWorkingDay, inShiftId);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_is_open`(IN inCashRegisterId INT)
BEGIN

  SELECT open FROM cash_register

    WHERE cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `cash_register_working_day_shift_get`(IN inWorkingDay DATE, IN inShiftId INT)
BEGIN

  SELECT cash_register_id FROM cash_register

    WHERE working_day = inWorkingDay AND shift_id = inShiftId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `change_price_log_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT COUNT(*) FROM change_price_log

    WHERE date BETWEEN inFirstDate AND inLastDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `change_price_log_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT DATE_FORMAT(log.date, '%d/%m/%Y') AS logged_date, log.user_account_username, pro.bar_code, man.name AS manufacturer, pro.name,

         pro.packaging, log.last_price, log.new_price FROM change_price_log log INNER JOIN product pro ON log.product_id = pro.product_id

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `change_price_log_insert`(IN inUserName VARCHAR(50), IN inProductId INT, IN inDate DATE,

  IN inLastPrice DECIMAL(10, 2), IN inNewPrice DECIMAL(10, 2))
BEGIN

  INSERT INTO change_price_log (user_account_username, product_id, date, last_price, new_price)

    VALUES (inUserName, inProductId, inDate, inLastPrice, inNewPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `company_get`()
BEGIN

  SELECT nit, name FROM company;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `company_update`(IN inNit VARCHAR(10), IN inName VARCHAR(100))
BEGIN

  UPDATE company

    SET nit = inNit, name = inName;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_get`(IN inComparisonId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, general, physical_total, system_total

      FROM comparison

    WHERE comparison_id = inComparisonId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_insert`(IN inUserName VARCHAR(50), IN inDate DATETIME, IN inReason VARCHAR(150), IN inGeneral TINYINT,

  IN inPhysicalTotal INT)
BEGIN

  INSERT INTO comparison (user_account_username, date, reason, general, physical_total)

    VALUES (inUserName, inDate, inReason, inGeneral, inPhysicalTotal);

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_product_get`(IN inComparisonId INT)
BEGIN

  SELECT product_id, physical, system FROM comparison_product

      WHERE comparison_id = inComparisonId

      ORDER BY comparison_product_id;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM comparison

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `comparison_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_default_serial_number`()
BEGIN

  SELECT serial_number FROM correlative

    WHERE is_default = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_delete`(IN inSerialNumber VARCHAR(10))
BEGIN

  DELETE FROM correlative

    WHERE serial_number = inSerialNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_dependencies`(IN inSerialNumber VARCHAR(10))
BEGIN

  DECLARE correlativeRowsCount INT;

  DECLARE dependenciesCount INT DEFAULT 0;



  SELECT COUNT(*) FROM invoice

    WHERE serial_number = inSerialNumber

    INTO correlativeRowsCount;



  SET dependenciesCount = dependenciesCount + correlativeRowsCount;



  IF dependenciesCount > 0 THEN

    SELECT 1;

  ELSE

    SELECT 0;

  END IF;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_exists`(IN inSerialNumber VARCHAR(10))
BEGIN

  SELECT COUNT(*) FROM correlative

  WHERE serial_number = inSerialNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_get`(IN inSerialNumber VARCHAR(10))
BEGIN

  SELECT resolution_number, DATE_FORMAT(resolution_date, '%d/%m/%Y') AS resolution_date, initial_number, final_number,

      current, is_default FROM correlative

    WHERE serial_number = inSerialNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_insert`(IN inSerialNumber VARCHAR(10), IN inResolutionNumber VARCHAR(100), IN inResolutionDate Date,

  IN inInitialNumber BIGINT, IN inFinalNumber BIGINT)
BEGIN

  INSERT INTO correlative (serial_number, resolution_number, resolution_date, initial_number, final_number)

    VALUES (inSerialNumber, inResolutionNumber, inResolutionDate, inInitialNumber, inFinalNumber);

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_list_count`()
BEGIN

  SELECT COUNT(*) FROM correlative;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT serial_number, is_default FROM correlative

      ORDER BY serial_number

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_make_default`(IN inSerialNumber VARCHAR(10))
BEGIN

  UPDATE correlative

    SET is_default = 0;



  UPDATE correlative

    SET is_default = 1

    WHERE serial_number = inSerialNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `correlative_next_number`(IN inSerialNumber VARCHAR(10))
BEGIN

  DECLARE initialNumber BIGINT;

  DECLARE currentNumber BIGINT;



  SELECT initial_number, current FROM correlative

    WHERE serial_number = inSerialNumber

    INTO initialNumber, currentNumber;



  IF currentNumber = 0 THEN

    UPDATE correlative

      SET current = initialNumber

      WHERE serial_number = inSerialNumber;

    

    SELECT initialNumber;

  ELSE

    SELECT currentNumber + 1;



    UPDATE correlative

      SET current = current + 1

      WHERE serial_number = inSerialNumber;

  END IF;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_delete`(IN inCountId INT)
BEGIN

  DELETE FROM count_product

    WHERE count_id = inCountId;



  DELETE FROM count

    WHERE count_id = inCountId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_get`(IN inCountId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, total FROM count

    WHERE count_id = inCountId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_insert`(IN inUserName VARCHAR(50), IN inDate DATETIME, IN inReason VARCHAR(150), IN inTotal INT)
BEGIN

  INSERT INTO count (user_account_username, date, reason, total)

    VALUES (inUserName, inDate, inReason, inTotal);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_product_delete`(IN inCountId INT, IN inProductId INT)
BEGIN

  DELETE FROM count_product

    WHERE count_id = inCountId AND product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_product_get`(IN inCountId INT)
BEGIN

  SELECT product_id, quantity FROM count_product

    WHERE count_id = inCountId

    ORDER BY count_product_id;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_product_insert`(IN inCountId INT, IN inProductId INT, IN inQuantity INT)
BEGIN

  INSERT INTO count_product (count_id, product_id, quantity)

    VALUES (inCountId, inProductId, inQuantity);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM count

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `count_update`(IN inCountId INT, IN inTotal INT)
BEGIN

  UPDATE count

    SET total = inTotal

    WHERE count_id = inCountId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_exists`(IN inNit VARCHAR(15))
BEGIN

  SELECT COUNT(*) FROM customer

  WHERE nit = inNit;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_get`(IN inNit VARCHAR(15))
BEGIN

  SELECT name FROM customer

    WHERE nit = inNit;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_insert`(IN inNit VARCHAR(15), IN inName VARCHAR(100))
BEGIN

  INSERT INTO customer (nit, name)

    VALUES(inNit, inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `customer_update`(IN inNit VARCHAR(15), IN inName VARCHAR(100))
BEGIN

  UPDATE customer

  SET name = inName

  WHERE nit = inNit;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cancel`(IN inDepositId INT, IN inUserName VARCHAR(50), IN inDate DATE)
BEGIN

  UPDATE deposit

    SET status = 2

    WHERE deposit_id = inDepositId;



  INSERT INTO deposit_cancelled (deposit_id, user_account_username, date)

    VALUES (inDepositId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_list_get`(IN inCashReceiptId INT)
BEGIN

  SELECT deposit_id FROM deposit_cash_receipt

    WHERE cash_receipt_id = inCashReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_receipt_count`(IN inDepositId INT)
BEGIN

  SELECT COUNT(*) FROM deposit_cash_receipt

    WHERE deposit_id = inDepositId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_cash_receipt_insert`(IN inDepositId INT, IN inCashReceiptId INT, IN inAmount DECIMAL(10, 2))
BEGIN

  INSERT INTO deposit_cash_receipt (deposit_id, cash_receipt_id, amount)

    VALUES (inDepositId, inCashReceiptId, inAmount);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_confirm`(IN inDepositId INT)
BEGIN

  UPDATE deposit

    SET status = 3

    WHERE deposit_id = inDepositId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_get`(IN inDepositId INT)
BEGIN

  SELECT bank_account_number, cash_register_id, user_account_username, DATE_FORMAT(date, '%d/%m/%Y') AS created_date, number,

    total, status FROM deposit

    WHERE deposit_id = inDepositId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_insert`(IN inBankAccountNumber VARCHAR(100), IN inCashRegisterId INT, IN inUserName VARCHAR(50),

  IN inDate DATE, IN inNumber VARCHAR(50), IN inTotal DECIMAL(10, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO deposit (bank_account_number, cash_register_id, user_account_username, date, number, total, status)

    VALUES (inBankAccountNumber, inCashRegisterId, inUserName, inDate, inNumber, inTotal, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_list_get`(IN inCashRegisterId INT)
BEGIN

  SELECT deposit_id FROM deposit

    WHERE cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_pending_list_count`()
BEGIN

  SELECT COUNT(*) FROM deposit

    WHERE status = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_pending_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT dep.deposit_id, DATE_FORMAT(dep.date, '%d/%m/%Y') AS created_date, dep.bank_account_number, dep.number, 

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `deposit_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM deposit

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_get`(IN inInvoiceId INT)
BEGIN

  SELECT user_account_username, percentage FROM discount

    WHERE invoice_id = inInvoiceId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_insert`(IN inInvoiceId INT, IN inUserName VARCHAR(50), IN inPercentage DECIMAL(10, 2))
BEGIN

  INSERT INTO discount (invoice_id, user_account_username, percentage)

    VALUES (inInvoiceId, inUserName, inPercentage);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_list_count`(IN inFirstDate DATE, IN inLastDate DATE)
BEGIN

  SELECT COUNT(*) FROM discount dis INNER JOIN invoice inv ON dis.invoice_id = inv.invoice_id

    WHERE inv.status = 1 AND inv.date BETWEEN inFirstDate AND inLastDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `discount_list_get`(IN inFirstDate DATE, IN inLastDate DATE, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT DATE_FORMAT(inv.date, '%d/%m/%Y %H:%i:%s') AS created_date, dis.user_account_username, inv.serial_number,

         inv.number, inv.total AS subtotal, dis.percentage, inv.total * (dis.percentage / 100) AS amount,

         inv.total - inv.total * (dis.percentage / 100) AS total FROM discount dis INNER JOIN invoice inv 

         ON dis.invoice_id = inv.invoice_id

     WHERE inv.status = 1 AND inv.date BETWEEN ? AND ?

     ORDER BY inv.invoice_id

     LIMIT ?, ?";



  SET @p1 = inFirstDate;

  SET @p2 = inLastDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_cancel`(IN inEntryAdjustmentId INT, IN inUserName VARCHAR(50),

  IN inDate DATE)
BEGIN

  UPDATE entry_adjustment

    SET status = 2

    WHERE entry_adjustment_id = inEntryAdjustmentId;



  INSERT INTO entry_adjustment_cancelled (entry_adjustment_id, user_account_username, date)

    VALUES (inEntryAdjustmentId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_get`(IN inEntryAdjustmentId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, total, status

      FROM entry_adjustment

    WHERE entry_adjustment_id = inEntryAdjustmentId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_insert`(IN inUserName VARCHAR(50), IN inDate DATETIME,

  IN inReason VARCHAR(150), IN inTotal DECIMAL(10, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO entry_adjustment (user_account_username, date, reason, total, status)

    VALUES (inUserName, inDate, inReason, inTotal, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_lot_get`(IN inEntryAdjustmentId INT)
BEGIN

  SELECT lot_id, quantity, price FROM entry_adjustment_lot

      WHERE entry_adjustment_id =  inEntryAdjustmentId

      ORDER BY number;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(10, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO entry_adjustment_lot (entry_adjustment_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM entry_adjustment

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `entry_adjustment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `general_closure`(IN inDays INT)
BEGIN

  DECLARE productId INT;

  DECLARE balanceFoward INT;

  DECLARE finalBalance INT;

  DECLARE fromDate DATE;



  DECLARE done INT DEFAULT 0;

  DECLARE c1 CURSOR FOR SELECT product_id FROM product;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;



  SET fromDate = DATE_ADD(CURDATE(), INTERVAL -1 * inDays day);



  OPEN c1;

  FETCH c1 INTO productId;

  WHILE NOT done DO

    SELECT balance_foward FROM product WHERE product_id = productId INTO balanceFoward;

    CALL kardex_balance_foward_from_date_get(productId, balanceFoward, fromDate, finalBalance);

    IF finalBalance != 0 THEN
      UPDATE product
        SET balance_foward = finalBalance
        WHERE product_id = productId;
    END IF;

    FETCH c1 INTO productId;

  END WHILE;

  CLOSE c1;



  CALL delete_all_movements(fromDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `general_sales_report_cash_registers_get`(IN inWorkingDay DATE)
BEGIN

  SELECT cr.cash_register_id, sf.name, sf.time_table,

      SUM(inv.total - inv.total * (IFNULL(dis.percentage, 0) / 100)) AS total FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      INNER JOIN shift sf ON cr.shift_id = sf.shift_id

      LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cr.working_day = inWorkingDay AND inv.status = 1

    GROUP BY cr.cash_register_id;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `general_sales_report_total`(IN inWorkingDay DATE)
BEGIN

  SELECT SUM(inv.total - inv.total * (IFNULL(dis.percentage, 0) / 100)) FROM invoice inv

      INNER JOIN cash_register cr ON inv.cash_register_id = cr.cash_register_id

      LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cr.working_day = inWorkingDay AND inv.status = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `get_last_insert_id`()
BEGIN

  SELECT LAST_INSERT_ID();

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_bonus_insert`(IN inInvoiceId INT, IN inBonusId INT, IN inNumber INT, IN inPrice DECIMAL(10, 2))
BEGIN

  INSERT INTO invoice_bonus (invoice_id, bonus_id, number, price)

    VALUES (inInvoiceId, inBonusId, inNumber, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_cancel`(IN inInvoiceId INT, IN inUserName VARCHAR(50),

  IN inDate DATE)
BEGIN

  UPDATE invoice

    SET status = 2

    WHERE invoice_id = inInvoiceId;



  INSERT INTO invoice_cancelled (invoice_id, user_account_username, date)

    VALUES (inInvoiceId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_get`(IN inInvoiceId INT)
BEGIN

  SELECT serial_number, number, user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, nit, name, total, vat,

      cash_register_id, status FROM invoice

    WHERE invoice_id = inInvoiceId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_id_get`(IN inSerialNumber VARCHAR(10), IN inNumber BIGINT)
BEGIN

  SELECT invoice_id FROM invoice

    WHERE serial_number = inSerialNumber AND number = inNumber;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_insert`(IN inSerialNumber VARCHAR(10), IN inNumber BIGINT,

  IN inUserName VARCHAR(50), IN inDate DATETIME, IN inNit VARCHAR(15), IN inName VARCHAR(100), IN inTotal DECIMAL(10, 2),

  IN inVat DECIMAL(10, 2), IN inCashRegisterId INT, IN inStatus TINYINT)
BEGIN

  INSERT INTO invoice (serial_number, number, user_account_username, date, nit, name, total, vat, cash_register_id, status)

    VALUES (inSerialNumber, inNumber, inUserName, inDate, inNit, inName, inTotal, inVat, inCashRegisterId, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_items_count`(IN inInvoiceId INT)
BEGIN

  DECLARE lotRowsCount INT;

  DECLARE bonusRowsCount INT;

  DECLARE totalCount INT DEFAULT 0;



  SELECT COUNT(*) FROM invoice_lot

    WHERE invoice_id = inInvoiceId

    INTO lotRowsCount;



  SET totalCount = totalCount + lotRowsCount;



  SELECT COUNT(*) FROM invoice_bonus

    WHERE invoice_id = inInvoiceId

    INTO bonusRowsCount;



  SET totalCount = totalCount + bonusRowsCount;



  SELECT totalCount;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_items_get`(IN inInvoiceId INT, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "(SELECT invoice_id, lot_id, NULL AS bonus_id, number, quantity, price FROM invoice_lot

        WHERE invoice_id = ?)

      UNION

     (SELECT invoice_id, NULL AS lot_id , bonus_id, number, 1 AS quantity, price FROM invoice_bonus

        WHERE invoice_id =?)

      ORDER BY number

      LIMIT ?, ?";



  SET @p1 = inInvoiceId;

  SET @p2 = inInvoiceId;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_list_get`(IN inCashRegisterId INT)
BEGIN

  SELECT invoice_id FROM invoice

    WHERE cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(10, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO invoice_lot (invoice_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM invoice

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `invoice_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT invoice_id, serial_number, number, DATE_FORMAT(date, '%d/%m/%Y') AS created_date FROM invoice

      WHERE date BETWEEN ? AND ?

      ORDER BY date

      LIMIT ?, ?";



  SET @p1 = inStartDate;

  SET @p2 = inEndDate;

  SET @p3 = inStartItem;

  SET @p4 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3, @p4;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_balance_foward_from_date_get`(IN inProductId INT, IN inBalanceFoward INT,

  IN inDate DATE, OUT finalBalance INT)
BEGIN

  SET @balance := inBalanceFoward;



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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_balance_foward_get`(IN inProductId INT, IN inLastItem INT,

  IN inBalanceFoward INT)
BEGIN

  SET @balance := inBalanceFoward;



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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `kardex_get`(IN inProductId INT, IN inStartItem INT, IN inItemsPerPage INT,

  IN inBalanceFoward INT)
BEGIN

  SET @balance := inBalanceFoward;



  PREPARE statement FROM

    "SELECT created_date, document, number, lot_id, entry, withdraw,

         @balance := @balance + CAST(entry AS SIGNED) - CAST(withdraw AS SIGNED) AS balance FROM

       (SELECT DATE_FORMAT(inv.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Factura' AS document,

            CONCAT(inv.serial_number, '-', inv.number) AS number, inv_lot.lot_id, '' AS entry, inv_lot.quantity AS withdraw,

            inv_lot.number AS item_number FROM invoice inv INNER JOIN invoice_lot inv_lot

            ON inv.invoice_id = inv_lot.invoice_id INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

        WHERE inv.status = 1 AND lot.product_id = ?

        UNION

        SELECT DATE_FORMAT(rec.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Recibo' AS document, rec.receipt_id AS number,

            rec_lot.lot_id, rec_lot.quantity AS entry, '' AS withdraw, rec_lot.number AS item_number FROM receipt rec INNER

            JOIN receipt_lot rec_lot ON rec.receipt_id = rec_lot.receipt_id INNER JOIN lot ON rec_lot.lot_id = lot.lot_id

        WHERE rec.status = 1 AND lot.product_id = ?

        UNION

        SELECT DATE_FORMAT(pur.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Devolucion' AS document,

            pur.purchase_return_id AS number, pur_lot.lot_id, '' AS entry, pur_lot.quantity AS withdraw,

            pur_lot.number AS item_number FROM purchase_return pur INNER JOIN purchase_return_lot pur_lot ON

            pur.purchase_return_id = pur_lot.purchase_return_id INNER JOIN lot ON pur_lot.lot_id = lot.lot_id

        WHERE pur.status = 1 AND lot.product_id = ?

        UNION

        SELECT DATE_FORMAT(shi.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Envio' AS document, shi.shipment_id AS number,

            shi_lot.lot_id, '' AS entry, shi_lot.quantity AS withdraw, shi_lot.number AS item_number FROM shipment shi INNER

            JOIN shipment_lot shi_lot ON shi.shipment_id = shi_lot.shipment_id INNER JOIN lot ON shi_lot.lot_id = lot.lot_id

        WHERE shi.status = 1 AND lot.product_id = ?

        UNION

        SELECT DATE_FORMAT(ent.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Vale Entrada' AS document, ent.entry_adjustment_id

            AS number, ent_lot.lot_id, ent_lot.quantity AS entry, '' AS withdraw, ent_lot.number AS item_number FROM

            entry_adjustment ent INNER JOIN entry_adjustment_lot ent_lot

            ON ent.entry_adjustment_id = ent_lot.entry_adjustment_id INNER JOIN lot ON ent_lot.lot_id = lot.lot_id

        WHERE ent.status = 1 AND lot.product_id = ?

        UNION

        SELECT DATE_FORMAT(wit.date, '%d/%m/%Y %H:%i:%s') AS created_date, 'Vale Salida' AS document,

            wit.withdraw_adjustment_id AS number, wit_lot.lot_id, '' AS entry, wit_lot.quantity AS withdraw,

            wit_lot.number AS item_number FROM withdraw_adjustment wit INNER JOIN withdraw_adjustment_lot wit_lot

            ON wit.withdraw_adjustment_id = wit_lot.withdraw_adjustment_id INNER JOIN lot ON wit_lot.lot_id = lot.lot_id

        WHERE wit.status = 1 AND lot.product_id = ?) AS kardex ORDER BY created_date, item_number

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_available_quantity_get`(IN inLotId INT)
BEGIN

  SELECT quantity - reserved FROM lot

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_deactivate`(IN inLotId INT)
BEGIN

  UPDATE lot

    SET quantity = 0, reserved = 0

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_decrease_quantity`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET quantity = quantity - inQuantity

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_decrease_reserved`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET reserved = reserved - inQuantity

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_expiration_date_update`(IN inLotId INT, IN inExpirationDate DATE)
BEGIN

  UPDATE lot

    SET expiration_date = inExpirationDate

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_get`(IN inLotId INT)
BEGIN

  SELECT product_id, DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date,

      DATE_FORMAT(entry_date, '%d/%m/%Y') AS entry_date, price FROM lot

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_increase_quantity`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET quantity = quantity + inQuantity

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_increase_reserved`(IN inLotId INT, IN inQuantity INT)
BEGIN

  UPDATE lot

    SET reserved = reserved + inQuantity

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_insert`(IN inProductId INT, IN inEntryDate DATE, IN inExpirationDate DATE, IN inPrice DECIMAL(10, 2),

  IN inQuantity INT)
BEGIN

  INSERT INTO lot (product_id, entry_date, expiration_date, price, quantity)

    VALUES (inProductId, inEntryDate, inExpirationDate, inPrice, inQuantity);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_price_update`(IN inLotId INT, IN inPrice DECIMAL(10, 2))
BEGIN

  UPDATE lot

    SET price = inPrice

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `lot_quantity_get`(IN inLotId INT)
BEGIN

  SELECT quantity FROM lot

    WHERE lot_id = inLotId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_counting_template_get`(IN inFirst VARCHAR(100), IN inLast VARCHAR(100))
BEGIN

  SELECT * FROM

    (SELECT pro.product_id AS id, pro.bar_code, man.name AS manufacturer, pro.name AS product, pro.packaging FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro.deactivated = 0 ORDER BY man.name, pro.name) AS counting_template

  WHERE manufacturer BETWEEN inFirst AND inLast;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_delete`(IN inManufacturerId INT)
BEGIN

  DELETE FROM manufacturer

    WHERE manufacturer_id = inManufacturerId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_distinct_list_get`()
BEGIN

    SELECT DISTINCT name FROM manufacturer

      ORDER BY name;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_get`(IN inManufacturerId INT)
BEGIN

  SELECT name FROM manufacturer

    WHERE manufacturer_id = inManufacturerId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_insert`(IN inName VARCHAR(100))
BEGIN

  INSERT INTO manufacturer (name)

    VALUES (inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_list_count`()
BEGIN

  SELECT COUNT(*) FROM manufacturer;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_product_list_count`(IN inManufacturerId INT)
BEGIN

  SELECT COUNT(*) FROM product

    WHERE manufacturer_id = inManufacturerId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_product_list_get`(IN inManufacturerId INT, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT product_id, name FROM product

      WHERE manufacturer_id = ?

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inManufacturerId;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `manufacturer_update`(IN inManufacturerId INT, IN inName VARCHAR(100))
BEGIN

  UPDATE manufacturer

    SET name = inName

    WHERE manufacturer_id = inManufacturerId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_delete`(IN inPaymentCardBrandId INT)
BEGIN

  DELETE FROM payment_card_brand

    WHERE payment_card_brand_id = inPaymentCardBrandId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_get`(IN inPaymentCardBrandId INT)
BEGIN

  SELECT name FROM payment_card_brand

    WHERE payment_card_brand_id = inPaymentCardBrandId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_insert`(IN inName VARCHAR(100))
BEGIN

  INSERT INTO payment_card_brand (name)

    VALUES (inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_list_count`()
BEGIN

  SELECT COUNT(*) FROM payment_card_brand;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT payment_card_brand_id, name FROM payment_card_brand

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_brand_update`(IN inPaymentCardBrandId INT, IN inName VARCHAR(100))
BEGIN

  UPDATE payment_card_brand

    SET name = inName

    WHERE payment_card_brand_id = inPaymentCardBrandId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_delete`(IN inPaymentCardTypeId INT)
BEGIN

  DELETE FROM payment_card_type

    WHERE payment_card_type_id = inPaymentCardTypeId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_get`(IN inPaymentCardTypeId INT)
BEGIN

  SELECT name FROM payment_card_type

    WHERE payment_card_type_id = inPaymentCardTypeId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_insert`(IN inName VARCHAR(100))
BEGIN

  INSERT INTO payment_card_type (name)

    VALUES (inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_list_count`()
BEGIN

  SELECT COUNT(*) FROM payment_card_type;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT payment_card_type_id, name FROM payment_card_type

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `payment_card_type_update`(IN inPaymentCardTypeId INT, IN inName VARCHAR(100))
BEGIN

  UPDATE payment_card_type

    SET name = inName

    WHERE payment_card_type_id = inPaymentCardTypeId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_available_quantity_get`(IN inProductId INT)
BEGIN

  SELECT quantity - reserved FROM product

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_balance_foward_get`(IN inProductId INT)
BEGIN

  SELECT balance_foward FROM product

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_bar_code_exists`(IN inProductId INT, IN inBarCode VARCHAR(100))
BEGIN

  SELECT COUNT(*) FROM product

    WHERE product_id != inProductId AND bar_code = inBarCode;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_bar_code_update`(IN inProductId INT, IN inBarCode VARCHAR(100))
BEGIN
  UPDATE product
    SET bar_code = inBarCode
    WHERE product_id = inProductId;
END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_bonus_list_get`(IN inProductId INT)
BEGIN

  SELECT bonus_id AS id, quantity, percentage, DATE_FORMAT(created_date, '%d/%m/%Y') AS created_date,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expired_date FROM bonus

    WHERE product_id = inProductId AND expiration_date > CURDATE()

    ORDER BY quantity;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_counting_template_get`(IN inFirst VARCHAR(100), IN inLast VARCHAR(100))
BEGIN

  SELECT * FROM

    (SELECT pro.product_id AS id, pro.bar_code, man.name AS manufacturer, pro.name AS product, pro.packaging FROM product pro

      INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

     WHERE pro.deactivated = 0 ORDER BY pro.name, man.name) AS counting_template

  WHERE product BETWEEN inFirst AND inLast;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_decrease_quantity`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET quantity = quantity - inQuantity

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_decrease_reserved`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET reserved = reserved - inQuantity

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_delete`(IN inProductId INT)
BEGIN

  DELETE FROM change_price_log

    WHERE product_id = inProductId;



  DELETE FROM product_supplier

    WHERE product_id = inProductId;



  DELETE FROM product

    WHERE product_id = inProductId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_distinct_list_get`()
BEGIN

    SELECT DISTINCT name FROM product

      ORDER BY name;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_get`(IN inProductId INT)
BEGIN

  SELECT bar_code, name, packaging, description, unit_of_measure_id, manufacturer_id, price, deactivated FROM product

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_id_get`(IN inBarCode VARCHAR(100))
BEGIN

  SELECT product_id FROM product

    WHERE bar_code = inBarCode AND deactivated != 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_inactive_count`(IN inDays INT)
BEGIN

  SELECT COUNT(*) FROM (SELECT 1 FROM product pro

      INNER JOIN lot ON lot.product_id = pro.product_id

      INNER JOIN invoice_lot inv_lot ON lot.lot_id = inv_lot.lot_id

      INNER JOIN invoice inv ON inv_lot.invoice_id = inv.invoice_id

      INNER JOIN (SELECT MAX(inv.invoice_id) AS invoice_id, lot.product_id FROM invoice inv

                      INNER JOIN invoice_lot inv_lot ON inv.invoice_id = inv_lot.invoice_id

                      INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

                    WHERE lot.product_id IN (SELECT product_id FROM product) AND inv.status = 1

                      AND inv.date <= DATE_ADD(CURDATE(), INTERVAL -1 * inDays day)

                    GROUP BY lot.product_id) AS last_inv

      ON pro.product_id = last_inv.product_id AND inv.invoice_id = last_inv.invoice_id

  GROUP BY pro.product_id) AS inactive_products;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_inactive_get`(IN inDays INT, IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.bar_code, man.name AS manufacturer, pro.name, pro.packaging, pro.quantity,

         DATE_FORMAT(inv.date, '%d/%m/%Y') AS last_sale, SUM(inv_lot.quantity) AS sale_quantity FROM product pro

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         INNER JOIN lot ON lot.product_id = pro.product_id

         INNER JOIN invoice_lot inv_lot ON lot.lot_id = inv_lot.lot_id

         INNER JOIN invoice inv ON inv_lot.invoice_id = inv.invoice_id

         INNER JOIN (SELECT MAX(inv.invoice_id) AS invoice_id, lot.product_id FROM invoice inv

                         INNER JOIN invoice_lot inv_lot ON inv.invoice_id = inv_lot.invoice_id

                         INNER JOIN lot ON inv_lot.lot_id = lot.lot_id

                     WHERE lot.product_id IN (SELECT product_id FROM product) AND inv.status = 1

                         AND inv.date <= DATE_ADD(CURDATE(), INTERVAL -1 * ? day)

			 GROUP BY lot.product_id) AS last_inv

         ON pro.product_id = last_inv.product_id AND inv.invoice_id = last_inv.invoice_id

     GROUP BY pro.product_id

     ORDER BY pro.name

     LIMIT ?, ?";



  SET @p1 = inDays;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_increase_quantity`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET quantity = quantity + inQuantity

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_increase_reserved`(IN inProductId INT, IN inQuantity INT)
BEGIN

  UPDATE product

    SET reserved = reserved + inQuantity

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_insert`(IN inBarCode VARCHAR(100), IN inName VARCHAR(100),

  IN inPackaging VARCHAR(150), IN inDescription TEXT, IN inUnitOfMeasureId INT, IN inManufacturerId INT,

  IN inPrice DECIMAL(10, 2), IN inDeactivated TINYINT)
BEGIN

  INSERT INTO product (bar_code, name, packaging, description, unit_of_measure_id, manufacturer_id, price, deactivated)

    VALUES (inBarCode, inName, inPackaging, inDescription, inUnitOfMeasureId, inManufacturerId, inPrice, inDeactivated);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_list_count`()
BEGIN

  SELECT COUNT(*) FROM product;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT product_id AS id, name, packaging FROM product

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_available_get`(IN inProductId INT)
BEGIN

  SELECT lot_id, IFNULL(expiration_date, '9999-12-31') AS expiration_date FROM lot

    WHERE quantity - reserved > 0 AND product_id = inProductId

    ORDER BY expiration_date, lot_id;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_get`(IN inProductId INT)
BEGIN

  SELECT lot_id AS id, entry_date, expiration_date, price, quantity, (quantity - reserved) AS available FROM lot

    WHERE quantity > 0 AND product_id = inProductId

    ORDER BY lot_id;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_total_available_get`(IN inProductId INT)
BEGIN

  SELECT SUM(quantity - reserved) FROM lot

    WHERE quantity > 0 AND product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_lot_total_quantity_get`(IN inProductId INT)
BEGIN

  SELECT SUM(quantity) FROM lot

    WHERE quantity > 0 AND product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_negative_balance_count`()
BEGIN

  SELECT COUNT(*) FROM product pro

      INNER JOIN (SELECT product_id, SUM(quantity) AS sum_quantity FROM lot WHERE quantity > 0 GROUP BY product_id) AS lots

      ON pro.product_id = lots.product_id WHERE pro.quantity - lots.sum_quantity < 0;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_negative_balance_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro.bar_code, man.name AS manufacturer, pro.name, pro.packaging, pro.quantity AS general_quantity,

         lots.sum_quantity AS lots_quantity, pro.quantity - lots.sum_quantity AS balance FROM product pro

         INNER JOIN manufacturer man ON pro.manufacturer_id = man.manufacturer_id

         INNER JOIN (SELECT product_id, SUM(quantity) AS sum_quantity FROM lot

     WHERE quantity > 0 GROUP BY product_id) AS lots ON pro.product_id = lots.product_id

     WHERE pro.quantity - lots.sum_quantity < 0

     ORDER BY pro.name

     LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_quantity_get`(IN inProductId INT)
BEGIN

  SELECT quantity FROM product

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_search`(IN inSearchString VARCHAR(50))
BEGIN

  SELECT bar_code, name, packaging FROM product

    WHERE name LIKE CONCAT(inSearchString, '%') AND deactivated != 1
    ORDER BY name, packaging;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_delete`(IN inProductId INT, IN inSupplierId INT,
IN inSku VARCHAR(50))
BEGIN

  DELETE FROM product_supplier

    WHERE product_id = inProductId AND supplier_id = inSupplierId AND sku = inSku;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_exists`(IN inSupplierId INT, IN inSku VARCHAR(50))
BEGIN

  SELECT COUNT(*) FROM product_supplier

    WHERE supplier_id = inSupplierId AND sku = inSku;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_get`(IN inProductId INT)
BEGIN

  SELECT supplier_id, sku FROM product_supplier

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_insert`(IN inProductId INT, IN inSupplierId INT, IN inSku VARCHAR(50))
BEGIN

  INSERT INTO product_supplier (product_id, supplier_id, sku)

    VALUES (inProductId, inSupplierId, inSku);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_supplier_product_id_get`(IN inSupplierId INT, IN inSku VARCHAR(50))
BEGIN

  SELECT product_id FROM product_supplier

    WHERE supplier_id = inSupplierId AND sku = inSku;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `product_update`(IN inProductId INT, IN inBarCode VARCHAR(100), IN inName VARCHAR(100),

  IN inPackaging VARCHAR(150), IN inDescription TEXT, IN inUnitOfMeasureId INT, IN inManufacturerId INT,

  IN inPrice DECIMAL(10, 2), IN inDeactivated TINYINT)
BEGIN

  UPDATE product

    SET bar_code = inBarCode, name = inName, packaging = inPackaging, description = inDescription,

      unit_of_measure_id = inUnitOfMeasureId, manufacturer_id = inManufacturerId, price = inPrice, deactivated = inDeactivated

    WHERE product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_cancel`(IN inPurchaseReturnId INT, IN inUserName VARCHAR(50), IN inDate DATE)
BEGIN

  UPDATE purchase_return

    SET status = 2

    WHERE purchase_return_id = inPurchaseReturnId;



  INSERT INTO purchase_return_cancelled (purchase_return_id, user_account_username, date)

    VALUES (inPurchaseReturnId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_get`(IN inPurchaseReturnId INT)
BEGIN

  SELECT user_account_username, supplier_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, contact, total, status

      FROM purchase_return

    WHERE purchase_return_id = inPurchaseReturnId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_insert`(IN inUserName VARCHAR(50), IN inSupplierId INT, IN inDate DATETIME,

  IN inReason VARCHAR(150), IN inContact VARCHAR(100), IN inTotal DECIMAL(10, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO purchase_return (user_account_username, supplier_id, date, reason, contact, total, status)

    VALUES (inUserName, inSupplierId, inDate, inReason, inContact, inTotal, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_lot_count`(IN inPurchaseReturnId INT)
BEGIN

  SELECT COUNT(*) FROM purchase_return_lot

    WHERE purchase_return_id = inPurchaseReturnId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_lot_get`(IN inPurchaseReturnId INT, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT lot_id, quantity, price FROM purchase_return_lot

      WHERE purchase_return_id = ?

      ORDER BY number

      LIMIT ?, ?";



  SET @p1 = inPurchaseReturnId;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(10, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO purchase_return_lot (purchase_return_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM purchase_return

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `purchase_return_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_cancel`(IN inReceiptId INT, IN inUserName VARCHAR(50),

  IN inDate DATE)
BEGIN

  UPDATE receipt

    SET status = 2

    WHERE receipt_id = inReceiptId;



  INSERT INTO receipt_cancelled (receipt_id, user_account_username, date)

    VALUES (inReceiptId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_get`(IN inReceiptId INT)
BEGIN

  SELECT user_account_username, supplier_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, shipment_number, total, status

      FROM receipt

    WHERE receipt_id = inReceiptId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_insert`(IN inUserName VARCHAR(50), IN inSupplierId INT, IN inDate DATETIME,

  IN inShipmentNumber VARCHAR(50), IN inTotal DECIMAL(10, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO receipt (user_account_username, supplier_id, date, shipment_number, total, status)

    VALUES (inUserName, inSupplierId, inDate, inShipmentNumber, inTotal, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_lot_get`(IN inReceiptId INT)
BEGIN

  SELECT lot_id, quantity, price FROM receipt_lot

      WHERE receipt_id = inReceiptId

      ORDER BY number;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(10, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO receipt_lot (receipt_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM receipt

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `receipt_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_delete`(IN inReserveId INT)
BEGIN

  DELETE FROM reserve

    WHERE reserve_id = inReserveId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_get`(IN inReserveId INT)
BEGIN

  SELECT user_account_username, lot_id, DATE_FORMAT(date, '%d/%m/%Y') AS created_date, quantity FROM reserve

    WHERE reserve_id = inReserveId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_increase_quantity`(IN inReserveId INT, IN inQuantity INT)
BEGIN

  UPDATE reserve

    SET quantity = quantity + inQuantity

    WHERE reserve_id = inReserveId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_insert`(IN inUserName VARCHAR(50), IN inLotId INT, IN inDate DATE, IN inQuantity INT)
BEGIN

  INSERT INTO reserve (user_account_username, lot_id, date, quantity)

    VALUES (inUserName, inLotId, inDate, inQuantity);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `reserve_list_get`(IN inProductId INT)
BEGIN

  SELECT res.reserve_id, DATE_FORMAT(res.date, '%d/%m/%Y') AS created_date, res.user_account_username, lot.lot_id,

      res.quantity FROM reserve res INNER JOIN lot ON res.lot_id = lot.lot_id

    WHERE lot.product_id = inProductId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_get`(IN inRoleId INT)
BEGIN

  SELECT name FROM role

    WHERE role_id = inRoleId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_list_count`()
BEGIN

  SELECT COUNT(*) FROM role;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT role_id, name FROM role

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `role_subject_action_value_get`(IN inRoleId INT, IN inSubjectId INT, IN inActionId INT)
BEGIN
  SELECT value FROM role_subject_action
    WHERE role_id = inRoleId AND subject_id = inSubjectId AND action_id = inActionId;
END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `root_change_password`(IN inPassword VARCHAR(50))
BEGIN

  UPDATE root

    SET password = inPassword;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_invoices_get`(IN inCashRegisterId INT)
BEGIN

  SELECT inv.serial_number, inv.number, inv.name, IF(inv.status = 2, 0, cr.cash) AS cash,

      IF(inv.status = 2, 0, cr.total_vouchers) as total_vouchers,

      IF(inv.status = 2, 0, @discount_percentage := IFNULL(dis.percentage, 0)) AS discount,

      IF(inv.status = 2, 0, inv.total - inv.total * (@discount_percentage / 100)) AS total, status FROM invoice inv 

      INNER JOIN cash_receipt cr ON inv.invoice_id = cr.cash_receipt_id

      LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cash_register_id = inCashRegisterId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_cash`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(cash) FROM cash_receipt cr

    INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId AND inv.status = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_discount`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(inv.total * (dis.percentage / 100)) FROM invoice inv

      INNER JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId AND inv.status = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_vat`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM((inv.total - inv.total * (IFNULL(dis.percentage, 0) / 100)) * (inv.vat / 100)) FROM invoice inv

    LEFT JOIN discount dis ON inv.invoice_id = dis.invoice_id

    WHERE cash_register_id = inCashRegisterId AND status = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `sales_report_total_vouchers`(IN inCashRegisterId INT)
BEGIN

  SELECT SUM(total_vouchers) FROM cash_receipt cr

    INNER JOIN invoice inv ON cr.cash_receipt_id = inv.invoice_id

    WHERE inv.cash_register_id = inCashRegisterId AND inv.status = 1;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_delete`(IN inShiftId INT)
BEGIN

  DELETE FROM shift

    WHERE shift_id = inShiftId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_get`(IN inShiftId INT)
BEGIN

  SELECT name, time_table FROM shift

    WHERE shift_id = inShiftId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_insert`(IN inName VARCHAR(50), IN inTimeTable VARCHAR(100))
BEGIN

  INSERT INTO shift (name, time_table)

    VALUES (inName, inTimeTable);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_list_count`()
BEGIN

  SELECT COUNT(*) FROM shift;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT shift_id, name FROM shift

      ORDER BY name

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shift_update`(IN inShiftId INT, IN inName VARCHAR(50), IN inTimeTable VARCHAR(100))
BEGIN

  UPDATE shift

    SET name = inName, time_table = inTimeTable

    WHERE shift_id = inShiftId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_cancel`(IN inShipmentId INT, IN inUserName VARCHAR(50),

  IN inDate DATE)
BEGIN

  UPDATE shipment

    SET status = 2

    WHERE shipment_id = inShipmentId;



  INSERT INTO shipment_cancelled (shipment_id, user_account_username, date)

    VALUES (inShipmentId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_get`(IN inShipmentId INT)
BEGIN

  SELECT user_account_username, branch_id, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, contact, total, status

      FROM shipment

    WHERE shipment_id = inShipmentId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_insert`(IN inUserName VARCHAR(50), IN inBranchId INT,

  IN inDate DATETIME, IN inContact VARCHAR(100), IN inTotal DECIMAL(10, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO shipment (user_account_username, branch_id, date, contact, total, status)

    VALUES (inUserName, inBranchId, inDate, inContact, inTotal, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_lot_count`(IN inShipmentId INT)
BEGIN

  SELECT COUNT(*) FROM shipment_lot

    WHERE shipment_id = inShipmentId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_lot_get`(IN inShipmentId INT, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT lot_id, quantity, price FROM shipment_lot

      WHERE shipment_id = ?

      ORDER BY number

      LIMIT ?, ?";



  SET @p1 = inShipmentId;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(10, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO shipment_lot (shipment_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM shipment

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `shipment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `subject_id_get`(IN inName VARCHAR(50))
BEGIN
  SELECT subject_id FROM subject
    WHERE name = inName;
END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_delete`(IN inSupplierId INT)
BEGIN

  DELETE FROM supplier

    WHERE supplier_id = inSupplierId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_get`(IN inSupplierId INT)
BEGIN

  SELECT name, nit, telephone, address, email, contact FROM supplier

    WHERE supplier_id = inSupplierId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_insert`(IN inName VARCHAR(100), IN inNit VARCHAR(15), IN inTelephone VARCHAR(50),

  IN inAddress VARCHAR(150), IN inEmail VARCHAR(100), IN inContact VARCHAR(100))
BEGIN

  INSERT INTO supplier (name, nit, telephone, address, email, contact)

    VALUES (inName, inNit, inTelephone, inAddress, inEmail, inContact);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_list_count`()
BEGIN

  SELECT COUNT(*) FROM supplier;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_product_list_count`(IN inSupplierId INT)
BEGIN

  SELECT COUNT(*) FROM (SELECT product_id FROM product_supplier WHERE supplier_id = inSupplierId)

      AS supplier_products;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_product_list_get`(IN inSupplierId INT, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT pro_sup.product_id, pro.name FROM product_supplier pro_sup INNER JOIN product pro

         ON pro_sup.product_id = pro.product_id

     WHERE pro_sup.supplier_id = ?

     ORDER BY pro.name

     LIMIT ?, ?";



  SET @p1 = inSupplierId;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `supplier_update`(IN inSupplierId INT, IN inName VARCHAR(100),

  IN inNit VARCHAR(15), IN inTelephone VARCHAR(50), IN inAddress VARCHAR(150),

  IN inEmail VARCHAR(100), IN inContact VARCHAR(100))
BEGIN

  UPDATE supplier

  SET name = inName, nit = inNit, telephone = inTelephone, address = inAddress,

    email = inEmail, contact = inContact

  WHERE supplier_id = inSupplierId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_delete`(IN inUnitOfMeasureId INT)
BEGIN

  DELETE FROM unit_of_measure

    WHERE unit_of_measure_id = inUnitOfMeasureId;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_get`(IN inUnitOfMeasureId INT)
BEGIN

  SELECT name FROM unit_of_measure

    WHERE unit_of_measure_id = inUnitOfMeasureId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_insert`(IN inName VARCHAR(100))
BEGIN

  INSERT INTO unit_of_measure (name)

    VALUES (inName);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_list_count`()
BEGIN

  SELECT COUNT(*) FROM unit_of_measure;

END$$

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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `unit_of_measure_update`(IN inUnitOfMeasureId INT, IN inName VARCHAR(100))
BEGIN

  UPDATE unit_of_measure

    SET name = inName

    WHERE unit_of_measure_id = inUnitOfMeasureId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_change_password`(IN inUserName VARCHAR(50), IN inPassword VARCHAR(50))
BEGIN

  UPDATE user_account

    SET password = inPassword

    WHERE user_account_username = inUserName;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_delete`(IN inUserName VARCHAR(50))
BEGIN

  DELETE FROM user_account

    WHERE user_account_username = inUserName;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_dependencies`(IN inUserName VARCHAR(50))
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_exists`(IN inUserName VARCHAR(50))
BEGIN

  SELECT COUNT(*) FROM user_account

  WHERE user_account_username = inUserName;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_get`(IN inUserName VARCHAR(50))
BEGIN

  SELECT role_id, first_name, last_name, deactivated FROM user_account

    WHERE user_account_username = inUserName;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_insert`(IN inUserName VARCHAR(50), IN inRoleId INT, IN inFirstName VARCHAR(50),

  IN inLastName VARCHAR(50), IN inPassword VARCHAR(50), IN inDeactivated TINYINT)
BEGIN

  INSERT INTO user_account (user_account_username, role_id, first_name, last_name, password, deactivated)

    VALUES (inUserName, inRoleId, inFirstName, inLastName, inPassword, inDeactivated);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_is_valid`(IN inUserName VARCHAR(50), IN inPassword VARCHAR(50))
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_list_count`()
BEGIN

  SELECT COUNT(*) FROM user_account;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_list_get`(IN inStartItem INT, IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT user_account_username AS username, first_name, last_name FROM user_account

      ORDER BY user_account_username

      LIMIT ?, ?";



  SET @p1 = inStartItem;

  SET @p2 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `user_account_update`(IN inUserName VARCHAR(50), IN inRoleId INT, IN inFirstName VARCHAR(50),

  IN inLastName VARCHAR(50), IN inPassword VARCHAR(50), IN inDeactivated TINYINT)
BEGIN

  UPDATE user_account

    SET role_id = inRoleId, first_name = inFirstName, last_name = inLastName, password = inPassword, deactivated = inDeactivated

    WHERE user_account_username = inUserName;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `vat_get`()
BEGIN

  SELECT percentage FROM vat;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `vat_update`(IN inPercentage DECIMAL(10, 2))
BEGIN

  UPDATE vat

    SET percentage = inPercentage;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `voucher_get`(IN inCashReceiptId INT)
BEGIN

  SELECT transaction, amount, payment_card_number, payment_card_type_id, payment_card_brand_id, name,

      DATE_FORMAT(expiration_date, '%d/%m/%Y') AS expiration_date FROM voucher

    WHERE cash_receipt_id = inCashReceiptId

    ORDER BY voucher_id;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `voucher_insert`(IN inCashReceiptId INT, IN inTransaction VARCHAR(100), IN inAmount DECIMAL(10, 2),

  IN inPaymentCardNumber INT, IN inPaymentCardTypeId INT, IN inPaymentCardBrandId INT, IN inName VARCHAR(100),

  IN inExpirationDate DATE)
BEGIN

  INSERT INTO voucher (cash_receipt_id, transaction, amount, payment_card_number, payment_card_type_id, payment_card_brand_id,

      name, expiration_date)

    VALUES (inCashReceiptId, inTransaction, inAmount, inPaymentCardNumber, inPaymentCardTypeId, inPaymentCardBrandId, inName,

      inExpirationDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_cancel`(IN inWithdrawAdjustmentId INT, IN inUserName VARCHAR(50),

  IN inDate DATE)
BEGIN

  UPDATE withdraw_adjustment

    SET status = 2

    WHERE withdraw_adjustment_id = inWithdrawAdjustmentId;



  INSERT INTO withdraw_adjustment_cancelled (withdraw_adjustment_id, user_account_username, date)

    VALUES (inWithdrawAdjustmentId, inUserName, inDate);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_get`(IN inWithdrawAdjustmentId INT)
BEGIN

  SELECT user_account_username, DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS created_date, reason, total, status

      FROM withdraw_adjustment

    WHERE withdraw_adjustment_id = inWithdrawAdjustmentId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_insert`(IN inUserName VARCHAR(50), IN inDate DATETIME,

  IN inReason VARCHAR(150), IN inTotal DECIMAL(10, 2), IN inStatus TINYINT)
BEGIN

  INSERT INTO withdraw_adjustment (user_account_username, date, reason, total, status)

    VALUES (inUserName, inDate, inReason, inTotal, inStatus);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_lot_count`(IN inWithdrawAdjustmentId INT)
BEGIN

  SELECT COUNT(*) FROM withdraw_adjustment_lot

    WHERE withdraw_adjustment_id = inWithdrawAdjustmentId;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_lot_get`(IN inWithdrawAdjustmentId INT, IN inStartItem INT,

  IN inItemsPerPage INT)
BEGIN

  PREPARE statement FROM

    "SELECT lot_id, quantity, price FROM withdraw_adjustment_lot

      WHERE withdraw_adjustment_id = ?

      ORDER BY number

      LIMIT ?, ?";



  SET @p1 = inWithdrawAdjustmentId;

  SET @p2 = inStartItem;

  SET @p3 = inItemsPerPage;



  EXECUTE statement USING @p1, @p2, @p3;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_lot_insert`(IN inDocumentId INT, IN inLotId INT, IN inQuantity INT, IN inPrice DECIMAL(10, 2),

  IN inNumber INT)
BEGIN

  INSERT INTO withdraw_adjustment_lot (withdraw_adjustment_id, lot_id, number, quantity, price)

    VALUES (inDocumentId, inLotId, inNumber, inQuantity, inPrice);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_search_count`(IN inStartDate DATE, IN inEndDate DATE)
BEGIN

  SELECT COUNT(*) FROM withdraw_adjustment

    WHERE date BETWEEN inStartDate AND inEndDate;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `withdraw_adjustment_search_get`(IN inStartDate DATE, IN inEndDate DATE, IN inStartItem INT, IN inItemsPerPage INT)
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

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_close`(IN inWorkingDay DATE)
BEGIN

  UPDATE working_day

    SET open = 0

    WHERE working_day = inWorkingDay;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_close_cash_registers`(IN inWorkingDay DATE)
BEGIN

  UPDATE cash_register

    SET open = 0

    WHERE working_day = inWorkingDay;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_exists`(IN inWorkingDay DATE)
BEGIN

  SELECT COUNT(*) FROM working_day

    WHERE working_day = inWorkingDay;

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_insert`(IN inWorkingDay DATE)
BEGIN

  INSERT INTO working_day (working_day)

    VALUES (inWorkingDay);

END$$

CREATE DEFINER=`999_user`@`localhost` PROCEDURE `working_day_is_open`(IN inWorkingDay DATE)
BEGIN

  SELECT open FROM working_day

    WHERE working_day = inWorkingDay;

END$$

DELIMITER ;
