-- Insert dummy applicator_outputs (30+ rows for applicator1)
-- Note: Following the rule for null fields based on applicator type

-- Use the machine_and_applicator database
USE machine_and_applicator;

-- Insert dummy users (30+ rows)
INSERT INTO users (username, password, first_name, last_name, user_type) VALUES
('admin001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Admin', 'ADMIN'),
('toolkeeper01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria', 'Santos', 'TOOLKEEPER'),
('toolkeeper02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Roberto', 'Cruz', 'TOOLKEEPER'),
('operator001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'Garcia', 'DEFAULT'),
('operator002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Mendez', 'DEFAULT'),
('operator003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Elena', 'Rodriguez', 'DEFAULT'),
('operator004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Miguel', 'Torres', 'DEFAULT'),
('operator005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sofia', 'Flores', 'DEFAULT'),
('operator006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Diego', 'Vargas', 'DEFAULT'),
('operator007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carmen', 'Jimenez', 'DEFAULT'),
('operator008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luis', 'Morales', 'DEFAULT'),
('operator009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Patricia', 'Herrera', 'DEFAULT'),
('operator010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fernando', 'Castillo', 'DEFAULT'),
('operator011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Isabella', 'Ruiz', 'DEFAULT'),
('operator012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ricardo', 'Ortega', 'DEFAULT'),
('operator013', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Valeria', 'Ramos', 'DEFAULT'),
('operator014', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alejandro', 'Silva', 'DEFAULT'),
('operator015', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Natalia', 'Guerrero', 'DEFAULT'),
('operator016', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andres', 'Peña', 'DEFAULT'),
('operator017', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gabriela', 'Medina', 'DEFAULT'),
('operator018', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jorge', 'Delgado', 'DEFAULT'),
('operator019', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Camila', 'Rojas', 'DEFAULT'),
('operator020', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Oscar', 'Vega', 'DEFAULT'),
('operator021', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lucia', 'Molina', 'DEFAULT'),
('operator022', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Raul', 'Aguilar', 'DEFAULT'),
('operator023', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Diana', 'Castro', 'DEFAULT'),
('operator024', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sergio', 'Navarro', 'DEFAULT'),
('operator025', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Adriana', 'Paredes', 'DEFAULT'),
('toolkeeper03', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manuel', 'Lopez', 'TOOLKEEPER'),
('toolkeeper04', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Rosa', 'Fernandez', 'TOOLKEEPER'),
('admin002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Manager', 'ADMIN'),
('operator026', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Monica', 'Ibañez', 'DEFAULT'),
('operator027', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Javier', 'Cordova', 'DEFAULT'),
('operator028', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Angela', 'Soto', 'DEFAULT'),
('operator029', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pablo', 'Guerrero', 'DEFAULT'),
('operator030', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Beatriz', 'Sandoval', 'DEFAULT');

-- Insert dummy machines (30+ rows)
INSERT INTO machines (control_no, description, model, maker, serial_no, invoice_no, last_encoded) VALUES
('MCH-001', 'Wire Processing Machine', 'WPM-5000', 'Komax', 'KMX2023001', 'INV-2023-001', '2024-12-15 08:30:00'),
('MCH-002', 'Wire Cutting Machine', 'WCM-3000', 'Schleuniger', 'SCH2023002', 'INV-2023-002', '2024-12-16 09:15:00'),
('MCH-003', 'Strip and Cut Machine', 'SCM-4000', 'Komax', 'KMX2023003', 'INV-2023-003', '2024-12-17 10:20:00'),
('MCH-004', 'Automated Wire Processor', 'AWP-6000', 'Schleuniger', 'SCH2023004', 'INV-2023-004', '2024-12-18 11:45:00'),
('MCH-005', 'Multi-Wire Machine', 'MWM-2500', 'Komax', 'KMX2023005', 'INV-2023-005', '2024-12-19 13:10:00'),
('MCH-006', 'Heavy Duty Processor', 'HDP-7000', 'Schleuniger', 'SCH2023006', 'INV-2023-006', '2024-12-20 14:30:00'),
('MCH-007', 'Precision Wire Machine', 'PWM-3500', 'Komax', 'KMX2023007', 'INV-2023-007', '2024-12-21 15:45:00'),
('MCH-008', 'Industrial Wire Cutter', 'IWC-4500', 'Carpenter', 'CAR2023008', 'INV-2023-008', '2024-12-22 08:00:00'),
('MCH-009', 'Fast Strip Machine', 'FSM-5500', 'Komax', 'KMX2023009', 'INV-2023-009', '2024-12-23 09:30:00'),
('MCH-010', 'Wire Terminal Machine', 'WTM-6500', 'Schleuniger', 'SCH2023010', 'INV-2023-010', '2024-12-24 10:15:00'),
('MCH-011', 'Compact Wire Processor', 'CWP-2000', 'Komax', 'KMX2023011', 'INV-2023-011', '2025-01-02 11:20:00'),
('MCH-012', 'High Speed Cutter', 'HSC-3200', 'Carpenter', 'CAR2023012', 'INV-2023-012', '2025-01-03 12:45:00'),
('MCH-013', 'Universal Wire Machine', 'UWM-4800', 'Schleuniger', 'SCH2023013', 'INV-2023-013', '2025-01-04 13:30:00'),
('MCH-014', 'Advanced Strip Machine', 'ASM-5200', 'Komax', 'KMX2023014', 'INV-2023-014', '2025-01-05 14:10:00'),
('MCH-015', 'Pro Wire Processor', 'PWP-6800', 'Schleuniger', 'SCH2023015', 'INV-2023-015', '2025-01-06 15:25:00'),
('MCH-016', 'Mini Wire Machine', 'MWM-1800', 'Carpenter', 'CAR2023016', 'INV-2023-016', '2025-01-07 08:45:00'),
('MCH-017', 'Dual Purpose Machine', 'DPM-4200', 'Komax', 'KMX2023017', 'INV-2023-017', '2025-01-08 09:50:00'),
('MCH-018', 'Precision Cutter Pro', 'PCP-3800', 'Schleuniger', 'SCH2023018', 'INV-2023-018', '2025-01-09 10:35:00'),
('MCH-019', 'Industrial Stripper', 'IS-5600', 'Carpenter', 'CAR2023019', 'INV-2023-019', '2025-01-10 11:40:00'),
('MCH-020', 'Wire Master 3000', 'WM-3000', 'Komax', 'KMX2023020', 'INV-2023-020', '2025-01-11 12:55:00'),
('MCH-021', 'Flex Wire Machine', 'FWM-4600', 'Schleuniger', 'SCH2023021', 'INV-2023-021', '2025-01-12 13:20:00'),
('MCH-022', 'Speed Cut Pro', 'SCP-2800', 'Carpenter', 'CAR2023022', 'INV-2023-022', '2025-01-13 14:15:00'),
('MCH-023', 'Wire Tech Advanced', 'WTA-5400', 'Komax', 'KMX2023023', 'INV-2023-023', '2025-01-14 15:10:00'),
('MCH-024', 'Ultimate Processor', 'UP-6200', 'Schleuniger', 'SCH2023024', 'INV-2023-024', '2025-01-15 08:25:00'),
('MCH-025', 'Smart Wire System', 'SWS-3600', 'Carpenter', 'CAR2023025', 'INV-2023-025', '2025-01-16 09:40:00'),
('MCH-026', 'Turbo Cut Machine', 'TCM-4400', 'Komax', 'KMX2023026', 'INV-2023-026', '2025-01-17 10:30:00'),
('MCH-027', 'Wire Solution Pro', 'WSP-5800', 'Schleuniger', 'SCH2023027', 'INV-2023-027', '2025-01-18 11:45:00'),
('MCH-028', 'Precision Master', 'PM-3400', 'Carpenter', 'CAR2023028', 'INV-2023-028', '2025-01-19 12:30:00'),
('MCH-029', 'Advanced Wire Tech', 'AWT-4900', 'Komax', 'KMX2023029', 'INV-2023-029', '2025-01-20 13:45:00'),
('MCH-030', 'Pro Wire Solution', 'PWS-5100', 'Schleuniger', 'SCH2023030', 'INV-2023-030', '2025-01-21 14:50:00'),
('MCH-031', 'Elite Wire Machine', 'EWM-6400', 'Carpenter', 'CAR2023031', 'INV-2023-031', '2025-01-22 15:35:00'),
('MCH-032', 'Supreme Processor', 'SP-7200', 'Komax', 'KMX2023032', 'INV-2023-032', '2025-01-23 08:20:00');

-- Insert dummy applicators (30+ rows with proper null field handling)
INSERT INTO applicators (hp_no, terminal_no, description, wire, terminal_maker, applicator_maker, serial_no, invoice_no, last_encoded) VALUES
-- SIDE type applicators (shear_blade, cutter_a, cutter_b will be null)
('HP-001', 'TM-101', 'SIDE', 'BIG', 'Molex', 'TE Connectivity', 'SER-001', 'INV-APP-001', '2024-12-15 08:30:00'),
('HP-002', 'TM-102', 'SIDE', 'SMALL', 'JST', 'Amphenol', 'SER-002', 'INV-APP-002', '2024-12-16 09:15:00'),
('HP-003', 'TM-103', 'SIDE', 'BIG', 'Tyco', 'TE Connectivity', 'SER-003', 'INV-APP-003', '2024-12-17 10:20:00'),
('HP-004', 'TM-104', 'SIDE', 'SMALL', 'Molex', 'Amphenol', 'SER-004', 'INV-APP-004', '2024-12-18 11:45:00'),
('HP-005', 'TM-105', 'SIDE', 'BIG', 'JST', 'TE Connectivity', 'SER-005', 'INV-APP-005', '2024-12-19 13:10:00'),
('HP-006', 'TM-106', 'SIDE', 'SMALL', 'Tyco', 'Amphenol', 'SER-006', 'INV-APP-006', '2024-12-20 14:30:00'),
('HP-007', 'TM-107', 'SIDE', 'BIG', 'Molex', 'TE Connectivity', 'SER-007', 'INV-APP-007', '2024-12-21 15:45:00'),
('HP-008', 'TM-108', 'SIDE', 'SMALL', 'JST', 'Amphenol', 'SER-008', 'INV-APP-008', '2024-12-22 08:00:00'),
-- END type applicators (slide_cutter, cutter_holder will be null)
('HP-009', 'TM-201', 'END', 'BIG', 'Tyco', 'TE Connectivity', 'SER-009', 'INV-APP-009', '2024-12-23 09:30:00'),
('HP-010', 'TM-202', 'END', 'SMALL', 'Molex', 'Amphenol', 'SER-010', 'INV-APP-010', '2024-12-24 10:15:00'),
('HP-011', 'TM-203', 'END', 'BIG', 'JST', 'TE Connectivity', 'SER-011', 'INV-APP-011', '2025-01-02 11:20:00'),
('HP-012', 'TM-204', 'END', 'SMALL', 'Tyco', 'Amphenol', 'SER-012', 'INV-APP-012', '2025-01-03 12:45:00'),
('HP-013', 'TM-205', 'END', 'BIG', 'Molex', 'TE Connectivity', 'SER-013', 'INV-APP-013', '2025-01-04 13:30:00'),
('HP-014', 'TM-206', 'END', 'SMALL', 'JST', 'Amphenol', 'SER-014', 'INV-APP-014', '2025-01-05 14:10:00'),
('HP-015', 'TM-207', 'END', 'BIG', 'Tyco', 'TE Connectivity', 'SER-015', 'INV-APP-015', '2025-01-06 15:25:00'),
('HP-016', 'TM-208', 'END', 'SMALL', 'Molex', 'Amphenol', 'SER-016', 'INV-APP-016', '2025-01-07 08:45:00'),
-- CLAMP type applicators (slide_cutter, cutter_holder will be null)
('HP-017', 'TM-301', 'CLAMP', 'BIG', 'JST', 'TE Connectivity', 'SER-017', 'INV-APP-017', '2025-01-08 09:50:00'),
('HP-018', 'TM-302', 'CLAMP', 'SMALL', 'Tyco', 'Amphenol', 'SER-018', 'INV-APP-018', '2025-01-09 10:35:00'),
('HP-019', 'TM-303', 'CLAMP', 'BIG', 'Molex', 'TE Connectivity', 'SER-019', 'INV-APP-019', '2025-01-10 11:40:00'),
('HP-020', 'TM-304', 'CLAMP', 'SMALL', 'JST', 'Amphenol', 'SER-020', 'INV-APP-020', '2025-01-11 12:55:00'),
('HP-021', 'TM-305', 'CLAMP', 'BIG', 'Tyco', 'TE Connectivity', 'SER-021', 'INV-APP-021', '2025-01-12 13:20:00'),
('HP-022', 'TM-306', 'CLAMP', 'SMALL', 'Molex', 'Amphenol', 'SER-022', 'INV-APP-022', '2025-01-13 14:15:00'),
-- STRIP AND CLAMP type applicators (slide_cutter, cutter_holder will be null)
('HP-023', 'TM-401', 'STRIP AND CLAMP', 'BIG', 'JST', 'TE Connectivity', 'SER-023', 'INV-APP-023', '2025-01-14 15:10:00'),
('HP-024', 'TM-402', 'STRIP AND CLAMP', 'SMALL', 'Tyco', 'Amphenol', 'SER-024', 'INV-APP-024', '2025-01-15 08:25:00'),
('HP-025', 'TM-403', 'STRIP AND CLAMP', 'BIG', 'Molex', 'TE Connectivity', 'SER-025', 'INV-APP-025', '2025-01-16 09:40:00'),
('HP-026', 'TM-404', 'STRIP AND CLAMP', 'SMALL', 'JST', 'Amphenol', 'SER-026', 'INV-APP-026', '2025-01-17 10:30:00'),
('HP-027', 'TM-405', 'STRIP AND CLAMP', 'BIG', 'Tyco', 'TE Connectivity', 'SER-027', 'INV-APP-027', '2025-01-18 11:45:00'),
('HP-028', 'TM-406', 'STRIP AND CLAMP', 'SMALL', 'Molex', 'Amphenol', 'SER-028', 'INV-APP-028', '2025-01-19 12:30:00'),
-- Additional mixed types to reach 30+
('HP-029', 'TM-109', 'SIDE', 'BIG', 'JST', 'TE Connectivity', 'SER-029', 'INV-APP-029', '2025-01-20 13:45:00'),
('HP-030', 'TM-209', 'END', 'SMALL', 'Tyco', 'Amphenol', 'SER-030', 'INV-APP-030', '2025-01-21 14:50:00'),
('HP-031', 'TM-110', 'SIDE', 'SMALL', 'Molex', 'TE Connectivity', 'SER-031', 'INV-APP-031', '2025-01-22 15:35:00'),
('HP-032', 'TM-307', 'CLAMP', 'BIG', 'JST', 'Amphenol', 'SER-032', 'INV-APP-032', '2025-01-23 08:20:00'),
('HP-033', 'TM-407', 'STRIP AND CLAMP', 'SMALL', 'Tyco', 'TE Connectivity', 'SER-033', 'INV-APP-033', '2025-01-24 09:25:00');

-- Insert dummy records (30+ rows)
-- Using subqueries to get the actual machine_id and applicator_id values
INSERT INTO records (shift, machine_id, applicator1_id, applicator2_id, created_by, date_inspected, date_encoded, is_active) VALUES
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-001'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-001'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-009'), 4, '2024-12-15', '2024-12-15 16:30:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-002'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-002'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-010'), 5, '2024-12-16', '2024-12-16 17:15:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-003'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-003'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-011'), 6, '2024-12-17', '2024-12-17 23:20:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-004'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-004'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-012'), 7, '2024-12-18', '2024-12-18 16:45:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-005'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-005'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-013'), 8, '2024-12-19', '2024-12-19 17:10:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-006'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-006'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-014'), 9, '2024-12-20', '2024-12-20 23:30:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-007'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-007'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-015'), 10, '2024-12-21', '2024-12-21 16:45:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-008'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-008'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-016'), 11, '2024-12-22', '2024-12-22 17:00:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-009'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-009'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-017'), 12, '2024-12-23', '2024-12-23 23:30:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-010'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-010'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-018'), 13, '2024-12-24', '2024-12-24 16:15:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-011'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-011'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-019'), 14, '2025-01-02', '2025-01-02 17:20:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-012'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-012'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-020'), 15, '2025-01-03', '2025-01-03 23:45:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-013'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-013'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-021'), 16, '2025-01-04', '2025-01-04 16:30:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-014'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-014'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-022'), 17, '2025-01-05', '2025-01-05 17:10:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-015'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-015'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-023'), 18, '2025-01-06', '2025-01-06 23:25:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-016'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-016'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-024'), 19, '2025-01-07', '2025-01-07 16:45:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-017'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-017'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-025'), 20, '2025-01-08', '2025-01-08 17:50:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-018'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-018'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-026'), 21, '2025-01-09', '2025-01-09 23:35:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-019'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-019'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-027'), 22, '2025-01-10', '2025-01-10 16:40:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-020'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-020'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-028'), 23, '2025-01-11', '2025-01-11 17:55:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-021'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-021'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-029'), 24, '2025-01-12', '2025-01-12 23:20:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-022'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-022'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-030'), 25, '2025-01-13', '2025-01-13 16:15:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-023'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-023'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-031'), 26, '2025-01-14', '2025-01-14 17:10:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-024'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-024'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-032'), 27, '2025-01-15', '2025-01-15 23:25:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-025'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-025'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-033'), 28, '2025-01-16', '2025-01-16 16:40:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-026'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-026'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-001'), 29, '2025-01-17', '2025-01-17 17:30:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-027'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-027'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-002'), 30, '2025-01-18', '2025-01-18 23:45:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-028'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-028'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-003'), 31, '2025-01-19', '2025-01-19 16:30:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-029'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-029'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-004'), 32, '2025-01-20', '2025-01-20 17:45:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-030'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-030'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-005'), 33, '2025-01-21', '2025-01-21 23:50:00', TRUE),
('1st', (SELECT machine_id FROM machines WHERE control_no = 'MCH-031'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-031'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-006'), 34, '2025-01-22', '2025-01-22 16:35:00', TRUE),
('2nd', (SELECT machine_id FROM machines WHERE control_no = 'MCH-032'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-032'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-007'), 35, '2025-01-23', '2025-01-23 17:20:00', TRUE),
('NIGHT', (SELECT machine_id FROM machines WHERE control_no = 'MCH-001'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-033'), (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-008'), 36, '2025-01-24', '2025-01-24 23:25:00', TRUE);

-- Insert dummy machine_outputs (30+ rows)
-- Using record_id from 1 to 33 (sequential record IDs)
INSERT INTO machine_outputs (record_id, machine_id, is_active, total_machine_output, cut_blade, strip_blade_a, strip_blade_b) VALUES
(1, (SELECT machine_id FROM machines WHERE control_no = 'MCH-001'), TRUE, 15000, 12500, 1250, 1250),
(2, (SELECT machine_id FROM machines WHERE control_no = 'MCH-002'), TRUE, 18500, 15200, 1650, 1650),
(3, (SELECT machine_id FROM machines WHERE control_no = 'MCH-003'), TRUE, 14200, 11800, 1200, 1200),
(4, (SELECT machine_id FROM machines WHERE control_no = 'MCH-004'), TRUE, 16800, 14000, 1400, 1400),
(5, (SELECT machine_id FROM machines WHERE control_no = 'MCH-005'), TRUE, 17500, 14600, 1450, 1450),
(6, (SELECT machine_id FROM machines WHERE control_no = 'MCH-006'), TRUE, 19200, 16000, 1600, 1600),
(7, (SELECT machine_id FROM machines WHERE control_no = 'MCH-007'), TRUE, 13800, 11500, 1150, 1150),
(8, (SELECT machine_id FROM machines WHERE control_no = 'MCH-008'), TRUE, 16200, 13500, 1350, 1350),
(9, (SELECT machine_id FROM machines WHERE control_no = 'MCH-009'), TRUE, 17800, 14800, 1500, 1500),
(10, (SELECT machine_id FROM machines WHERE control_no = 'MCH-010'), TRUE, 15600, 13000, 1300, 1300),
(11, (SELECT machine_id FROM machines WHERE control_no = 'MCH-011'), TRUE, 18200, 15200, 1500, 1500),
(12, (SELECT machine_id FROM machines WHERE control_no = 'MCH-012'), TRUE, 14800, 12300, 1250, 1250),
(13, (SELECT machine_id FROM machines WHERE control_no = 'MCH-013'), TRUE, 16500, 13800, 1350, 1350),
(14, (SELECT machine_id FROM machines WHERE control_no = 'MCH-014'), TRUE, 17200, 14300, 1450, 1450),
(15, (SELECT machine_id FROM machines WHERE control_no = 'MCH-015'), TRUE, 19500, 16200, 1650, 1650),
(16, (SELECT machine_id FROM machines WHERE control_no = 'MCH-016'), TRUE, 13500, 11200, 1150, 1150),
(17, (SELECT machine_id FROM machines WHERE control_no = 'MCH-017'), TRUE, 16800, 14000, 1400, 1400),
(18, (SELECT machine_id FROM machines WHERE control_no = 'MCH-018'), TRUE, 18200, 15200, 1500, 1500),
(19, (SELECT machine_id FROM machines WHERE control_no = 'MCH-019'), TRUE, 15200, 12700, 1250, 1250),
(20, (SELECT machine_id FROM machines WHERE control_no = 'MCH-020'), TRUE, 17500, 14600, 1450, 1450),
(21, (SELECT machine_id FROM machines WHERE control_no = 'MCH-021'), TRUE, 18800, 15600, 1600, 1600),
(22, (SELECT machine_id FROM machines WHERE control_no = 'MCH-022'), TRUE, 14500, 12100, 1200, 1200),
(23, (SELECT machine_id FROM machines WHERE control_no = 'MCH-023'), TRUE, 16200, 13500, 1350, 1350),
(24, (SELECT machine_id FROM machines WHERE control_no = 'MCH-024'), TRUE, 17800, 14800, 1500, 1500),
(25, (SELECT machine_id FROM machines WHERE control_no = 'MCH-025'), TRUE, 19200, 16000, 1600, 1600),
(26, (SELECT machine_id FROM machines WHERE control_no = 'MCH-026'), TRUE, 13800, 11500, 1150, 1150),
(27, (SELECT machine_id FROM machines WHERE control_no = 'MCH-027'), TRUE, 16500, 13800, 1350, 1350),
(28, (SELECT machine_id FROM machines WHERE control_no = 'MCH-028'), TRUE, 18500, 15400, 1550, 1550),
(29, (SELECT machine_id FROM machines WHERE control_no = 'MCH-029'), TRUE, 15800, 13200, 1300, 1300),
(30, (SELECT machine_id FROM machines WHERE control_no = 'MCH-030'), TRUE, 17200, 14300, 1450, 1450),
(31, (SELECT machine_id FROM machines WHERE control_no = 'MCH-031'), TRUE, 18800, 15600, 1600, 1600),
(32, (SELECT machine_id FROM machines WHERE control_no = 'MCH-032'), TRUE, 14200, 11800, 1200, 1200),
(33, (SELECT machine_id FROM machines WHERE control_no = 'MCH-001'), TRUE, 16800, 14000, 1400, 1400);

-- Insert dummy applicator_outputs (30+ rows for applicator1)
-- Note: Following the rule for null fields based on applicator type
INSERT INTO applicator_outputs (record_id, applicator_id, is_active, total_output, wire_crimper, wire_anvil, insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, shear_blade, cutter_a, cutter_b) VALUES
-- SIDE type applicators (applicator_id 1-8, 29, 31) - shear_blade, cutter_a, cutter_b are NULL
(1, 1, TRUE, 7500, 6200, 650, 500, 150, 45, 25, NULL, NULL, NULL),
(2, 2, TRUE, 9250, 7600, 825, 650, 175, 52, 30, NULL, NULL, NULL),
(3, 3, TRUE, 7100, 5900, 600, 450, 150, 38, 22, NULL, NULL, NULL),
(4, 4, TRUE, 8400, 6800, 700, 550, 200, 45, 25, NULL, NULL, NULL),
(5, 5, TRUE, 8750, 7300, 725, 575, 150, 48, 28, NULL, NULL, NULL),
(6, 6, TRUE, 9600, 8000, 800, 650, 150, 55, 32, NULL, NULL, NULL),
(7, 7, TRUE, 6900, 5750, 575, 425, 150, 42, 24, NULL, NULL, NULL),
(8, 8, TRUE, 8100, 6750, 675, 525, 150, 46, 26, NULL, NULL, NULL),
(26, 29, TRUE, 6900, 5750, 575, 425, 150, 38, 22, NULL, NULL, NULL),
(28, 31, TRUE, 9250, 7700, 770, 630, 150, 52, 30, NULL, NULL, NULL),

-- END type applicators (applicator_id 9-16, 30) - slide_cutter, cutter_holder are NULL
(9, 9, TRUE, 8900, 7400, 750, 600, 150, NULL, NULL, 85, 42, 43),
(10, 10, TRUE, 7800, 6500, 650, 500, 150, NULL, NULL, 78, 38, 40),
(11, 11, TRUE, 9100, 7600, 760, 590, 150, NULL, NULL, 88, 44, 44),
(12, 12, TRUE, 7400, 6200, 620, 430, 150, NULL, NULL, 72, 35, 37),
(13, 13, TRUE, 8250, 6900, 690, 510, 150, NULL, NULL, 80, 40, 40),
(14, 14, TRUE, 8600, 7200, 720, 530, 150, NULL, NULL, 82, 41, 41),
(15, 15, TRUE, 9750, 8100, 810, 690, 150, NULL, NULL, 92, 46, 46),
(16, 16, TRUE, 6750, 5600, 560, 440, 150, NULL, NULL, 68, 33, 35),
(27, 30, TRUE, 7600, 6300, 630, 520, 150, NULL, NULL, 75, 37, 38),

-- CLAMP type applicators (applicator_id 17-22, 32) - slide_cutter, cutter_holder are NULL
(17, 17, TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39),
(18, 18, TRUE, 9100, 7600, 760, 590, 150, NULL, NULL, 85, 42, 43),
(19, 19, TRUE, 7600, 6300, 630, 520, 150, NULL, NULL, 72, 36, 36),
(20, 20, TRUE, 8750, 7300, 730, 570, 150, NULL, NULL, 82, 41, 41),
(21, 21, TRUE, 9400, 7800, 780, 670, 150, NULL, NULL, 88, 44, 44),
(22, 22, TRUE, 7250, 6000, 600, 500, 150, NULL, NULL, 68, 34, 34),
(32, 32, TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39),

-- STRIP AND CLAMP type applicators (applicator_id 23-28, 33) - slide_cutter, cutter_holder are NULL
(23, 23, TRUE, 8100, 6750, 675, 525, 150, NULL, NULL, 75, 37, 38),
(24, 24, TRUE, 8900, 7400, 740, 610, 150, NULL, NULL, 82, 41, 41),
(25, 25, TRUE, 9600, 8000, 800, 650, 150, NULL, NULL, 88, 44, 44),
(26, 26, TRUE, 7900, 6600, 660, 490, 150, NULL, NULL, 72, 36, 36),
(27, 27, TRUE, 8250, 6900, 690, 510, 150, NULL, NULL, 78, 39, 39),
(28, 28, TRUE, 9250, 7700, 770, 630, 150, NULL, NULL, 85, 42, 43),
(33, 33, TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39);

-- Corrected applicator_outputs insertions that properly match the records table relationships

-- First, let's insert applicator_outputs for applicator1 (referenced in records.applicator1_id)
INSERT INTO applicator_outputs (record_id, applicator_id, is_active, total_output, wire_crimper, wire_anvil, insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, shear_blade, cutter_a, cutter_b) VALUES
-- Record 1: MCH-001 with HP-001 (applicator1) and HP-009 (applicator2)
-- HP-001 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(1, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-001'), TRUE, 7500, 6200, 650, 500, 150, 45, 25, NULL, NULL, NULL),

-- Record 2: MCH-002 with HP-002 (applicator1) and HP-010 (applicator2)  
-- HP-002 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(2, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-002'), TRUE, 9250, 7600, 825, 650, 175, 52, 30, NULL, NULL, NULL),

-- Record 3: MCH-003 with HP-003 (applicator1) and HP-011 (applicator2)
-- HP-003 is SIDE type - shear_blade, cutter_a, cutter_b are NULL  
(3, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-003'), TRUE, 7100, 5900, 600, 450, 150, 38, 22, NULL, NULL, NULL),

-- Record 4: MCH-004 with HP-004 (applicator1) and HP-012 (applicator2)
-- HP-004 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(4, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-004'), TRUE, 8400, 6800, 700, 550, 200, 45, 25, NULL, NULL, NULL),

-- Record 5: MCH-005 with HP-005 (applicator1) and HP-013 (applicator2)
-- HP-005 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(5, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-005'), TRUE, 8750, 7300, 725, 575, 150, 48, 28, NULL, NULL, NULL),

-- Record 6: MCH-006 with HP-006 (applicator1) and HP-014 (applicator2)
-- HP-006 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(6, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-006'), TRUE, 9600, 8000, 800, 650, 150, 55, 32, NULL, NULL, NULL),

-- Record 7: MCH-007 with HP-007 (applicator1) and HP-015 (applicator2)
-- HP-007 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(7, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-007'), TRUE, 6900, 5750, 575, 425, 150, 42, 24, NULL, NULL, NULL),

-- Record 8: MCH-008 with HP-008 (applicator1) and HP-016 (applicator2)
-- HP-008 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(8, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-008'), TRUE, 8100, 6750, 675, 525, 150, 46, 26, NULL, NULL, NULL),

-- Record 9: MCH-009 with HP-009 (applicator1) and HP-017 (applicator2)
-- HP-009 is END type - slide_cutter, cutter_holder are NULL
(9, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-009'), TRUE, 8900, 7400, 750, 600, 150, NULL, NULL, 85, 42, 43),

-- Record 10: MCH-010 with HP-010 (applicator1) and HP-018 (applicator2)
-- HP-010 is END type - slide_cutter, cutter_holder are NULL
(10, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-010'), TRUE, 7800, 6500, 650, 500, 150, NULL, NULL, 78, 38, 40),

-- Record 11: MCH-011 with HP-011 (applicator1) and HP-019 (applicator2)
-- HP-011 is END type - slide_cutter, cutter_holder are NULL
(11, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-011'), TRUE, 9100, 7600, 760, 590, 150, NULL, NULL, 88, 44, 44),

-- Record 12: MCH-012 with HP-012 (applicator1) and HP-020 (applicator2)
-- HP-012 is END type - slide_cutter, cutter_holder are NULL
(12, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-012'), TRUE, 7400, 6200, 620, 430, 150, NULL, NULL, 72, 35, 37),

-- Record 13: MCH-013 with HP-013 (applicator1) and HP-021 (applicator2)
-- HP-013 is END type - slide_cutter, cutter_holder are NULL
(13, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-013'), TRUE, 8250, 6900, 690, 510, 150, NULL, NULL, 80, 40, 40),

-- Record 14: MCH-014 with HP-014 (applicator1) and HP-022 (applicator2)
-- HP-014 is END type - slide_cutter, cutter_holder are NULL
(14, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-014'), TRUE, 8600, 7200, 720, 530, 150, NULL, NULL, 82, 41, 41),

-- Record 15: MCH-015 with HP-015 (applicator1) and HP-023 (applicator2)
-- HP-015 is END type - slide_cutter, cutter_holder are NULL
(15, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-015'), TRUE, 9750, 8100, 810, 690, 150, NULL, NULL, 92, 46, 46),

-- Record 16: MCH-016 with HP-016 (applicator1) and HP-024 (applicator2)
-- HP-016 is END type - slide_cutter, cutter_holder are NULL
(16, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-016'), TRUE, 6750, 5600, 560, 440, 150, NULL, NULL, 68, 33, 35),

-- Record 17: MCH-017 with HP-017 (applicator1) and HP-025 (applicator2)
-- HP-017 is CLAMP type - slide_cutter, cutter_holder are NULL
(17, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-017'), TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39),

-- Record 18: MCH-018 with HP-018 (applicator1) and HP-026 (applicator2)
-- HP-018 is CLAMP type - slide_cutter, cutter_holder are NULL
(18, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-018'), TRUE, 9100, 7600, 760, 590, 150, NULL, NULL, 85, 42, 43),

-- Record 19: MCH-019 with HP-019 (applicator1) and HP-027 (applicator2)
-- HP-019 is CLAMP type - slide_cutter, cutter_holder are NULL
(19, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-019'), TRUE, 7600, 6300, 630, 520, 150, NULL, NULL, 72, 36, 36),

-- Record 20: MCH-020 with HP-020 (applicator1) and HP-028 (applicator2)
-- HP-020 is CLAMP type - slide_cutter, cutter_holder are NULL
(20, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-020'), TRUE, 8750, 7300, 730, 570, 150, NULL, NULL, 82, 41, 41),

-- Record 21: MCH-021 with HP-021 (applicator1) and HP-029 (applicator2)
-- HP-021 is CLAMP type - slide_cutter, cutter_holder are NULL
(21, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-021'), TRUE, 9400, 7800, 780, 670, 150, NULL, NULL, 88, 44, 44),

-- Record 22: MCH-022 with HP-022 (applicator1) and HP-030 (applicator2)
-- HP-022 is CLAMP type - slide_cutter, cutter_holder are NULL
(22, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-022'), TRUE, 7250, 6000, 600, 500, 150, NULL, NULL, 68, 34, 34),

-- Record 23: MCH-023 with HP-023 (applicator1) and HP-031 (applicator2)
-- HP-023 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(23, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-023'), TRUE, 8100, 6750, 675, 525, 150, NULL, NULL, 75, 37, 38),

-- Record 24: MCH-024 with HP-024 (applicator1) and HP-032 (applicator2)
-- HP-024 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(24, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-024'), TRUE, 8900, 7400, 740, 610, 150, NULL, NULL, 82, 41, 41),

-- Record 25: MCH-025 with HP-025 (applicator1) and HP-033 (applicator2)
-- HP-025 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(25, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-025'), TRUE, 9600, 8000, 800, 650, 150, NULL, NULL, 88, 44, 44),

-- Record 26: MCH-026 with HP-026 (applicator1) and HP-001 (applicator2)
-- HP-026 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(26, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-026'), TRUE, 7900, 6600, 660, 490, 150, NULL, NULL, 72, 36, 36),

-- Record 27: MCH-027 with HP-027 (applicator1) and HP-002 (applicator2)
-- HP-027 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(27, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-027'), TRUE, 8250, 6900, 690, 510, 150, NULL, NULL, 78, 39, 39),

-- Record 28: MCH-028 with HP-028 (applicator1) and HP-003 (applicator2)
-- HP-028 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(28, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-028'), TRUE, 9250, 7700, 770, 630, 150, NULL, NULL, 85, 42, 43),

-- Record 29: MCH-029 with HP-029 (applicator1) and HP-004 (applicator2)
-- HP-029 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(29, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-029'), TRUE, 6900, 5750, 575, 425, 150, 38, 22, NULL, NULL, NULL),

-- Record 30: MCH-030 with HP-030 (applicator1) and HP-005 (applicator2)
-- HP-030 is END type - slide_cutter, cutter_holder are NULL
(30, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-030'), TRUE, 7600, 6300, 630, 520, 150, NULL, NULL, 75, 37, 38),

-- Record 31: MCH-031 with HP-031 (applicator1) and HP-006 (applicator2)
-- HP-031 is SIDE type - shear_blade, cutter_a, cutter_b are NULL
(31, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-031'), TRUE, 9250, 7700, 770, 630, 150, 52, 30, NULL, NULL, NULL),

-- Record 32: MCH-032 with HP-032 (applicator1) and HP-007 (applicator2)
-- HP-032 is CLAMP type - slide_cutter, cutter_holder are NULL
(32, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-032'), TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39),

-- Record 33: MCH-001 with HP-033 (applicator1) and HP-008 (applicator2)
-- HP-033 is STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(33, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-033'), TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39);

-- Now insert applicator_outputs for applicator2 (referenced in records.applicator2_id)
INSERT INTO applicator_outputs (record_id, applicator_id, is_active, total_output, wire_crimper, wire_anvil, insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, shear_blade, cutter_a, cutter_b) VALUES
-- Record 1: HP-009 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(1, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-009'), TRUE, 7500, 6200, 620, 530, 150, NULL, NULL, 72, 36, 36),

-- Record 2: HP-010 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(2, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-010'), TRUE, 9250, 7700, 770, 630, 150, NULL, NULL, 88, 44, 44),

-- Record 3: HP-011 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(3, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-011'), TRUE, 7100, 5900, 590, 460, 150, NULL, NULL, 68, 34, 34),

-- Record 4: HP-012 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(4, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-012'), TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39),

-- Record 5: HP-013 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(5, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-013'), TRUE, 8750, 7300, 730, 570, 150, NULL, NULL, 82, 41, 41),

-- Record 6: HP-014 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(6, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-014'), TRUE, 9600, 8000, 800, 650, 150, NULL, NULL, 92, 46, 46),

-- Record 7: HP-015 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(7, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-015'), TRUE, 6900, 5750, 575, 425, 150, NULL, NULL, 65, 32, 33),

-- Record 8: HP-016 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(8, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-016'), TRUE, 8100, 6750, 675, 525, 150, NULL, NULL, 75, 37, 38),

-- Record 9: HP-017 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(9, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-017'), TRUE, 8900, 7400, 740, 610, 150, NULL, NULL, 82, 41, 41),

-- Record 10: HP-018 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(10, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-018'), TRUE, 7800, 6500, 650, 500, 150, NULL, NULL, 72, 36, 36),

-- Record 11: HP-019 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(11, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-019'), TRUE, 9100, 7600, 760, 590, 150, NULL, NULL, 85, 42, 43),

-- Record 12: HP-020 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(12, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-020'), TRUE, 7400, 6200, 620, 430, 150, NULL, NULL, 68, 34, 34),

-- Record 13: HP-021 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(13, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-021'), TRUE, 8250, 6900, 690, 510, 150, NULL, NULL, 78, 39, 39),

-- Record 14: HP-022 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(14, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-022'), TRUE, 8600, 7200, 720, 530, 150, NULL, NULL, 80, 40, 40),

-- Record 15: HP-023 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(15, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-023'), TRUE, 9750, 8100, 810, 690, 150, NULL, NULL, 92, 46, 46),

-- Record 16: HP-024 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(16, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-024'), TRUE, 6750, 5600, 560, 440, 150, NULL, NULL, 65, 32, 33),

-- Record 17: HP-025 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(17, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-025'), TRUE, 8400, 7000, 700, 550, 150, NULL, NULL, 78, 39, 39),

-- Record 18: HP-026 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(18, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-026'), TRUE, 9100, 7600, 760, 590, 150, NULL, NULL, 85, 42, 43),

-- Record 19: HP-027 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(19, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-027'), TRUE, 7600, 6300, 630, 520, 150, NULL, NULL, 72, 36, 36),

-- Record 20: HP-028 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(20, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-028'), TRUE, 8750, 7300, 730, 570, 150, NULL, NULL, 82, 41, 41),

-- Record 21: HP-029 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(21, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-029'), TRUE, 9400, 7800, 780, 670, 150, 45, 25, NULL, NULL, NULL),

-- Record 22: HP-030 (applicator2) - END type - slide_cutter, cutter_holder are NULL
(22, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-030'), TRUE, 7250, 6000, 600, 500, 150, NULL, NULL, 68, 34, 34),

-- Record 23: HP-031 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(23, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-031'), TRUE, 8100, 6750, 675, 525, 150, 38, 22, NULL, NULL, NULL),

-- Record 24: HP-032 (applicator2) - CLAMP type - slide_cutter, cutter_holder are NULL
(24, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-032'), TRUE, 8900, 7400, 740, 610, 150, NULL, NULL, 82, 41, 41),

-- Record 25: HP-033 (applicator2) - STRIP AND CLAMP type - slide_cutter, cutter_holder are NULL
(25, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-033'), TRUE, 9600, 8000, 800, 650, 150, NULL, NULL, 88, 44, 44),

-- Record 26: HP-001 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(26, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-001'), TRUE, 7900, 6600, 660, 490, 150, 42, 24, NULL, NULL, NULL),

-- Record 27: HP-002 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(27, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-002'), TRUE, 8250, 6900, 690, 510, 150, 45, 25, NULL, NULL, NULL),

-- Record 28: HP-003 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(28, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-003'), TRUE, 9250, 7700, 770, 630, 150, 48, 28, NULL, NULL, NULL),

-- Record 29: HP-004 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(29, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-004'), TRUE, 7800, 6500, 650, 500, 150, 40, 23, NULL, NULL, NULL),

-- Record 30: HP-005 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(30, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-005'), TRUE, 8600, 7200, 720, 530, 150, 46, 26, NULL, NULL, NULL),

-- Record 31: HP-006 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(31, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-006'), TRUE, 9400, 7800, 780, 670, 150, 52, 30, NULL, NULL, NULL),

-- Record 32: HP-007 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(32, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-007'), TRUE, 7200, 6000, 600, 450, 150, 38, 22, NULL, NULL, NULL),

-- Record 33: HP-008 (applicator2) - SIDE type - shear_blade, cutter_a, cutter_b are NULL
(33, (SELECT applicator_id FROM applicators WHERE hp_no = 'HP-008'), TRUE, 8800, 7300, 730, 620, 150, 48, 28, NULL, NULL, NULL);