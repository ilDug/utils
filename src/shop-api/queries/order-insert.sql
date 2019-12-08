INSERT INTO `shop`.`orders` (`orderId`, `uid`, `order`, `year`)
VALUES
  (
    :orderId,
    :uid, 
    :order,
    YEAR(NOW())
  );