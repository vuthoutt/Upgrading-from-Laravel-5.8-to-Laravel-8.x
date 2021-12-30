CREATE TABLE tbl_item_licensed_non_licensed_view
SELECT temp1.item_id as item_id, temp1.licensed_non_licensed FROM
(SELECT i.id AS item_id, ip1.other as other4,
GROUP_CONCAT(
IF(ip1.description IS NOT NULL,CONCAT(ip1.description, ''),''), IF(ip1.other = -1,IFNULL(ipv.dropdown_other,''),'')) AS licensed_non_licensed
FROM tbl_items i
LEFT JOIN (SELECT * FROM tbl_item_licensed_non_licensed_value GROUP BY item_id) AS ipv ON i.id = ipv.item_id
LEFT JOIN tbl_item_licensed_non_licensed AS ip1 ON ip1.id = ipv.dropdown_data_item_id
GROUP BY i.id
) AS temp1
CREATE UNIQUE INDEX item_id_index ON tbl_item_licensed_non_licensed_view(item_id);