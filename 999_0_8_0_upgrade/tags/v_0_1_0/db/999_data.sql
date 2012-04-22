DELIMITER $$

USE @db_database@$$

UPDATE role_subject_action SET value = 0 WHERE role_id = 4 AND subject_id = 8 AND action_id = 2;

UPDATE role_subject_action SET value = 0 WHERE role_id = 4 AND subject_id = 9 AND action_id = 2;

INSERT INTO `role_subject_action` (`role_id`, `subject_id`, `action_id`, `value`) VALUES
(1, 45, 6, 1),
(2, 45, 6, 1),
(3, 45, 6, 0),
(4, 45, 6, 0),
(5, 45, 6, 0)$$

INSERT INTO `subject` (`subject_id`, `name`) VALUES
(45, 'comparison_filter_prices')$$