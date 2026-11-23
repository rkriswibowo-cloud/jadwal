CREATE TABLE IF NOT EXISTS academic_periods (
    academic_year CHAR(9) NOT NULL,
    semester ENUM('Ganjil', 'Genap') NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    synced_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (academic_year, semester)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
