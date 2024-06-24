CREATE TABLE supermarket_wholesaler (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `supermarket_id` BIGINT UNSIGNED NOT NULL,
    `wholesaler_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`supermarket_id`) REFERENCES supermarkets(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`wholesaler_id`) REFERENCES wholesalers(`id`) ON DELETE CASCADE
);