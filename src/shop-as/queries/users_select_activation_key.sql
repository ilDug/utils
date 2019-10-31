SELECT 
    u.email,
    a.activationKey
FROM
    users u
        JOIN
    activations a USING (uid)
WHERE
    uid = :uid; 