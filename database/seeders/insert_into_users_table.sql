SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;
INSERT INTO users (name, email, password, role) VALUES
    ('Alice Johnson', 'alice@example.com', 'password123', 'admin'),
    ('Bob Smith', 'bob@example.com', 'password123', 'trainee'),
    ('Charlie Brown', 'charlie@example.com', 'password123', 'admin'),
    ('Dana White', 'dana@example.com', 'password123', 'trainee'),
    ('Eve Black', 'eve@example.com', 'password123', 'admin');