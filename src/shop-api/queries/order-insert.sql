INSERT INTO `shop`.`orders` (`orderId`, `uid`, `email`, `order`, `year`)
VALUES
  (
    :orderId,
    :uid, 
    :email,
    :order,
    YEAR(NOW())
  );