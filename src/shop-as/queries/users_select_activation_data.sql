SELECT 
    a.activationKey as aKey,
    a.uid,
    a.scope,
    u.active
FROM activations a
JOIN users u USING (uid)
WHERE activationKey = :key;