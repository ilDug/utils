UPDATE orders
SET 
    `order` = :order
WHERE 
    orderId = :orderId 
AND 
    uid = :uid;