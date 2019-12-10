SELECT `order` from orders
WHERE
    orderId  LIKE :orderId
AND 
    