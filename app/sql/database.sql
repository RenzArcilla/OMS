/*
    * Hybrid SQL schema for the Machine and Applicator database
    * Fixed columns for standard parts + JSON for custom parts
*/

CREATE DATABASE IF NOT EXISTS machine_and_applicator;
USE machine_and_applicator;

-- Create the users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,    
    last_name VARCHAR(50) NOT NULL,
    user_type ENUM('DEFAULT', 'TOOLKEEPER', 'ADMIN') DEFAULT 'DEFAULT',

    INDEX idx_username (username)
);

-- Create the applicators table
CREATE TABLE applicators (
    applicator_id INT PRIMARY KEY AUTO_INCREMENT,
    hp_no VARCHAR(50) UNIQUE NOT NULL,
    terminal_no VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    description ENUM('SIDE', 'END', 'CLAMP', 'STRIP AND CRIMP') NOT NULL,
    wire ENUM('BIG', 'SMALL') NOT NULL,
    terminal_maker VARCHAR(50) NOT NULL,
    applicator_maker VARCHAR(50) NOT NULL,
    serial_no VARCHAR(50) DEFAULT NULL,
    invoice_no VARCHAR(50) DEFAULT NULL,
    last_encoded DATETIME DEFAULT NULL,

    INDEX idx_hp_no (hp_no),
    INDEX idx_terminal_no (terminal_no),
    INDEX idx_terminal_maker (terminal_maker),
    INDEX idx_last_encoded (last_encoded),
    INDEX idx_applicators_is_active (is_active),
    INDEX idx_applicators_active_id (is_active, applicator_id)
);

-- Create the machines table
CREATE TABLE machines (
    machine_id INT PRIMARY KEY AUTO_INCREMENT,
    control_no VARCHAR(50) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    description VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    maker VARCHAR(50) NOT NULL,
    serial_no VARCHAR(50) DEFAULT NULL,
    invoice_no VARCHAR(50) DEFAULT NULL,
    last_encoded DATETIME DEFAULT NULL,

    INDEX idx_control_no (control_no),
    INDEX idx_maker (maker),
    INDEX idx_last_encoded (last_encoded),
    INDEX idx_machines_is_active (is_active),
    INDEX idx_machines_active_id (is_active, machine_id)
);

-- Create the records table
CREATE TABLE records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    shift ENUM('1st', '2nd', 'NIGHT') NOT NULL,
    machine_id INT NOT NULL,
    applicator1_id INT NOT NULL,
    applicator2_id INT,
    created_by INT NOT NULL,
    date_inspected DATE NOT NULL,
    date_encoded DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
    INDEX idx_applicator2_id (applicator2_id)
);

-- Custom part definitions (lightweight lookup)
CREATE TABLE custom_part_definitions (
    part_id INT PRIMARY KEY AUTO_INCREMENT,
    equipment_type ENUM('MACHINE', 'APPLICATOR') NOT NULL,
    part_name VARCHAR(100) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    UNIQUE KEY unique_equipment_part (equipment_type, part_name),
    INDEX idx_equipment_type (equipment_type),
    INDEX idx_is_active (is_active)
);

-- Hybrid applicator outputs table
CREATE TABLE applicator_outputs (
    applicator_output_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT NOT NULL,
    applicator_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    
    -- Standard parts (direct columns for performance)
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
    
    -- Custom parts (JSON for flexibility)
    custom_parts JSON DEFAULT NULL,
    
    FOREIGN KEY (record_id) REFERENCES records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (applicator_id) REFERENCES applicators(applicator_id),

    INDEX idx_record_id (record_id),
    INDEX idx_applicator_id (applicator_id),
    INDEX idx_is_active (is_active)
);

-- Hybrid machine outputs table
CREATE TABLE machine_outputs (
    machine_output_id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT NOT NULL,
    machine_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    
    -- Standard parts (direct columns)
    total_machine_output INT NOT NULL,
    cut_blade INT NOT NULL,
    strip_blade_a INT NOT NULL,
    strip_blade_b INT NOT NULL,
    
    -- Custom parts (JSON for flexibility)
    custom_parts JSON DEFAULT NULL,
    
    FOREIGN KEY (record_id) REFERENCES records(record_id) ON DELETE CASCADE,
    FOREIGN KEY (machine_id) REFERENCES machines(machine_id),

    INDEX idx_record_id (record_id),
    INDEX idx_machine_id (machine_id),
    INDEX idx_is_active (is_active)
);

-- Hybrid monitor tables
CREATE TABLE monitor_machine (
    monitor_id INT PRIMARY KEY AUTO_INCREMENT,
    machine_id INT NOT NULL UNIQUE,
    
    -- Standard parts totals
    total_machine_output INT DEFAULT 0,
    cut_blade_output INT DEFAULT 0,
    strip_blade_a_output INT DEFAULT 0,
    strip_blade_b_output INT DEFAULT 0,
    
    -- Custom parts totals
    custom_parts_output JSON DEFAULT NULL,

    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (machine_id) REFERENCES machines(machine_id) ON DELETE CASCADE,

    INDEX idx_machine_id (machine_id),
    INDEX idx_last_updated (last_updated)
);

CREATE TABLE monitor_applicator (
    monitor_id INT PRIMARY KEY AUTO_INCREMENT,
    applicator_id INT NOT NULL UNIQUE,

    -- Standard parts totals
    total_output INT DEFAULT 0,
    wire_crimper_output INT DEFAULT 0,
    wire_anvil_output INT DEFAULT 0,
    insulation_crimper_output INT DEFAULT 0,
    insulation_anvil_output INT DEFAULT 0,
    slide_cutter_output INT DEFAULT 0,
    cutter_holder_output INT DEFAULT 0,
    shear_blade_output INT DEFAULT 0,
    cutter_a_output INT DEFAULT 0,
    cutter_b_output INT DEFAULT 0,
    
    -- Custom parts totals
    custom_parts_output JSON DEFAULT NULL,

    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (applicator_id) REFERENCES applicators(applicator_id) ON DELETE CASCADE,

    INDEX idx_applicator_id (applicator_id),
    INDEX idx_last_updated (last_updated)
);

-- Reset tables with custom part support
CREATE TABLE machine_reset (
    reset_id INT PRIMARY KEY AUTO_INCREMENT,
    machine_id INT NOT NULL,
    reset_by INT NOT NULL,
    part_reset VARCHAR(50) NOT NULL, -- Can be standard part name or custom part code
    reset_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    remarks TEXT DEFAULT NULL,

    FOREIGN KEY (machine_id) REFERENCES machines(machine_id),
    FOREIGN KEY (reset_by) REFERENCES users(user_id),

    INDEX idx_machine_id (machine_id),
    INDEX idx_reset_time (reset_time)
);

CREATE TABLE applicator_reset (
    reset_id INT PRIMARY KEY AUTO_INCREMENT,
    applicator_id INT NOT NULL,
    reset_by INT NOT NULL,
    part_reset VARCHAR(50) NOT NULL, -- Can be standard part name or custom part code
    reset_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    remarks TEXT DEFAULT NULL,

    FOREIGN KEY (applicator_id) REFERENCES applicators(applicator_id),
    FOREIGN KEY (reset_by) REFERENCES users(user_id),

    INDEX idx_applicator_id (applicator_id),
    INDEX idx_reset_time (reset_time)
);