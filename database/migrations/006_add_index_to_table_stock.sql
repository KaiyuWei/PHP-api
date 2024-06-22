DELIMITER //

CREATE PROCEDURE create_index_if_not_exists()
BEGIN
    DECLARE index_owner_type_owner_id_exists INT DEFAULT 0;

    SELECT COUNT(1) INTO index_owner_type_owner_id_exists
    FROM information_schema.STATISTICS
    WHERE table_schema = DATABASE()
      AND table_name = 'stock'
      AND index_name = 'idx_owner_type_owner_id';

    IF index_owner_type_owner_id_exists = 0 THEN
        CREATE INDEX idx_owner_type_owner_id ON stock (owner_type, owner_id);
    END IF;
END //

DELIMITER ;

CALL create_index_if_not_exists();
DROP PROCEDURE create_index_if_not_exists;