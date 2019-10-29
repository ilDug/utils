SELECT 
 p.product
FROM products p
LEFT JOIN product_categories pc
    USING (productId)
LEFT JOIN categories c 
    ON pc.categoryId = c.name
WHERE p.hidden <= :hidden
ORDER BY p.identifier
