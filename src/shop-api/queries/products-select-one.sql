SELECT 
    # Aggiunge al JSON della colonna product il valore delle categorie
    JSON_SET(
		p.product, 
		'$.categories', 
        (
            # raggruppa le categorie in un array
			JSON_ARRAYAGG( 
                # crea un JSON con i valori della tabella categories
                IF(
					c.name is not null, 
					JSON_OBJECT('name', c.name, 'title', c.title) ,
                    NULL
                )
			)
        )
	) AS product
    
FROM
    products p
        LEFT JOIN
    product_categories pc USING (productId)
        LEFT JOIN
    categories c ON pc.categoryId = c.name
WHERE
    p.hidden <= :hidden AND p.productId = :productId
GROUP BY p.productId
ORDER BY p.identifier
