SELECT `order` from orders
WHERE
    orderId  LIKE :orderId
ORDER BY `order`->>"$.dates.creationDate" DESC
LIMIT  :limit