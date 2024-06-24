START TRANSACTION;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE products;
TRUNCATE TABLE stock;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO products (name) VALUES
    ('Lnv Ip3 14 Cel 64Gb Grey Chrome'),
    ('Lenovo ThinkPad T14'),
    ('Acer Chromebook'),
    ('Microsoft Surface Go 3 64 GB 26.7 Cm'),
    ('ASUS E410MA 14" Laptop 4GB RAM'),
    ('HP Pavilion x360 14-dw1010nr'),
    ('Dell Inspiron 15 3000'),
    ('Apple MacBook Air 13" M1'),
    ('Samsung Galaxy Book Flex2 Alpha'),
    ('Google Pixelbook Go'),
    ('Razer Blade 15 Base Gaming Laptop'),
    ('MSI GF63 Thin 10SCXR-222'),
    ('Huawei MateBook D 15'),
    ('Toshiba Dynabook Tecra A50-F'),
    ('LG Gram 14Z90P Laptop');

INSERT INTO stock (product_id, owner_id, owner_type, quantity, entry_time) VALUES
    (1, 1, 'supermarket', 100, '2024-06-23 10:00:00'),
    (2, 2, 'wholesaler', 150, '2024-06-23 11:00:00'),
    (3, 3, 'outlet', 200, '2024-06-23 12:00:00'),
    (4, 4, 'supermarket', 120, '2024-06-23 13:00:00'),
    (5, 5, 'wholesaler', 130, '2024-06-23 14:00:00'),
    (6, 6, 'outlet', 140, '2024-06-23 15:00:00'),
    (7, 7, 'supermarket', 160, '2024-06-23 16:00:00'),
    (8, 8, 'wholesaler', 170, '2024-06-23 17:00:00'),
    (9, 9, 'outlet', 180, '2024-06-23 18:00:00'),
    (10, 10, 'supermarket', 190, '2024-06-23 19:00:00'),
    (11, 1, 'wholesaler', 200, '2024-06-23 20:00:00'),
    (12, 2, 'outlet', 210, '2024-06-23 21:00:00'),
    (13, 3, 'supermarket', 220, '2024-06-23 22:00:00'),
    (14, 4, 'wholesaler', 230, '2024-06-23 23:00:00'),
    (15, 5, 'outlet', 240, '2024-06-24 00:00:00'),
    (1, 6, 'supermarket', 250, '2024-06-24 01:00:00'),
    (2, 7, 'wholesaler', 260, '2024-06-24 02:00:00'),
    (3, 8, 'outlet', 270, '2024-06-24 03:00:00'),
    (4, 9, 'supermarket', 280, '2024-06-24 04:00:00'),
    (5, 10, 'wholesaler', 290, '2024-06-24 05:00:00'),
    (6, 1, 'outlet', 300, '2024-06-24 06:00:00'),
    (7, 2, 'supermarket', 310, '2024-06-24 07:00:00'),
    (8, 3, 'wholesaler', 320, '2024-06-24 08:00:00'),
    (9, 4, 'outlet', 330, '2024-06-24 09:00:00'),
    (10, 5, 'supermarket', 340, '2024-06-24 10:00:00'),
    (11, 6, 'wholesaler', 350, '2024-06-24 11:00:00'),
    (12, 7, 'outlet', 360, '2024-06-24 12:00:00'),
    (13, 8, 'supermarket', 370, '2024-06-24 13:00:00'),
    (14, 9, 'wholesaler', 380, '2024-06-24 14:00:00'),
    (15, 10, 'outlet', 390, '2024-06-24 15:00:00'),
    (1, 1, 'supermarket', 400, '2024-06-24 16:00:00'),
    (2, 2, 'wholesaler', 410, '2024-06-24 17:00:00'),
    (3, 3, 'outlet', 420, '2024-06-24 18:00:00'),
    (4, 4, 'supermarket', 430, '2024-06-24 19:00:00'),
    (5, 5, 'wholesaler', 440, '2024-06-24 20:00:00'),
    (6, 6, 'outlet', 450, '2024-06-24 21:00:00'),
    (7, 7, 'supermarket', 460, '2024-06-24 22:00:00'),
    (8, 8, 'wholesaler', 470, '2024-06-24 23:00:00'),
    (9, 9, 'outlet', 480, '2024-06-25 00:00:00'),
    (10, 10, 'supermarket', 490, '2024-06-25 01:00:00'),
    (11, 1, 'wholesaler', 500, '2024-06-25 02:00:00'),
    (12, 2, 'outlet', 510, '2024-06-25 03:00:00'),
    (13, 3, 'supermarket', 520, '2024-06-25 04:00:00'),
    (14, 4, 'wholesaler', 530, '2024-06-25 05:00:00'),
    (15, 5, 'outlet', 540, '2024-06-25 06:00:00'),
    (1, 6, 'supermarket', 550, '2024-06-25 07:00:00'),
    (2, 7, 'wholesaler', 560, '2024-06-25 08:00:00');

COMMIT;