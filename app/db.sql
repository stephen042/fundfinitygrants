CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,  -- Store hashed passwords
    role ENUM('admin', 'agent') NOT NULL
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    date_of_birth DATE,
    gender VARCHAR(10),
    contact_number VARCHAR(15),
    email_address VARCHAR(100),
    address TEXT,
    monthly_income DECIMAL(10, 2),
    household_income DECIMAL(10, 2),
    employment_status VARCHAR(20),
    income_source VARCHAR(100),
    outstanding_debts TEXT,
    grant_reason TEXT,
    requested_amount DECIMAL(10, 2),
    grant_purpose VARCHAR(50),
    other_grants TEXT,
    residence_status VARCHAR(50),
    disadvantaged_group TEXT,
    criminal_record BOOLEAN,
    declaration BOOLEAN,
    eligibility_status ENUM('pending', 'eligible', 'ineligible') DEFAULT 'pending'
);

CREATE TABLE grants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT,
    grant_amount DECIMAL(10, 2),
    disbursement_status ENUM('pending', 'disbursed') DEFAULT 'pending',
    disbursement_date DATE,
    FOREIGN KEY (application_id) REFERENCES applications(id)
);

CREATE TABLE agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Store hashed password
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Store hashed password
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE applications_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    requested_grant_amount DECIMAL(10, 2) NOT NULL,
    reason_for_applying TEXT NOT NULL,
    status ENUM('Pending', 'Approved', 'Denied') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
