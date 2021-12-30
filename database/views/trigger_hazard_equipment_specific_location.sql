DROP TRIGGER IF EXISTS `trigger_equipment_spf_insert`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `trigger_equipment_spf_insert` AFTER INSERT ON `cp_equipment_specific_location_value` FOR EACH ROW BEGIN
REPLACE INTO cp_equipment_specific_location_view SET equipment_id = NEW.equipment_id, specific_location = (SELECT CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
GROUP_CONCAT(
    CONCAT(IF(dropdown_value.description IS NOT NULL,dropdown_value.description,''),'----'),
    IF(dropdown_value.other = -1,IFNULL(NEW.other,''),''))
)
FROM
 cp_equipment_specific_location as dropdown_value
LEFT JOIN cp_equipment_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN cp_equipment_specific_location AS ip3 ON ip2.parent_id = ip3.id
WHERE
 FIND_IN_SET(dropdown_value.id, NEW.value)
);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table cp_equipment_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_equipment_spf_update`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `trigger_equipment_spf_update` AFTER UPDATE ON `cp_equipment_specific_location_value` FOR EACH ROW BEGIN
REPLACE INTO cp_equipment_specific_location_view SET equipment_id = NEW.equipment_id, specific_location = (SELECT CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
GROUP_CONCAT(
    CONCAT(IF(dropdown_value.description IS NOT NULL,dropdown_value.description,''),'----'),
    IF(dropdown_value.other = -1,IFNULL(NEW.other,''),''))
)
FROM
 cp_equipment_specific_location as dropdown_value
LEFT JOIN cp_equipment_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN cp_equipment_specific_location AS ip3 ON ip2.parent_id = ip3.id
WHERE
 FIND_IN_SET(dropdown_value.id, NEW.value)
);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table cp_equipment_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_equipment_spf_delete`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `trigger_equipment_spf_delete` AFTER DELETE ON `cp_equipment_specific_location_value` FOR EACH ROW BEGIN
DELETE FROM cp_equipment_specific_location_view WHERE equipment_id = OLD.equipment_id;
END
;;
delimiter ;

DROP TRIGGER IF EXISTS `trigger_hazard_spf_insert`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `trigger_hazard_spf_insert` AFTER INSERT ON `cp_hazard_specific_location_value` FOR EACH ROW BEGIN
REPLACE INTO cp_hazard_specific_location_view SET hazard_id = NEW.hazard_id, specific_location = (SELECT CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
GROUP_CONCAT(
    CONCAT(IF(dropdown_value.description IS NOT NULL,dropdown_value.description,''),'----'),
    IF(dropdown_value.other = -1,IFNULL(NEW.other,''),''))
)
FROM
 cp_hazard_specific_location as dropdown_value
LEFT JOIN cp_hazard_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN cp_hazard_specific_location AS ip3 ON ip2.parent_id = ip3.id
WHERE
 FIND_IN_SET(dropdown_value.id, NEW.value)
);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table cp_hazard_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_hazard_spf_update`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `trigger_hazard_spf_update` AFTER UPDATE ON `cp_hazard_specific_location_value` FOR EACH ROW BEGIN
REPLACE INTO cp_hazard_specific_location_view SET hazard_id = NEW.hazard_id, specific_location = (SELECT CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
GROUP_CONCAT(
    CONCAT(IF(dropdown_value.description IS NOT NULL,dropdown_value.description,''),'----'),
    IF(dropdown_value.other = -1,IFNULL(NEW.other,''),''))
)
FROM
 cp_hazard_specific_location as dropdown_value
LEFT JOIN cp_hazard_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN cp_hazard_specific_location AS ip3 ON ip2.parent_id = ip3.id
WHERE
 FIND_IN_SET(dropdown_value.id, NEW.value)
);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table cp_hazard_specific_location_value
-- ----------------------------
DROP TRIGGER IF EXISTS `trigger_hazard_spf_delete`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `trigger_hazard_spf_delete` AFTER DELETE ON `cp_hazard_specific_location_value` FOR EACH ROW BEGIN
DELETE FROM cp_hazard_specific_location_view WHERE hazard_id = OLD.hazard_id;
END
;;
delimiter ;
