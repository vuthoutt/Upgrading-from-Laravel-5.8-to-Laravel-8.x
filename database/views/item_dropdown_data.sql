CREATE  TABLE tbl_item_asbestos_type_view
SELECT temp1.item_id1 as item_id, temp1.asbestos_type FROM
(SELECT i.id AS item_id1, ip1.other as other1,
GROUP_CONCAT(IF(ip3.description IS NOT NULL,CONCAT(ip3.description, '---'),''),IF(ip2.description IS NOT NULL,CONCAT(ip2.description, '---'),''),
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, '---'),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS asbestos_type
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_asbestos_type_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_asbestos_type AS ip1 ON ip1.id = ipv.dropdown_data_item_id
LEFT JOIN tbl_item_asbestos_type AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_asbestos_type AS ip3 ON ip2.parent_id = ip3.id
GROUP BY i.id) AS temp1;
-- ----------------------------------
CREATE TABLE tbl_item_accessibility_vulnerability_view
SELECT temp1.item_id as item_id, temp1.accessibility_vulnerability FROM
(SELECT i.id AS item_id, ip1.other as other7,
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''),
IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS accessibility_vulnerability
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_accessibility_vulnerability_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_accessibility_vulnerability AS ip1 ON ip1.id = ipv.dropdown_data_item_id
GROUP BY i.id
) AS temp1;

-- --------------------------------------
CREATE TABLE tbl_item_action_recommendation_view
SELECT temp1.item_id as item_id, temp1.action_recommendation FROM
(SELECT i.id AS item_id, ip1.other as other5,
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS action_recommendation
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_action_recommendation_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_action_recommendation AS ip1 ON ip1.id = ipv.dropdown_data_item_id
GROUP BY i.id
) AS temp1;

-- --------------------------------------
CREATE TABLE tbl_item_additional_information_view
SELECT temp1.item_id as item_id, temp1.additional_information FROM
(SELECT i.id AS item_id, ip1.other as other6,
GROUP_CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description, ''),''),IF(ip2.description IS NOT NULL,CONCAT(ip2.description, '---'),''),IF(ip1.description IS NOT NULL,CONCAT(ip1.description, '---'),''),
IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS additional_information
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_additional_information_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_additional_information AS ip1 ON ip1.id = ipv.dropdown_data_item_id
LEFT JOIN tbl_item_additional_information AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_additional_information AS ip3 ON ip2.parent_id = ip3.id
GROUP BY i.id
) AS temp1;

-- -------------------------------------
CREATE TABLE tbl_item_extent_view
SELECT temp1.item_id as item_id, temp1.extent FROM
(SELECT i.id AS item_id, ip1.other as other2,
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS extent
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_extent_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_extent AS ip1 ON ip1.id = ipv.dropdown_data_item_id
GROUP BY i.id) AS temp1;

-- ----------------------------
CREATE TABLE tbl_item_licensed_non_licensed_view
SELECT temp1.item_id as item_id, temp1.licensed_non_licensed FROM
(SELECT i.id AS item_id, ip1.other as other4,
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS licensed_non_licensed
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_licensed_non_licensed_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_licensed_non_licensed AS ip1 ON ip1.id = ipv.dropdown_data_item_id
GROUP BY i.id
) AS temp1;

-- ---------------------------
CREATE TABLE tbl_item_specific_location_view
SELECT temp1.item_id as item_id, temp1.specific_location FROM
(SELECT item_id , CONCAT(
IF(ip3.description IS NOT NULL,CONCAT(ip3.description,'----'),''),
IF(ip2.description IS NOT NULL,CONCAT(ip2.description,'----'),''),
dropdown_value.specific_location
)  as specific_location
from
(SELECT i.id AS item_id, ip1.other as other7,
ip1.parent_id as parent_id,
GROUP_CONCAT(
    CONCAT(IF(ip1.description IS NOT NULL,ip1.description,''),'----'),
    IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS specific_location
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_specific_location_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_specific_location ip1  ON FIND_IN_SET(ip1.id,ipv.dropdown_data_item_id )
GROUP BY i.id) as dropdown_value
LEFT JOIN tbl_item_specific_location AS ip2 ON dropdown_value.parent_id = ip2.id
LEFT JOIN tbl_item_specific_location AS ip3 ON ip2.parent_id = ip3.id
) AS temp1;
CREATE TABLE tbl_item_product_debris_view
SELECT temp1.item_id1 as item_id, temp1.product_debris FROM
(SELECT i.id AS item_id1, ip1.other as other1,
GROUP_CONCAT(IF(ip3.description IS NOT NULL,CONCAT(ip3.description, '---'),''),IF(ip2.description IS NOT NULL,CONCAT(ip2.description, '---'),''),
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, '---'),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS product_debris
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_product_debris_type_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_product_debris_type AS ip1 ON ip1.id = ipv.dropdown_data_item_id
LEFT JOIN tbl_item_product_debris_type AS ip2 ON ip1.parent_id = ip2.id
LEFT JOIN tbl_item_product_debris_type AS ip3 ON ip2.parent_id = ip3.id
LEFT JOIN tbl_item_product_debris_type AS ip4 ON ip3.parent_id = ip4.id
GROUP BY i.id) AS temp1;
CREATE UNIQUE INDEX item_id_index ON tbl_item_product_debris_view(item_id);
------------------------------
CREATE UNIQUE INDEX item_id_index ON tbl_item_specific_location_view(item_id);
CREATE UNIQUE INDEX item_id_index ON tbl_item_asbestos_type_view(item_id);
CREATE UNIQUE INDEX item_id_index ON tbl_item_accessibility_vulnerability_view(item_id);
CREATE UNIQUE INDEX item_id_index ON tbl_item_action_recommendation_view(item_id);
CREATE UNIQUE INDEX item_id_index ON tbl_item_additional_information_view(item_id);
CREATE UNIQUE INDEX item_id_index ON tbl_item_extent_view(item_id);
CREATE UNIQUE INDEX item_id_index ON tbl_item_licensed_non_licensed_view(item_id);