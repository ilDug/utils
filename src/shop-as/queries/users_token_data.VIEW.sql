CREATE VIEW users_token_data AS 
    SELECT 
        u.uid AS uid,
        u.username AS username,
        u.email AS email,
        u.firstName AS firstName,
        u.familyName AS familyName,
        JSON_ARRAYAGG(ua.authorization) AS authorizations 
    FROM 
        users u 
            LEFT JOIN 
        user_authorizations ua 
            ON u.uid =  ua.uid 
    GROUP BY u.uid;
