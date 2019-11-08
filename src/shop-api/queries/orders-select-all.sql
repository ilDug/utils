/* SELECT 
	JSON_SET(
		o.order, 
		'$.articles', 
        (
            # raggruppa le categorie in un array
			JSON_ARRAYAGG( 
                # crea un JSON con i valori della tabella categories
                IF(
					a.articleId is not null, 
					a.article ,
                    NULL
                )
			)
        )
	)  AS ordine,

   ROUND( UNIX_TIMESTAMP( CURTIME(3) ) * 1000 ) AS ora

FROM
    orders o
        LEFT JOIN
    articles a 
		USING (orderId)
WHERE 
    o.uid  LIKE :uid
    
GROUP BY o.orderId; */

# ******************************************************************** 
