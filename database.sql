-- =====================================================
-- Clinic Patient Records System - Database Script
-- Import this file in phpMyAdmin (Import tab) or run it
-- in the MySQL console: SOURCE database.sql;
-- =====================================================

CREATE DATABASE IF NOT EXISTS clinic_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE clinic_db;

-- Drop in the right order (child first) so the script can be re-run
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS doctors;

-- -----------------------------------------------------
-- Table 1: doctors (parent table)
-- -----------------------------------------------------
CREATE TABLE doctors (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  full_name      VARCHAR(100) NOT NULL,
  specialization VARCHAR(100) NOT NULL,
  phone          VARCHAR(20)  NOT NULL
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table 2: patients (child table, linked to doctors)
-- -----------------------------------------------------
CREATE TABLE patients (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  patient_no    VARCHAR(20)  NOT NULL UNIQUE,
  full_name     VARCHAR(100) NOT NULL,
  gender        ENUM('Male','Female','Other') NOT NULL,
  date_of_birth DATE         NOT NULL,
  phone         VARCHAR(20)  NOT NULL,
  address       VARCHAR(150) DEFAULT NULL,
  blood_group   VARCHAR(5)   DEFAULT NULL,
  allergies     VARCHAR(150) DEFAULT NULL,
  diagnosis     VARCHAR(255) DEFAULT NULL,
  doctor_id     INT          DEFAULT NULL,
  registered_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_patient_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Sample data
-- -----------------------------------------------------
INSERT INTO doctors (full_name, specialization, phone) VALUES
('Dr. Sarah Namutebi', 'General Practitioner', '0772100001'),
('Dr. John Okello',    'Pediatrician',         '0772100002'),
('Dr. Grace Atim',     'Gynecologist',         '0772100003'),
('Dr. Peter Kato',     'Dentist',              '0772100004');

INSERT INTO patients
(patient_no, full_name, gender, date_of_birth, phone, address, blood_group, allergies, diagnosis, doctor_id) VALUES
('PT-0001', 'Mary Nakato',    'Female', '1995-03-14', '0701234567', 'Ntinda, Kampala',  'O+',  'Penicillin', 'Malaria',            1),
('PT-0002', 'David Ssemwogerere', 'Male', '2018-07-22', '0709876543', 'Kireka, Wakiso', 'A+',  'None',       'Common cold',        2),
('PT-0003', 'Joan Achieng',   'Female', '1988-11-05', '0755111222', 'Gulu City',        'B+',  'Dust',       'Routine antenatal',  3),
('PT-0004', 'Moses Mugisha',  'Male',   '1979-01-30', '0782333444', 'Mbarara',          'AB-', 'None',       'Tooth extraction',   4),
('PT-0005', 'Aisha Nabirye',  'Female', '2001-09-18', '0771555666', 'Jinja',            'O-',  'Peanuts',    'Typhoid',            1);
