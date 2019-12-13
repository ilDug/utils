SELECT 
*
FROM warehouse
WHERE 
    articleId LIKE :articleId 
AND
    available >= :available
