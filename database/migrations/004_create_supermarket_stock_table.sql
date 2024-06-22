CREATE TABLE `stock_supermarket` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `supermarket_id` BIGINT UNSIGNED NOT NULL,
    `stock_quantity` INT UNSIGNED NOT NULL DEFAULT 0,
    `stock_entry_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `price` DECIMAL(10, 2),
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supermarket_id`) REFERENCES `supermarkets`(`id`) ON DELETE CASCADE
);
