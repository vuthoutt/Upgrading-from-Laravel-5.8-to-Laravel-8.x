CREATE TABLE tbl_item_extent_view
SELECT temp1.item_id as item_id, temp1.extent FROM
(SELECT i.id AS item_id, ip1.other as other2,
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS extent
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_extent_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_extent AS ip1 ON ip1.id = ipv.dropdown_data_item_id
GROUP BY i.id) AS temp1
CREATE UNIQUE INDEX item_id_index ON tbl_item_extent_view(item_id);