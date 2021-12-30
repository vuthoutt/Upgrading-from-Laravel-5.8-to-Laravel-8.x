CREATE TRIGGER trigger_item_spf_insert AFTER INSERT ON tbl_item_specific_location_value FOR EACH ROW BEGIN
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
END;

CREATE TRIGGER trigger_item_spf_update AFTER UPDATE ON tbl_item_specific_location_value FOR EACH ROW BEGIN
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
END;

CREATE TRIGGER trigger_item_spf_delete AFTER DELETE ON tbl_item_specific_location_value FOR EACH ROW BEGIN
DELETE FROM tbl_item_specific_location_view WHERE item_id = OLD.item_id;
END;