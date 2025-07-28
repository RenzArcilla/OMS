/*
    * SQL schema for the Machine and Applicator database
    * This file contains the necessary SQL commands to create the database and its tables.
*/

-- Drop the database if existing
-- DROP DATABASE IF EXISTS machine_and_applicator; WARNING!!! WILL DELETE ALL DATA PERTAINING TO PREVIOUS DATABASE!

-- Create the database and use it
CREATE DATABASE IF NOT EXISTS machine_and_applicator;
USE machine_and_applicator;


-- Create the users table
-- This table stores user information including their roles and credentials.
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,    
    last_name VARCHAR(50) NOT NULL,
    user_type ENUM('DEFAULT', 'TOOLKEEPER', 'ADMIN') DEFAULT 'DEFAULT',

    INDEX idx_user_type (user_type)
);
    

-- Create the applicators table
-- This table stores information about machines including their specifications and status.
CREATE TABLE applicators (
    applicator_id INT PRIMARY KEY AUTO_INCREMENT,
    hp_no VARCHAR(50) UNIQUE NOT NULL,
    terminal_no VARCHAR(50) NOT NULL,
    description ENUM('SIDE', 'END', 'CLAMP', 'STRIP AND CLAMP') NOT NULL,
    wire ENUM('BIG', 'SMALL') NOT NULL,
    terminal_maker VARCHAR(50) NOT NULL,
    applicator_maker VARCHAR(50) NOT NULL,
    serial_no VARCHAR(50) DEFAULT 'NO RECORD',
    invoice_no VARCHAR(50) DEFAULT 'NO RECORD',
    last_encoded DATETIME DEFAULT NULL,

    INDEX idx_terminal_no (terminal_no),
    INDEX idx_terminal_maker (terminal_maker),
    INDEX idx_last_encoded (last_encoded)
);


-- Create the machines table
-- This table stores information about machines including their specifications and status.
CREATE TABLE machines (
    machine_id INT PRIMARY KEY AUTO_INCREMENT,
    control_no VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    maker VARCHAR(50) NOT NULL,
    serial_no VARCHAR(50) DEFAULT 'NO RECORD',
    invoice_no VARCHAR(50) DEFAULT 'NO RECORD',
    last_encoded DATETIME DEFAULT NULL,

    INDEX idx_maker (maker),
    INDEX idx_last_encoded (last_encoded)
);


-- Create the records table
-- This table stores records of inspections, including details about the machine, applicator, and inspection
CREATE TABLE records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    shift ENUM('1st', '2nd', 'NIGHT') NOT NULL,
    machine_id INT NOT NULL,
    applicator1_id INT NOT NULL,
    applicator2_id INT,
    created_by INT NOT NULL,
    date_inspected DATE NOT NULL,
    date_encoded DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id),
    FOREIGN KEY (applicator1_id) REFERENCES applicators(applicator_id),
    FOREIGN KEY (applicator2_id) REFERENCES applicators(applicator_id),

    INDEX idx_machine_id (machine_id),
    INDEX idx_created_by (created_by),
    INDEX idx_date_encoded (date_encoded),
    INDEX idx_is_active (is_active),
    INDEX idx_applicator1_id (applicator1_id),
    INDEX idx_applicator2_id (applicator2_id),
    INDEX idx_applicator1_active (applicator1_id, is_active),
    INDEX idx_applicator2_active (applicator2_id, is_active)
);


-- Create the applicator_outputs table
-- This table stores the outputs of applicators for each record, including activity status
CREATE TABLE applicator_outputs (
    applicator_output_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT NOT NULL,
    applicator_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    total_output INT NOT NULL,
    wire_crimper INT NOT NULL,
    wire_anvil INT NOT NULL,
    insulation_crimper INT NOT NULL,
    insulation_anvil INT NOT NULL,
    slide_cutter INT DEFAULT NULL,
    cutter_holder INT DEFAULT NULL,
    shear_blade INT DEFAULT NULL,
    cutter_a INT DEFAULT NULL,
    cutter_b INT DEFAULT NULL,
    FOREIGN KEY (record_id) REFERENCES records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (applicator_id) REFERENCES applicators(applicator_id),

    INDEX idx_record_id (record_id),
    INDEX idx_applicator_id (applicator_id),
    INDEX idx_is_active (is_active)
);


-- Create the machine_outputs table
-- This table stores the outputs of machines for each record including activity status
CREATE TABLE machine_outputs (
    machine_output_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT NOT NULL,
    machine_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    total_machine_output INT NOT NULL,
    cut_blade INT NOT NULL,
    strip_blade_a INT NOT NULL,
    strip_blade_b INT NOT NULL,
    FOREIGN KEY (record_id) REFERENCES records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id),

    INDEX idx_record_id (record_id),
    INDEX idx_is_active (is_active)
);