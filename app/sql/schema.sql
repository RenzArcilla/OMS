/*
    * SQL schema for the Machine and Applicator database
    * This file contains the necessary SQL commands to create the database and its tables.
*/


-- Create the database and use it
CREATE DATABASE IF NOT EXISTS machine_and_applicator;
USE machine_and_applicator;


-- Create the users table
-- This table stores user information including their roles and credentials.
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    user_type ENUM('DEFAULT', 'TECHNICIAN', 'TOOLKEEPER', 'SUPERVISOR') DEFAULT 'DEFAULT',

    INDEX idx_user_type (user_type)
);


-- Create the machines table
-- This table stores information about machines including their specifications and status.
CREATE TABLE applicators (
    applicator_id INT PRIMARY KEY AUTO_INCREMENT,
    hp_no VARCHAR(50) UNIQUE,
    terminal_no VARCHAR(50),
    description ENUM('SIDE', 'END'),
    wire ENUM('BIG', 'SMALL'),
    terminal_maker VARCHAR(50),
    applicator_maker VARCHAR(50),
    serial_no VARCHAR(50) DEFAULT 'NO RECORD',
    invoice_no VARCHAR(50) DEFAULT 'NO RECORD',
    last_encoded DATETIME DEFAULT NULL

    -- Total tracking fields
    total_output INT DEFAULT 0,
    wire_crimper INT DEFAULT 0,
    wire_anvil INT DEFAULT 0,
    insulation_crimper INT DEFAULT 0,
    insulation_anvil INT DEFAULT 0,
    slide_cutter INT DEFAULT NULL,
    cutter_holder INT DEFAULT NULL,
    shear_blade INT DEFAULT NULL,
    cutter_a INT DEFAULT NULL,
    cutter_b INT DEFAULT NULL,

    INDEX idx_terminal_no (terminal_no),
    INDEX idx_terminal_maker (terminal_maker),
    INDEX idx_last_encoded (last_encoded)
);


-- Create the record_applicator_outputs table
-- This table stores the outputs of applicators for each record, including versioning and activity status
CREATE TABLE record_applicator_outputs (
    applicator_output_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT,
    applicator_id INT,
    version INT DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    parent_id INT DEFAULT NULL,
    total_output INT,
    wire_crimper INT,
    wire_anvil INT,
    insulation_crimper INT,
    insulation_anvil INT,
    slide_cutter INT DEFAULT NULL,
    cutter_holder INT DEFAULT NULL,
    shear_blade INT DEFAULT NULL,
    cutter_a INT DEFAULT NULL,
    cutter_b INT DEFAULT NULL,
    FOREIGN KEY (record_id) REFERENCES records(record_id),
    FOREIGN KEY (applicator_id) REFERENCES applicators(applicator_id),
    FOREIGN KEY (parent_id) REFERENCES record_applicator_outputs(applicator_output_id),

    INDEX idx_record_id (record_id),
    INDEX idx_applicator_id (applicator_id),
    INDEX idx_is_active (is_active)
);


-- Create the machines table
-- This table stores information about machines including their specifications and status.
CREATE TABLE machines (
    machine_id INT PRIMARY KEY AUTO_INCREMENT,
    control_no VARCHAR(50) UNIQUE,
    description VARCHAR(50),
    model VARCHAR(50),
    maker VARCHAR(50),
    serial_no VARCHAR(50) DEFAULT 'NO RECORD',
    invoice_no VARCHAR(50) DEFAULT 'NO RECORD',
    last_encoded DATETIME DEFAULT NULL,

    INDEX idx_maker (maker),
    INDEX idx_last_encoded (last_encoded)
);


-- Create the record_machine_outputs table
-- This table stores the outputs of machines for each record, including versioning and activity status
CREATE TABLE record_machine_outputs (
    machine_output_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT,
    version INT DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    parent_id INT DEFAULT NULL,
    total_machine_output INT,
    cut_blade INT,
    strip_blade_a INT,
    strip_blade_b INT,
    FOREIGN KEY (record_id) REFERENCES records(record_id),
    FOREIGN KEY (parent_id) REFERENCES record_machine_outputs(machine_output_id),

    INDEX idx_record_id (record_id),
    INDEX idx_is_active (is_active)
);


-- Create the records table
-- This table stores records of inspections, including details about the machine, applicator, and inspection
CREATE TABLE records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    parent_id INT DEFAULT NULL,
    version INT NOT NULL DEFAULT 1,
    shift ENUM('1st', '2nd', 'NIGHT'),
    machine_id INT,
    applicator_id INT,
    created_by INT,
    date_inspected DATE,
    date_encoded DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id),
    FOREIGN KEY (applicator_id) REFERENCES applicators(applicator_id),
    FOREIGN KEY (parent_id) REFERENCES records(record_id),

    INDEX idx_machine_id (machine_id),
    INDEX idx_applicator_id (applicator_id),
    INDEX idx_created_by (created_by),
    INDEX idx_date_encoded (date_encoded),
    INDEX idx_is_active (is_active),
    INDEX idx_applicator_active (applicator_id, is_active)
);
