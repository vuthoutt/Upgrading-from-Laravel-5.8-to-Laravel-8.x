CREATE TRIGGER trigger_item_ab_insert AFTER INSERT ON tbl_item_asbestos_type_value FOR EACH ROW BEGIN
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
END;

CREATE TRIGGER trigger_item_ab_update AFTER UPDATE ON tbl_item_asbestos_type_value FOR EACH ROW BEGIN
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
END;

CREATE TRIGGER trigger_item_ab_delete AFTER DELETE ON tbl_item_asbestos_type_value FOR EACH ROW BEGIN
DELETE FROM tbl_item_asbestos_type_view WHERE item_id = OLD.item_id;
END;