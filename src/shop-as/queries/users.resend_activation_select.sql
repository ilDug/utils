SELECT
  u.uid,
  u.active,
  a.activationKey,
  a.scope,
  a.activationDate
FROM activations a
JOIN users u USING (uid)
WHERE
  email = :email AND a.scope = :scope