UPDATE products 
SET 
    identifier = :identifier, 
    sku = :sku, 
    brand = :brand, 
    product = :product
WHERE
    productId = :productId
;