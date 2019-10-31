SELECT 
    a.activationKey as restoreKey,
    a.generationKeyDate,
    a.activationDate,
    a.uid,
    a.scope,
    u.email
FROM activations a
JOIN users u USING (uid)
WHERE activationKey = :key;
