-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-01-2011 a las 16:49:07
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `999_store`
--

--
-- Volcar la base de datos para la tabla `action`
--

INSERT INTO `action` (`action_id`, `name`) VALUES
(1, 'access'),
(2, 'write'),
(3, 'cancel'),
(4, 'close'),
(5, 'confirm'),
(6, 'read');

--
-- Volcar la base de datos para la tabla `bank`
--

INSERT INTO `bank` (`bank_id`, `name`) VALUES
(1, 'GyT Continental'),
(2, 'Industrial');

--
-- Volcar la base de datos para la tabla `bank_account`
--

INSERT INTO `bank_account` (`bank_account_number`, `bank_id`, `name`) VALUES
('29-5000036-6', 1, 'Drogueria Jose Gil Jutiapa');

--
-- Volcar la base de datos para la tabla `bonus`
--


--
-- Volcar la base de datos para la tabla `branch`
--


--
-- Volcar la base de datos para la tabla `cash_receipt`
--


--
-- Volcar la base de datos para la tabla `cash_register`
--


--
-- Volcar la base de datos para la tabla `change_price_log`
--


--
-- Volcar la base de datos para la tabla `company`
--

INSERT INTO `company` (`nit`, `name`) VALUES
('350682-7', 'Tienda 999');

--
-- Volcar la base de datos para la tabla `comparison`
--


--
-- Volcar la base de datos para la tabla `comparison_product`
--


--
-- Volcar la base de datos para la tabla `correlative`
--


--
-- Volcar la base de datos para la tabla `count`
--


--
-- Volcar la base de datos para la tabla `count_product`
--


--
-- Volcar la base de datos para la tabla `customer`
--


--
-- Volcar la base de datos para la tabla `deposit`
--


--
-- Volcar la base de datos para la tabla `deposit_cancelled`
--


--
-- Volcar la base de datos para la tabla `deposit_cash_receipt`
--


--
-- Volcar la base de datos para la tabla `discount`
--


--
-- Volcar la base de datos para la tabla `entry_adjustment`
--


--
-- Volcar la base de datos para la tabla `entry_adjustment_cancelled`
--


--
-- Volcar la base de datos para la tabla `entry_adjustment_lot`
--


--
-- Volcar la base de datos para la tabla `invoice`
--


--
-- Volcar la base de datos para la tabla `invoice_bonus`
--


--
-- Volcar la base de datos para la tabla `invoice_cancelled`
--


--
-- Volcar la base de datos para la tabla `invoice_lot`
--


--
-- Volcar la base de datos para la tabla `lot`
--


--
-- Volcar la base de datos para la tabla `manufacturer`
--


--
-- Volcar la base de datos para la tabla `payment_card_brand`
--

INSERT INTO `payment_card_brand` (`payment_card_brand_id`, `name`) VALUES
(1, 'Visa'),
(2, 'MasterCard');

--
-- Volcar la base de datos para la tabla `payment_card_type`
--

INSERT INTO `payment_card_type` (`payment_card_type_id`, `name`) VALUES
(1, 'Credito'),
(2, 'Debito');

--
-- Volcar la base de datos para la tabla `product`
--


--
-- Volcar la base de datos para la tabla `product_supplier`
--


--
-- Volcar la base de datos para la tabla `purchase_return`
--


--
-- Volcar la base de datos para la tabla `purchase_return_cancelled`
--


--
-- Volcar la base de datos para la tabla `purchase_return_lot`
--


--
-- Volcar la base de datos para la tabla `receipt`
--


--
-- Volcar la base de datos para la tabla `receipt_cancelled`
--


--
-- Volcar la base de datos para la tabla `receipt_lot`
--


--
-- Volcar la base de datos para la tabla `reserve`
--


--
-- Volcar la base de datos para la tabla `role`
--

INSERT INTO `role` (`role_id`, `name`) VALUES
(1, 'Administrador'),
(2, 'Auditor'),
(3, 'Supervisor'),
(4, 'Operador'),
(5, 'Contador');

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
(1, 14, 2, 1),
(1, 15, 1, 1),
(1, 16, 2, 1),
(1, 17, 2, 1),
(1, 18, 2, 1),
(1, 19, 2, 1),
(1, 20, 2, 1),
(1, 21, 2, 1),
(1, 22, 2, 1),
(1, 23, 2, 1),
(1, 24, 2, 1),
(1, 25, 2, 1),
(1, 26, 1, 1),
(1, 27, 2, 1),
(1, 28, 2, 1),
(1, 27, 3, 1),
(1, 30, 2, 1),
(1, 30, 3, 1),
(1, 31, 4, 1),
(1, 32, 4, 1),
(1, 33, 1, 1),
(1, 30, 5, 1),
(1, 34, 6, 1),
(1, 35, 6, 1),
(1, 36, 6, 1),
(1, 37, 6, 1),
(1, 38, 2, 1);

--
-- Volcar la base de datos para la tabla `root`
--

INSERT INTO `root` (`password`) VALUES
('4ba8ee3256001cc06025ac392d9b1c98051bff09');

--
-- Volcar la base de datos para la tabla `shift`
--

INSERT INTO `shift` (`shift_id`, `name`, `time_table`) VALUES
(1, 'Diurno', '8 am - 6 pm');

--
-- Volcar la base de datos para la tabla `shipment`
--


--
-- Volcar la base de datos para la tabla `shipment_cancelled`
--


--
-- Volcar la base de datos para la tabla `shipment_lot`
--


--
-- Volcar la base de datos para la tabla `subject`
--

INSERT INTO `subject` (`subject_id`, `name`) VALUES
(1, 'inventory'),
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
(14, 'comparison'),
(15, 'admin'),
(16, 'user_account'),
(17, 'company'),
(18, 'vat'),
(19, 'correlative'),
(20, 'shift'),
(21, 'bank'),
(22, 'bank_account'),
(23, 'payment_card_type'),
(24, 'payment_card_brand'),
(25, 'reserve'),
(26, 'pos'),
(27, 'invoice'),
(28, 'discount'),
(30, 'deposit'),
(31, 'cash_register'),
(32, 'working_day'),
(33, 'pos_admin'),
(34, 'invoice_discount_log'),
(35, 'product_price_log'),
(36, 'document_cancel_log'),
(37, 'cash_document_cancel_log'),
(38, 'general_closure');

--
-- Volcar la base de datos para la tabla `supplier`
--


--
-- Volcar la base de datos para la tabla `unit_of_measure`
--

INSERT INTO `unit_of_measure` (`unit_of_measure_id`, `name`) VALUES
(1, 'Unidad'),
(10, '1/2 Docena'),
(11, 'Docena'),
(12, 'Metro'),
(13, 'Yarda');

--
-- Volcar la base de datos para la tabla `user_account`
--

INSERT INTO `user_account` (`user_account_username`, `role_id`, `first_name`, `last_name`, `password`, `deactivated`) VALUES
('roboli', 1, 'Roberto', 'Oliveros', '36a9fc86e20ec727f782d1e27f877303d866fbbd', 0);

--
-- Volcar la base de datos para la tabla `vat`
--

INSERT INTO `vat` (`percentage`) VALUES
(12.00);

--
-- Volcar la base de datos para la tabla `voucher`
--


--
-- Volcar la base de datos para la tabla `withdraw_adjustment`
--


--
-- Volcar la base de datos para la tabla `withdraw_adjustment_cancelled`
--


--
-- Volcar la base de datos para la tabla `withdraw_adjustment_lot`
--


--
-- Volcar la base de datos para la tabla `working_day`
--

