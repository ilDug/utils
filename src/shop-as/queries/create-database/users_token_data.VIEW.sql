DROP VIEW IF EXISTS users_token_data; 

CREATE VIEW users_token_data AS 
    SELECT 
        u.uid AS uid,
        u.email AS email,
        u.active AS acive,
        -- u.username AS username,
        -- u.firstName AS firstName,
        -- u.familyName AS familyName,
        JSON_ARRAYAGG(ua.authorization) AS authorizations 
    FROM 
        users u 
            LEFT JOIN 
        user_authorizations ua 
            ON u.uid =  ua.uid 
    GROUP BY u.uid;
