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
) AS temp1
CREATE UNIQUE INDEX item_id_index ON tbl_item_additional_information_view(item_id);