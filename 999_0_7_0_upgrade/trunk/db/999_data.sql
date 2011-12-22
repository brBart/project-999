DELIMITER $$

UPDATE bonus SET user_account_username = '@bonus_username@'$$

UPDATE company SET warehouse_name = '@warehouse_name@'$$

UPDATE role_subject_action SET value = 1 WHERE role_id = 5 AND subject_id = 1 AND action_id = 1;

UPDATE role_subject_action SET value = 1 WHERE role_id = 5 AND subject_id = 39 AND action_id = 6;

INSERT INTO `role_subject_action` (`role_id`, `subject_id`, `action_id`, `value`) VALUES
(1, 42, 6, 1),
(2, 42, 6, 1),
(3, 42, 6, 1),
(4, 42, 6, 0),
(5, 42, 6, 1),
(1, 43, 6, 1),
(2, 43, 6, 1),
(3, 43, 6, 1),
(4, 43, 6, 0),
(5, 43, 6, 1),
(1, 44, 6, 1),
(2, 44, 6, 1),
(3, 44, 6, 1),
(4, 44, 6, 0),
(5, 44, 6, 1)$$

INSERT INTO `subject` (`subject_id`, `name`) VALUES
(42, 'sales_summary_product_report'),
(43, 'sales_summary_user_account_report'),
(44, 'purchases_summary_product_report')$$

