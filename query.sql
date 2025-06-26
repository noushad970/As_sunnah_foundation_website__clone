CREATE DATABASE IF NOT EXISTS assumah;

USE assumah;

CREATE TABLE admin_users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    admin_id VARCHAR(100),
    password VARCHAR(255)
);

CREATE TABLE assunnah_donations (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(100),
    donor_number VARCHAR(20),
    amount DECIMAL(10, 2),
    payment_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tran_id VARCHAR(50),
    validated_on DATETIME,
    card_type VARCHAR(50),
    bank_tran_id VARCHAR(50)
);

CREATE TABLE assunnah_contact_submissions (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(150),
    message TEXT,
    submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE assunnah_gallery (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    image_title VARCHAR(100),
    image VARCHAR(255)
);

CREATE TABLE assunnah_projects (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT
);

CREATE TABLE assunnah_membership_applications (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    member_type VARCHAR(50),
    name VARCHAR(100),
    fathers_name VARCHAR(100),
    probashi TINYINT(1),
    phone_number VARCHAR(15),
    email VARCHAR(100),
    occupation VARCHAR(50),
    reference VARCHAR(100),
    reference_address VARCHAR(200),
    donation_payment_method VARCHAR(50),
    membership_status VARCHAR(20)
);

CREATE TABLE assunnah_volunteer_applications (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    probashi TINYINT(1),
    phone_number VARCHAR(15),
    emergency_phone VARCHAR(15),
    email VARCHAR(255),
    facebook_link VARCHAR(255),
    nid_number VARCHAR(20),
    educational_info VARCHAR(255),
    occupation VARCHAR(255),
    permanent_district_thana VARCHAR(255),
    permanent_address_text TEXT,
    present_address_text TEXT,
    volunteer_for VARCHAR(255),
    special_skill VARCHAR(255),
    application_status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE assunnah_temp_opt (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100),
    otp VARCHAR(6)
);

CREATE TABLE assunnah_ongoing_project (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    description TEXT
);