-- =====================================================================
-- Hostel Management System - Database Schema
-- =====================================================================
-- Import this file in phpMyAdmin (or `mysql -u root -p < hostel_management.sql`)
-- Database name used by the app: management   (see db.php)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS management;
USE management;

-- ---------------------------------------------------------------------
-- ADMIN
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL  -- stored as a password_hash(), never plain text
) ENGINE=InnoDB;

-- NOTE: No default admin row is inserted here on purpose.
-- Run setup_first_admin.php once in your browser after importing this
-- file -- it creates a properly password_hash()'d admin account for you.
-- See README.md for details.

-- ---------------------------------------------------------------------
-- USERS (students)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    regno    VARCHAR(50)  NOT NULL UNIQUE,
    phone    VARCHAR(20)  NULL,
    password VARCHAR(255) NOT NULL,  -- stored as a password_hash()
    name     VARCHAR(150) NULL,
    year     VARCHAR(20)  NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- NOTE: No demo student row is inserted here either, for the same
-- reason -- see README.md "Create your first student login".

-- ---------------------------------------------------------------------
-- FEES
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS fees (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    regno       VARCHAR(50) NOT NULL,
    name        VARCHAR(150) NULL,
    year        VARCHAR(20) NULL,
    fees_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    fees_status ENUM('paid','unpaid') NOT NULL DEFAULT 'unpaid',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (regno)
) ENGINE=InnoDB;

-- No sample row inserted here either -- add fee records for real
-- students via phpMyAdmin once your student accounts exist.

-- ---------------------------------------------------------------------
-- MAINTENANCE REQUESTS
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS maintenance (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    regno       VARCHAR(50) NOT NULL,
    category    VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    status      ENUM('Pending','Solved') NOT NULL DEFAULT 'Pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NULL DEFAULT NULL,
    INDEX (regno),
    INDEX (status)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- GATE PASS / LEAVE
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS gatepass_leave (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    regno       VARCHAR(50) NOT NULL,
    type        ENUM('Gate Pass','Leave') NOT NULL,
    reason      VARCHAR(100) NOT NULL,
    out_time    VARCHAR(20) NULL,
    in_time     VARCHAR(20) NULL,
    start_date  DATE NULL,
    return_date DATE NULL,
    attachment  VARCHAR(255) NULL,
    status      ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (regno),
    INDEX (status)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- I-CARD (issued card data) + I-CARD REQUESTS (workflow)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS icard (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    regno VARCHAR(50) NOT NULL UNIQUE,
    name  VARCHAR(150) NULL,
    year  VARCHAR(20) NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS icard_requests (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    regno   VARCHAR(50) NOT NULL,
    status  ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (regno)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- CHANGE INFORMATION REQUESTS
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS change_information_requests (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    regno       VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    status      ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (regno)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- REFUND REQUESTS (multi-stage approval)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS refund_requests (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    registration_no   VARCHAR(50) NOT NULL,
    request_type      VARCHAR(100) NOT NULL,
    amount            DECIMAL(10,2) NOT NULL DEFAULT 0,
    bank_name         VARCHAR(150) NULL,
    account_no        VARCHAR(50) NULL,
    ifsc              VARCHAR(20) NULL,
    director_status   ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    rector_status     ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    librarian_status  ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    accountant_status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (registration_no)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- REPORTING HISTORY
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reporting_history (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    registration_no VARCHAR(50) NOT NULL,
    remark          TEXT NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (registration_no)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- PROMOTION (academic progress / re-admission form)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS promotion (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    regno           VARCHAR(50) NOT NULL,
    course          VARCHAR(100) NOT NULL,
    college_name    VARCHAR(150) NOT NULL,
    semester        VARCHAR(50) NOT NULL,
    college_contact VARCHAR(20) NOT NULL,
    last_exam       VARCHAR(100) NOT NULL,
    total_marks     INT NOT NULL,
    obtained_marks  INT NOT NULL,
    percentage      DECIMAL(5,2) NOT NULL,
    id_type         VARCHAR(50) NOT NULL,
    id_number       VARCHAR(50) NOT NULL,
    prayer_jan      INT NULL,
    prayer_feb      INT NULL,
    prayer_mar      INT NULL,
    prayer_apr      INT NULL,
    prayer_may      INT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (regno)
) ENGINE=InnoDB;
