CREATE TABLE `products` (
                            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `name` VARCHAR(50) NOT NULL UNIQUE,
                            `created_at` TIMESTAMP NULL DEFAULT NULL,
                            `updated_at` TIMESTAMP NULL DEFAULT NULL
);