DROP TRIGGER IF EXISTS `trigger_item_action_recommendation_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_action_recommendation_insert` AFTER INSERT ON `tbl_item_action_recommendation_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_action_recommendation_view SET item_id = NEW.item_id, action_recommendation = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS action_recommendation
FROM tbl_item_action_recommendation AS ip1
LEFT JOIN tbl_item_action_recommendation AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_action_recommendation AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_action_recommendation AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

DROP TRIGGER IF EXISTS `trigger_item_action_recommendation_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_action_recommendation_update` AFTER UPDATE ON `tbl_item_action_recommendation_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_action_recommendation_view SET item_id = NEW.item_id, action_recommendation = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS action_recommendation
FROM tbl_item_action_recommendation AS ip1
LEFT JOIN tbl_item_action_recommendation AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_action_recommendation AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_action_recommendation AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;
-- ----------------------------
-- Triggers structure for table tbl_item_action_recommendation_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_action_recommendation_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_action_recommendation_delete` AFTER DELETE ON `tbl_item_action_recommendation_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_action_recommendation_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_additional_information_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_additional_info_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_additional_info_insert` AFTER INSERT ON `tbl_item_additional_information_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_additional_information_view SET item_id = NEW.item_id, additional_information = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS additional_information
FROM tbl_item_additional_information AS ip1
LEFT JOIN tbl_item_additional_information AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_additional_information AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_additional_information AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_additional_information_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_additional_info_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_additional_info_update` AFTER UPDATE ON `tbl_item_additional_information_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_additional_information_view SET item_id = NEW.item_id, additional_information = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS additional_information
FROM tbl_item_additional_information AS ip1
LEFT JOIN tbl_item_additional_information AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_additional_information AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_additional_information AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_additional_information_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_additional_info_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_additional_info_delete` AFTER DELETE ON `tbl_item_additional_information_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_additional_information_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_asbestos_type_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_asbestos_type_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_asbestos_type_insert` AFTER INSERT ON `tbl_item_asbestos_type_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_asbestos_type_view SET item_id = NEW.item_id, asbestos_type = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS asbestos_type
FROM tbl_item_asbestos_type AS ip1
LEFT JOIN tbl_item_asbestos_type AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_asbestos_type AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_asbestos_type AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_asbestos_type_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_asbestos_type_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_asbestos_type_update` AFTER UPDATE ON `tbl_item_asbestos_type_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_asbestos_type_view SET item_id = NEW.item_id, asbestos_type = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS asbestos_type
FROM tbl_item_asbestos_type AS ip1
LEFT JOIN tbl_item_asbestos_type AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_asbestos_type AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_asbestos_type AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_asbestos_type_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_asbestos_type_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_asbestos_type_delete` AFTER DELETE ON `tbl_item_asbestos_type_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_asbestos_type_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_extent_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_extent_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_extent_insert` AFTER INSERT ON `tbl_item_extent_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_extent_view SET item_id = NEW.item_id, extent = (SELECT
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(NEW.dropdown_other,''),'')) AS extent
FROM tbl_item_extent AS ip1
LEFT JOIN tbl_item_extent AS ip2 ON ip1.parent_id = ip2.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_extent_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_extent_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_extent_update` AFTER UPDATE ON `tbl_item_extent_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_extent_view SET item_id = NEW.item_id, extent = (SELECT
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(NEW.dropdown_other,''),'')) AS extent
FROM tbl_item_extent AS ip1
LEFT JOIN tbl_item_extent AS ip2 ON ip1.parent_id = ip2.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_extent_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_extent_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_extent_delete` AFTER DELETE ON `tbl_item_extent_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_extent_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_licensed_non_licensed_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_licensed_non_licensed_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_licensed_non_licensed_insert` AFTER INSERT ON `tbl_item_licensed_non_licensed_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_licensed_non_licensed_view SET item_id = NEW.item_id, licensed_non_licensed = (SELECT
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(NEW.dropdown_other,''),'')) AS licensed_non_licensed
FROM tbl_item_licensed_non_licensed AS ip1
LEFT JOIN tbl_item_licensed_non_licensed AS ip2 ON ip1.parent_id = ip2.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_licensed_non_licensed_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_licensed_non_licensed_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_licensed_non_licensed_update` AFTER UPDATE ON `tbl_item_licensed_non_licensed_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_licensed_non_licensed_view SET item_id = NEW.item_id, licensed_non_licensed = (SELECT
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(NEW.dropdown_other,''),'')) AS licensed_non_licensed
FROM tbl_item_licensed_non_licensed AS ip1
LEFT JOIN tbl_item_licensed_non_licensed AS ip2 ON ip1.parent_id = ip2.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_licensed_non_licensed_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_licensed_non_licensed_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_licensed_non_licensed_delete` AFTER DELETE ON `tbl_item_licensed_non_licensed_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_licensed_non_licensed_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_product_debris_type_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_product_debris_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_product_debris_insert` AFTER INSERT ON `tbl_item_product_debris_type_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_product_debris_view SET item_id = NEW.item_id, product_debris = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS product_debris
FROM tbl_item_product_debris_type AS ip1
LEFT JOIN tbl_item_product_debris_type AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_product_debris_type AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_product_debris_type AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_product_debris_type_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_product_debris_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_product_debris_update` AFTER UPDATE ON `tbl_item_product_debris_type_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_product_debris_view SET item_id = NEW.item_id, product_debris = (SELECT
GROUP_CONCAT(
IF
( ip3.description IS NOT NULL, CONCAT( ip3.description, '---' ), '' ),
IF
( ip2.description IS NOT NULL, CONCAT( ip2.description, '---' ), '' ),
IF
( ip1.description IS NOT NULL, CONCAT( ip1.description, '---' ), '' ),
IF
( ip1.other = - 1, IFNULL( NEW.dropdown_other, '' ), '' )
) AS product_debris
FROM tbl_item_product_debris_type AS ip1
LEFT JOIN tbl_item_product_debris_type AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_product_debris_type AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_product_debris_type AS ip4 ON ip3.parent_id = ip4.id

WHERE ip1.id = NEW.dropdown_data_item_id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_product_debris_type_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_product_debris_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_product_debris_delete` AFTER DELETE ON `tbl_item_product_debris_type_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_product_debris_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_spf_insert`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_spf_insert` AFTER INSERT ON `tbl_item_specific_location_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_specific_location_view SET item_id = NEW.item_id, specific_location = (SELECT CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
GROUP_CONCAT(
    CONCAT(IF(dropdown_value.description IS NOT NULL,dropdown_value.description,''),'----'),
    IF(dropdown_value.other = -1,IFNULL(NEW.dropdown_other,''),''))
)
FROM
 tbl_item_specific_location as dropdown_value
LEFT JOIN tbl_item_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN tbl_item_specific_location AS ip3 ON ip2.parent_id = ip3.id
WHERE
 FIND_IN_SET(dropdown_value.id, NEW.dropdown_data_item_id)
);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_spf_update`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_spf_update` AFTER UPDATE ON `tbl_item_specific_location_value` FOR EACH ROW BEGIN
REPLACE INTO tbl_item_specific_location_view SET item_id = NEW.item_id, specific_location = (SELECT CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
GROUP_CONCAT(
    CONCAT(IF(dropdown_value.description IS NOT NULL,dropdown_value.description,''),'----'),
    IF(dropdown_value.other = -1,IFNULL(NEW.dropdown_other,''),''))
)
FROM
 tbl_item_specific_location as dropdown_value
LEFT JOIN tbl_item_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN tbl_item_specific_location AS ip3 ON ip2.parent_id = ip3.id
WHERE
 FIND_IN_SET(dropdown_value.id, NEW.dropdown_data_item_id)
);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tbl_item_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_item_spf_delete`;
delimiter ;;
CREATE DEFINER = `lrvl-lbhc`@`localhost` TRIGGER `trigger_item_spf_delete` AFTER DELETE ON `tbl_item_specific_location_value` FOR EACH ROW BEGIN
DELETE FROM tbl_item_specific_location_view WHERE item_id = OLD.item_id;
END
;;
delimiter ;
