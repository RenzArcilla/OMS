# Machine and Applicator Database Schema Documentation

## Overview

This database system is designed to track machine and applicator usage in a manufacturing environment. It uses a **hybrid approach**: direct columns for standard parts (fast performance) and JSON columns for custom parts (flexibility), providing the best of both worlds.

## Database: `machine_and_applicator`

---

## Core Tables

### 1. `users`
**Purpose**: Stores user information and access levels for the system.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `user_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for each user |
| `username` | VARCHAR(50) | UNIQUE, NOT NULL | Login username |
| `password` | VARCHAR(255) | NOT NULL | Encrypted password |
| `first_name` | VARCHAR(50) | NOT NULL | User's first name |
| `last_name` | VARCHAR(50) | NOT NULL | User's last name |
| `user_type` | ENUM | DEFAULT 'DEFAULT' | Access level: 'DEFAULT', 'TOOLKEEPER', 'ADMIN' |

**Indexes**: `idx_username`

**User Types**:
- **DEFAULT**: Basic user with read acces.
- **TOOLKEEPER**: Can perform resets and maintenance operations
- **ADMIN**: Full system access including custom part management

---

### 2. `machines`
**Purpose**: Stores information about manufacturing machines.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `machine_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for each machine |
| `control_no` | VARCHAR(50) | UNIQUE, NOT NULL | Machine control number |
| `description` | VARCHAR(50) | NOT NULL | Machine description |
| `model` | VARCHAR(50) | NOT NULL | Machine model |
| `maker` | VARCHAR(50) | NOT NULL | Manufacturer name |
| `serial_no` | VARCHAR(50) | DEFAULT NULL | Serial number (optional) |
| `invoice_no` | VARCHAR(50) | DEFAULT NULL | Invoice number (optional) |
| `last_encoded` | DATETIME | DEFAULT NULL | Last time data was entered for this machine |

**Indexes**: `idx_control_no` `idx_maker`, `idx_last_encoded`

---

### 3. `applicators`
**Purpose**: Stores information about applicators used with machines.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `applicator_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for each applicator |
| `hp_no` | VARCHAR(50) | UNIQUE, NOT NULL | HP number |
| `terminal_no` | VARCHAR(50) | NOT NULL | Terminal number |
| `description` | ENUM | NOT NULL | Type: 'SIDE', 'END', 'CLAMP', 'STRIP AND CLAMP' |
| `wire` | ENUM | NOT NULL | Wire size: 'BIG', 'SMALL' |
| `terminal_maker` | VARCHAR(50) | NOT NULL | Terminal manufacturer |
| `applicator_maker` | VARCHAR(50) | NOT NULL | Applicator manufacturer |
| `serial_no` | VARCHAR(50) | DEFAULT NULL | Serial number (optional) |
| `invoice_no` | VARCHAR(50) | DEFAULT NULL | Invoice number (optional) |
| `last_encoded` | DATETIME | DEFAULT NULL | Last time data was entered for this applicator |

**Indexes**: `idx_hp_no`, `idx_terminal_no`, `idx_terminal_maker`, `idx_last_encoded`

---

## Custom Part Management System

### 4. `custom_part_definitions`
**Purpose**: Lightweight lookup table for admin-defined custom parts.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `part_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for custom part |
| `equipment_type` | ENUM | NOT NULL | 'MACHINE' or 'APPLICATOR' |
| `part_name` | VARCHAR(100) | NOT NULL | Human-readable part name |
| `is_active` | BOOLEAN | DEFAULT TRUE | Whether this part is currently available |
| `created_by` | INT | NOT NULL, FK → users | Admin user who created this part |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | When this part was created |

**Indexes**: `idx_equipment_type`, `idx_is_active`
**Unique Constraint**: `unique_equipment_part` (prevents duplicate part names per equipment type)

---

## Production Recording

### 5. `records`
**Purpose**: Main table for production inspection records.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `record_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for each record |
| `shift` | ENUM | NOT NULL | Work shift: '1st', '2nd', 'NIGHT' |
| `machine_id` | INT | NOT NULL, FK → machines | Machine used |
| `applicator1_id` | INT | NOT NULL, FK → applicators | Primary applicator used |
| `applicator2_id` | INT | FK → applicators | Secondary applicator (optional) |
| `created_by` | INT | NOT NULL, FK → users | User who created the record |
| `date_inspected` | DATE | NOT NULL | Date of inspection |
| `date_encoded` | DATETIME | DEFAULT CURRENT_TIMESTAMP | When record was entered into system |
| `is_active` | BOOLEAN | DEFAULT TRUE | Soft delete flag |

**Indexes**: `idx_machine_id`, `idx_created_by`, `idx_date_encoded`, `idx_is_active`, `idx_applicator1_id`, `idx_applicator2_id`

---

### 6. `machine_outputs` (Hybrid Structure)
**Purpose**: Records output counts for machine parts per inspection record.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `machine_output_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `record_id` | INT | NOT NULL, FK → records | Associated inspection record |
| `machine_id` | INT | NOT NULL, FK → machines | Machine that produced output |
| `is_active` | BOOLEAN | DEFAULT TRUE | Soft delete flag |
| **Standard Parts** | | | **Direct columns for performance** |
| `total_machine_output` | INT | NOT NULL | Total machine output count |
| `cut_blade` | INT | NOT NULL | Cut blade output count |
| `strip_blade_a` | INT | NOT NULL | Strip blade A output count |
| `strip_blade_b` | INT | NOT NULL | Strip blade B output count |
| **Custom Parts** | | | **JSON for flexibility** |
| `custom_parts` | JSON | DEFAULT NULL | Custom part outputs as key-value pairs |

**Indexes**: `idx_record_id`, `idx_machine_id`, `idx_is_active`

**Custom Parts JSON Structure**:
```json
{
  "precision_cutter": 125,
  "special_blade": 75,
  "custom_tool_x": 200
}
```

### 7. `applicator_outputs` (Hybrid Structure)
**Purpose**: Records output counts for applicator parts per inspection record.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `applicator_output_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `record_id` | INT | NOT NULL, FK → records | Associated inspection record |
| `applicator_id` | INT | NOT NULL, FK → applicators | Applicator that produced output |
| `is_active` | BOOLEAN | DEFAULT TRUE | Soft delete flag |
| **Standard Parts** | | | **Direct columns for performance** |
| `total_output` | INT | NOT NULL | Total applicator output |
| `wire_crimper` | INT | NOT NULL | Wire crimper output |
| `wire_anvil` | INT | NOT NULL | Wire anvil output |
| `insulation_crimper` | INT | NOT NULL | Insulation crimper output |
| `insulation_anvil` | INT | NOT NULL | Insulation anvil output |
| `slide_cutter` | INT | DEFAULT NULL | Slide cutter output (optional) |
| `cutter_holder` | INT | DEFAULT NULL | Cutter holder output (optional) |
| `shear_blade` | INT | DEFAULT NULL | Shear blade output (optional) |
| `cutter_a` | INT | DEFAULT NULL | Cutter A output (optional) |
| `cutter_b` | INT | DEFAULT NULL | Cutter B output (optional) |
| **Custom Parts** | | | **JSON for flexibility** |
| `custom_parts` | JSON | DEFAULT NULL | Custom part outputs as key-value pairs |

**Indexes**: `idx_record_id`, `idx_applicator_id`, `idx_is_active`

---

## Monitoring System

### 8. `monitor_machine` (Hybrid Structure)
**Purpose**: Tracks cumulative output counts for machine parts over time.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `monitor_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `machine_id` | INT | NOT NULL, UNIQUE, FK → machines | Machine being monitored |
| **Standard Parts Totals** | | | |
| `total_machine_output` | INT | DEFAULT 0 | Cumulative total output |
| `cut_blade_output` | INT | DEFAULT 0 | Cumulative cut blade output |
| `strip_blade_a_output` | INT | DEFAULT 0 | Cumulative strip blade A output |
| `strip_blade_b_output` | INT | DEFAULT 0 | Cumulative strip blade B output |
| **Custom Parts Totals** | | | |
| `custom_parts_output` | JSON | DEFAULT NULL | Cumulative custom part outputs |
| `last_updated` | DATETIME | AUTO-UPDATE | Last modification timestamp |

**Indexes**: `idx_machine_id`, `idx_last_updated`

### 9. `monitor_applicator` (Hybrid Structure)
**Purpose**: Tracks cumulative output counts for applicator parts over time.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `monitor_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `applicator_id` | INT | NOT NULL, UNIQUE, FK → applicators | Applicator being monitored |
| **Standard Parts Totals** | | | |
| `total_output` | INT | DEFAULT 0 | Cumulative total output |
| `wire_crimper_output` | INT | DEFAULT 0 | Cumulative wire crimper output |
| `wire_anvil_output` | INT | DEFAULT 0 | Cumulative wire anvil output |
| `insulation_crimper_output` | INT | DEFAULT 0 | Cumulative insulation crimper output |
| `insulation_anvil_output` | INT | DEFAULT 0 | Cumulative insulation anvil output |
| `slide_cutter_output` | INT | DEFAULT 0 | Cumulative slide cutter output |
| `cutter_holder_output` | INT | DEFAULT 0 | Cumulative cutter holder output |
| `shear_blade_output` | INT | DEFAULT 0 | Cumulative shear blade output |
| `cutter_a_output` | INT | DEFAULT 0 | Cumulative cutter A output |
| `cutter_b_output` | INT | DEFAULT 0 | Cumulative cutter B output |
| **Custom Parts Totals** | | | |
| `custom_parts_output` | JSON | DEFAULT NULL | Cumulative custom part outputs |
| `last_updated` | DATETIME | AUTO-UPDATE | Last modification timestamp |

**Indexes**: `idx_applicator_id`, `idx_last_updated`

---

## Reset Tracking

### 10. `machine_reset`
**Purpose**: Audit trail for machine part resets.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `reset_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `machine_id` | INT | NOT NULL, FK → machines | Machine that was reset |
| `reset_by` | INT | NOT NULL, FK → users | User who performed the reset |
| `part_reset` | VARCHAR(50) | NOT NULL | Part that was reset (standard name or custom code, 'ALL' for full reset) |
| `reset_time` | DATETIME | DEFAULT CURRENT_TIMESTAMP | When reset occurred |
| `remarks` | TEXT | DEFAULT NULL | Optional notes about the reset |

**Indexes**: `idx_machine_id`, `idx_reset_time`

**Standard Part Values**: `'ALL'`, `'cut_blade'`, `'strip_blade_a'`, `'strip_blade_b'`
**Custom Part Values**: Any part code from `custom_part_definitions`

### 11. `applicator_reset`
**Purpose**: Audit trail for applicator part resets.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `reset_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `applicator_id` | INT | NOT NULL, FK → applicators | Applicator that was reset |
| `reset_by` | INT | NOT NULL, FK → users | User who performed the reset |
| `part_reset` | VARCHAR(50) | NOT NULL | Part that was reset (standard name or custom code, 'ALL' for full reset) |
| `reset_time` | DATETIME | DEFAULT CURRENT_TIMESTAMP | When reset occurred |
| `remarks` | TEXT | DEFAULT NULL | Optional notes about the reset |

**Indexes**: `idx_applicator_id`, `idx_reset_time`

**Standard Part Values**: `'ALL'`, `'wire_crimper'`, `'wire_anvil'`, `'insulation_crimper'`, `'insulation_anvil'`, `'slide_cutter'`, `'cutter_holder'`, `'shear_blade'`, `'cutter_a'`, `'cutter_b'`

---

## Hybrid Architecture Benefits

### **Performance Optimization**
- **Standard Parts (90% of operations)**: Direct column access - fastest possible queries
- **Custom Parts (10% of operations)**: JSON queries - acceptable performance for rare usage
- **No JOINs required** for basic output retrieval operations

### **Development Simplicity**
- **Familiar structure**: Standard parts use traditional columns
- **Easy ORM mapping**: Direct property mapping for common operations
- **Minimal complexity**: Only one small lookup table for custom parts

### **Business Flexibility**
- **Admin-managed**: Admins can add new parts without schema changes
- **Future-proof**: System can adapt to new part types
- **Backwards compatible**: Existing queries continue to work

---

## Data Flow and Relationships

### Primary Workflow:
1. **Setup**: Machines and applicators are registered
2. **Custom Parts** (optional): Admins define new custom parts as needed
3. **Inspection**: Users create records linking machines, applicators, and shifts
4. **Output Recording**: Both standard and custom part outputs are recorded
5. **Monitoring**: Cumulative totals are maintained automatically
6. **Maintenance**: Parts are reset when replaced, with full audit trail

### Key Relationships:
- Each **record** involves 1 machine and 1-2 applicators
- **Outputs** combine direct columns (standard parts) + JSON (custom parts)
- **Monitor tables** maintain running totals for both standard and custom parts
- **Reset tables** handle both standard and custom part resets

---