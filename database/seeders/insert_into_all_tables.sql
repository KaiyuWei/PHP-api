SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO users (name, email, password, role) VALUES
    ('Alice Johnson', 'alice@example.com', 'password123', 'admin'),
    ('Bob Smith', 'bob@example.com', 'password123', 'trainee'),
    ('Charlie Brown', 'charlie@example.com', 'password123', 'admin'),
    ('Dana White', 'dana@example.com', 'password123', 'trainee'),
    ('Eve Black', 'eve@example.com', 'password123', 'admin');

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE products;
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
                                ('LG Gram 14Z90P Laptop'),
                                ('Dell XPS 13 9310'),
                                ('HP Spectre x360 14'),
                                ('Asus ZenBook 14 UX425EA'),
                                ('Acer Swift 3 SF314-42-R9YN'),
                                ('Microsoft Surface Laptop 4');

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE supermarkets;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO supermarkets (name) VALUES
                                    ('Albert Heijn'),
                                    ('Jumbo'),
                                    ('Lidl'),
                                    ('Aldi'),
                                    ('Carrefour'),
                                    ('Tesco'),
                                    ('Sainsbury'),
                                    ('Asda'),
                                    ('Morrisons'),
                                    ('Waitrose'),
                                    ('Co-op'),
                                    ('Marks & Spencer'),
                                    ('Spar'),
                                    ('Costco'),
                                    ('Walmart'),
                                    ('Target'),
                                    ('Whole Foods Market'),
                                    ('Kroger'),
                                    ('Safeway'),
                                    ('Publix');

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE wholesalers;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO wholesalers (name) VALUES
                                   ('Global Wholesale Distributors'),
                                   ('Premier Wholesale Solutions'),
                                   ('United Wholesalers Group'),
                                   ('Wholesale Central Supply'),
                                   ('Mega Wholesale Inc.'),
                                   ('Prime Distributors Co.'),
                                   ('Advanced Wholesale Services'),
                                   ('National Wholesale Enterprises'),
                                   ('Elite Wholesale Partners'),
                                   ('Superior Wholesale Ltd.'),
                                   ('Dynamic Wholesale Network'),
                                   ('Universal Wholesale Trading'),
                                   ('Alliance Wholesale Company'),
                                   ('Infinity Wholesale Supply'),
                                   ('Summit Wholesale Corporation'),
                                   ('Titan Wholesale Group'),
                                   ('Nexus Wholesale Solutions'),
                                   ('Pioneer Wholesale Providers'),
                                   ('Ultimate Wholesale Distributors'),
                                   ('Vertex Wholesale Enterprises');

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE outlets;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO outlets (name, wholesaler_id) VALUES
                                              ('Tech Outlet Central', 9),
                                              ('Gadget Hub', 12),
                                              ('Electronics Express', 20),
                                              ('Appliance Depot', 14),
                                              ('Digital World', 3),
                                              ('Techie Town', 3),
                                              ('Smart Gadgets Store', 5),
                                              ('Innovation Electronics', 12),
                                              ('Tech Haven', 15),
                                              ('FutureTech Store', 6),
                                              ('Tech Solutions Outlet', 4),
                                              ('Device City', 19),
                                              ('Gizmo Galaxy', 1),
                                              ('Electronic Emporium', 4),
                                              ('Gadget Zone', 8),
                                              ('Tech World Outlet', 11),
                                              ('NextGen Electronics', 1),
                                              ('Digital Dreams', 7),
                                              ('TechSavvy Shop', 8),
                                              ('Smart Solutions', 8);

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE supermarket_wholesaler;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO supermarket_wholesaler (supermarket_id, wholesaler_id) VALUES
                                                                       (1, 5),
                                                                       (2, 8),
                                                                       (3, 12),
                                                                       (4, 1),
                                                                       (5, 9),
                                                                       (6, 14),
                                                                       (7, 7),
                                                                       (8, 3),
                                                                       (9, 19),
                                                                       (10, 4),
                                                                       (11, 13),
                                                                       (12, 2),
                                                                       (13, 16),
                                                                       (14, 6),
                                                                       (15, 20),
                                                                       (16, 11),
                                                                       (17, 17),
                                                                       (18, 10),
                                                                       (19, 15),
                                                                       (20, 18);

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE stock;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO stock (product_id, owner_id, owner_type, quantity, entry_time) VALUES
                                                                               (1, 1, 'supermarket', 100, '2024-06-23 10:00:00'),
                                                                               (2, 2, 'wholesaler', 150, '2024-06-23 11:00:00'),
                                                                               (3, 3, 'outlet', 200, '2024-06-23 12:00:00'),
                                                                               (4, 4, 'supermarket', 120, '2024-06-23 13:00:00'),
                                                                               (5, 5, 'wholesaler', 180, '2024-06-23 14:00:00'),
                                                                               (6, 6, 'outlet', 90, '2024-06-23 15:00:00'),
                                                                               (7, 7, 'supermarket', 110, '2024-06-23 16:00:00'),
                                                                               (8, 8, 'wholesaler', 160, '2024-06-23 17:00:00'),
                                                                               (9, 9, 'outlet', 140, '2024-06-23 18:00:00'),
                                                                               (10, 10, 'supermarket', 130, '2024-06-23 19:00:00'),
                                                                               (11, 11, 'wholesaler', 170, '2024-06-23 20:00:00'),
                                                                               (12, 12, 'outlet', 190, '2024-06-23 21:00:00'),
                                                                               (13, 13, 'supermarket', 210, '2024-06-23 22:00:00'),
                                                                               (14, 14, 'wholesaler', 220, '2024-06-23 23:00:00'),
                                                                               (15, 15, 'outlet', 230, '2024-06-24 00:00:00'),
                                                                               (16, 16, 'supermarket', 240, '2024-06-24 01:00:00'),
                                                                               (17, 17, 'wholesaler', 250, '2024-06-24 02:00:00'),
                                                                               (18, 18, 'outlet', 260, '2024-06-24 03:00:00'),
                                                                               (19, 19, 'supermarket', 270, '2024-06-24 04:00:00'),
                                                                               (20, 20, 'wholesaler', 280, '2024-06-24 05:00:00'),
                                                                               (1, 2, 'outlet', 150, '2024-06-24 06:00:00'),
                                                                               (2, 3, 'supermarket', 160, '2024-06-24 07:00:00'),
                                                                               (3, 4, 'wholesaler', 170, '2024-06-24 08:00:00'),
                                                                               (4, 5, 'outlet', 180, '2024-06-24 09:00:00'),
                                                                               (5, 6, 'supermarket', 190, '2024-06-24 10:00:00'),
                                                                               (6, 7, 'wholesaler', 200, '2024-06-24 11:00:00'),
                                                                               (7, 8, 'outlet', 210, '2024-06-24 12:00:00'),
                                                                               (8, 9, 'supermarket', 220, '2024-06-24 13:00:00'),
                                                                               (9, 10, 'wholesaler', 230, '2024-06-24 14:00:00'),
                                                                               (10, 11, 'outlet', 240, '2024-06-24 15:00:00'),
                                                                               (11, 12, 'supermarket', 250, '2024-06-24 16:00:00'),
                                                                               (12, 13, 'wholesaler', 260, '2024-06-24 17:00:00'),
                                                                               (13, 14, 'outlet', 270, '2024-06-24 18:00:00'),
                                                                               (14, 15, 'supermarket', 280, '2024-06-24 19:00:00'),
                                                                               (15, 16, 'wholesaler', 290, '2024-06-24 20:00:00'),
                                                                               (16, 17, 'outlet', 300, '2024-06-24 21:00:00'),
                                                                               (17, 18, 'supermarket', 310, '2024-06-24 22:00:00'),
                                                                               (18, 19, 'wholesaler', 320, '2024-06-24 23:00:00'),
                                                                               (19, 20, 'outlet', 330, '2024-06-25 00:00:00'),
                                                                               (20, 1, 'supermarket', 340, '2024-06-25 01:00:00'),
                                                                               (1, 3, 'wholesaler', 350, '2024-06-25 02:00:00'),
                                                                               (2, 5, 'outlet', 360, '2024-06-25 03:00:00'),
                                                                               (3, 7, 'supermarket', 370, '2024-06-25 04:00:00'),
                                                                               (4, 9, 'wholesaler', 380, '2024-06-25 05:00:00'),
                                                                               (5, 11, 'outlet', 390, '2024-06-25 06:00:00'),
                                                                               (6, 13, 'supermarket', 400, '2024-06-25 07:00:00'),
                                                                               (7, 15, 'wholesaler', 410, '2024-06-25 08:00:00'),
                                                                               (8, 17, 'outlet', 420, '2024-06-25 09:00:00'),
                                                                               (9, 19, 'supermarket', 430, '2024-06-25 10:00:00'),
                                                                               (10, 1, 'wholesaler', 440, '2024-06-25 11:00:00'),
                                                                               (11, 2, 'outlet', 450, '2024-06-25 12:00:00'),
                                                                               (12, 3, 'supermarket', 460, '2024-06-25 13:00:00'),
                                                                               (13, 4, 'wholesaler', 470, '2024-06-25 14:00:00'),
                                                                               (14, 5, 'outlet', 480, '2024-06-25 15:00:00'),
                                                                               (15, 6, 'supermarket', 490, '2024-06-25 16:00:00'),
                                                                               (16, 7, 'wholesaler', 500, '2024-06-25 17:00:00'),
                                                                               (17, 8, 'outlet', 510, '2024-06-25 18:00:00'),
                                                                               (18, 9, 'supermarket', 520, '2024-06-25 19:00:00'),
                                                                               (19, 10, 'wholesaler', 530, '2024-06-25 20:00:00'),
                                                                               (20, 11, 'outlet', 540, '2024-06-25 21:00:00'),
                                                                               (1, 12, 'supermarket', 550, '2024-06-25 22:00:00'),
                                                                               (2, 13, 'wholesaler', 560, '2024-06-25 23:00:00'),
                                                                               (3, 14, 'outlet', 570, '2024-06-26 00:00:00'),
                                                                               (4, 15, 'supermarket', 580, '2024-06-26 01:00:00'),
                                                                               (5, 16, 'wholesaler', 590, '2024-06-26 02:00:00'),
                                                                               (6, 17, 'outlet', 600, '2024-06-26 03:00:00'),
                                                                               (7, 18, 'supermarket', 610, '2024-06-26 04:00:00'),
                                                                               (8, 19, 'wholesaler', 620, '2024-06-26 05:00:00'),
                                                                               (9, 20, 'outlet', 630, '2024-06-26 06:00:00'),
                                                                               (10, 1, 'supermarket', 640, '2024-06-26 07:00:00'),
                                                                               (11, 2, 'wholesaler', 650, '2024-06-26 08:00:00'),
                                                                               (12, 3, 'outlet', 660, '2024-06-26 09:00:00'),
                                                                               (13, 4, 'supermarket', 670, '2024-06-26 10:00:00'),
                                                                               (14, 5, 'wholesaler', 680, '2024-06-26 11:00:00'),
                                                                               (15, 6, 'outlet', 690, '2024-06-26 12:00:00'),
                                                                               (16, 7, 'supermarket', 700, '2024-06-26 13:00:00'),
                                                                               (17, 8, 'wholesaler', 710, '2024-06-26 14:00:00'),
                                                                               (18, 9, 'outlet', 720, '2024-06-26 15:00:00'),
                                                                               (19, 10, 'supermarket', 730, '2024-06-26 16:00:00'),
                                                                               (20, 11, 'wholesaler', 740, '2024-06-26 17:00:00'),
                                                                               (1, 12, 'outlet', 750, '2024-06-26 18:00:00'),
                                                                               (2, 13, 'supermarket', 760, '2024-06-26 19:00:00'),
                                                                               (3, 14, 'wholesaler', 770, '2024-06-26 20:00:00'),
                                                                               (4, 15, 'outlet', 780, '2024-06-26 21:00:00'),
                                                                               (5, 16, 'supermarket', 790, '2024-06-26 22:00:00'),
                                                                               (6, 17, 'wholesaler', 800, '2024-06-26 23:00:00'),
                                                                               (7, 18, 'outlet', 810, '2024-06-27 00:00:00'),
                                                                               (8, 19, 'supermarket', 820, '2024-06-27 01:00:00'),
                                                                               (9, 20, 'wholesaler', 830, '2024-06-27 02:00:00'),
                                                                               (5, 5, 'wholesaler', 180, '2024-06-23 14:00:00'),
                                                                               (6, 6, 'outlet', 90, '2024-06-23 15:00:00'),
                                                                               (7, 7, 'supermarket', 110, '2024-06-23 16:00:00'),
                                                                               (8, 8, 'wholesaler', 160, '2024-06-23 17:00:00'),
                                                                               (9, 9, 'outlet', 140, '2024-06-23 18:00:00'),
                                                                               (10, 10, 'supermarket', 130, '2024-06-23 19:00:00'),
                                                                               (11, 11, 'wholesaler', 170, '2024-06-23 20:00:00'),
                                                                               (12, 12, 'outlet', 190, '2024-06-23 21:00:00'),
                                                                               (13, 13, 'supermarket', 210, '2024-06-23 22:00:00'),
                                                                               (14, 14, 'wholesaler', 220, '2024-06-23 23:00:00'),
                                                                               (15, 15, 'outlet', 230, '2024-06-24 00:00:00');