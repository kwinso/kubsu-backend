CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Insert an admin with a hashed password
-- Cred are admin:admin
INSERT INTO admins (username, password) VALUES (
  'admin',
  '$2y$12$XpzZch1FcMM9YXK2d9bygeWbwwhHcacdeJFaYGRDkS4DPAsq7SReW'
);