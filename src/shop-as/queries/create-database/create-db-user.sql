CREATE USER 'shopauthuser'@'localhost' IDENTIFIED BY 'do4ng9sb45ig0sb38glqmxb';
GRANT  SELECT, INSERT, UPDATE, DELETE, EXECUTE, SHOW VIEW ON users.* TO `shopauthuser`@`localhost`;