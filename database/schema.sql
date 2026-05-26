-- Referencia alineada con tu tabla users actual.
-- Si ya tienes la tabla, no hace falta ejecutar esto; el backend lee columnas con DESCRIBE.

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  uuid CHAR(36) NOT NULL,
  first_name VARCHAR(255) NOT NULL,
  last_name VARCHAR(255) NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL DEFAULT 'user',
  status VARCHAR(50) NOT NULL DEFAULT 'active',
  email_verified_at DATETIME NULL,
  last_login_at DATETIME NULL,
  last_login_ip VARCHAR(45) NULL,
  token VARCHAR(255) NULL,
  token_expires_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  UNIQUE KEY uk_users_uuid (uuid),
  UNIQUE KEY uk_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
