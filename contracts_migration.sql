CREATE TABLE IF NOT EXISTS contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contract_no VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    contract_type VARCHAR(100) DEFAULT 'Service Agreement',
    -- First Party
    first_party_name VARCHAR(255),
    first_party_address TEXT,
    first_party_phone VARCHAR(50),
    first_party_email VARCHAR(255),
    first_party_representative VARCHAR(255),
    first_party_designation VARCHAR(255),
    -- Second Party
    second_party_name VARCHAR(255),
    second_party_address TEXT,
    second_party_phone VARCHAR(50),
    second_party_email VARCHAR(255),
    second_party_representative VARCHAR(255),
    second_party_designation VARCHAR(255),
    -- Contract Details
    start_date DATE,
    end_date DATE,
    value DECIMAL(15,2) DEFAULT 0,
    contents LONGTEXT,
    terms_conditions TEXT,
    notes TEXT,
    status ENUM('Draft','Active','Expired','Terminated') DEFAULT 'Draft',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
