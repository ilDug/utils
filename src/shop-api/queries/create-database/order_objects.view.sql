CREATE VIEW order_objects AS
    SELECT 
        o.orderId,
        o.uid,
        o.year,
        JSON_SET(o.order, '$.articles', a.articles_array) AS `order`
    FROM
        orders o
            LEFT JOIN

        (
            SELECT 
                aa.orderId,

                JSON_ARRAYAGG(
                    IF(aa.articleId IS NOT NULL, aa.article, NULL)
                ) AS articles_array

            FROM
                shop.articles aa
            GROUP BY aa.orderId
        ) a 

            USING (orderId)