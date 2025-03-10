CREATE TABLE IF NOT EXISTS `outlets`
(
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `wholesaler_id` BIGINT UNSIGNED,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`wholesaler_id`) REFERENCES `wholesalers`(`id`) ON DELETE CASCADE
);