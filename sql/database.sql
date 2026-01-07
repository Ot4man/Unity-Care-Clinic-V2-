CREATE DATABASE IF NOT EXISTS unity_care_clinic_v2;
USE unity_care_clinic_v2;


-- USERS

CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  phone VARCHAR(20),
  password_hash VARCHAR(100) NOT NULL,
  role ENUM('admin','doctor','patient') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (email)
);



-- DEPARTMENTS

CREATE TABLE departments (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  location VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);


-- DOCTORS

CREATE TABLE doctors (
  id INT NOT NULL,
  specialization VARCHAR(100),
  department_id INT,
  PRIMARY KEY (id),
  CONSTRAINT fk_doctor_user
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_doctor_department
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);


-- PATIENTS

CREATE TABLE patients (
  id INT NOT NULL,
  gender ENUM('male','female'),
  date_of_birth DATE,
  address VARCHAR(30),
  PRIMARY KEY (id),
  CONSTRAINT fk_patient_user
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
);


-- APPOINTMENTS

CREATE TABLE appointments (
  id INT NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL,
  time TIME NOT NULL,
  doctor_id INT NOT NULL,
  patient_id INT NOT NULL,
  reason TEXT,
  status ENUM('scheduled','done','cancelled') DEFAULT 'scheduled',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_appointment_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
  CONSTRAINT fk_appointment_patient
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);


-- MEDICATIONS

CREATE TABLE medications (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  instructions TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);


-- PRESCRIPTIONS

CREATE TABLE prescriptions (
  id INT NOT NULL AUTO_INCREMENT,
  date DATE NOT NULL,
  doctor_id INT NOT NULL,
  patient_id INT NOT NULL,
  medication_id INT NOT NULL,
  dosage_instructions TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_prescription_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
  CONSTRAINT fk_prescription_patient
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_prescription_medication
    FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE
);

--Insertion :  

-- USERS 

INSERT INTO users (id, email, first_name, last_name, phone, password_hash, role) VALUES
(1, 'admin@hospital.com', 'Admin', 'Root', '0600000000', 'hash1', 'admin'),

(2, 'doc1@hospital.com', 'John', 'Doe', '0611111111', 'hash2', 'doctor'),
(3, 'doc2@hospital.com', 'Sarah', 'Smith', '0622222222', 'hash3', 'doctor'),
(4, 'doc3@hospital.com', 'Ali', 'Kamal', '0633333333', 'hash4', 'doctor'),

(5, 'pat1@mail.com', 'Ahmed', 'Hassan', '0644444444', 'hash5', 'patient'),
(6, 'pat2@mail.com', 'Fatima', 'Zahra', '0655555555', 'hash6', 'patient'),
(7, 'pat3@mail.com', 'Omar', 'Salim', '0666666666', 'hash7', 'patient');
 

-- DEPARTMENTS 

INSERT INTO departments (id, name, location) VALUES
(1, 'Cardiology', 'Building A'),
(2, 'Neurology', 'Building B'),
(3, 'Pediatrics', 'Building C');
 

-- DOCTORS 

INSERT INTO doctors (id, specialization, department_id) VALUES
(2, 'Cardiologist', 1),
(3, 'Neurologist', 2),
(4, 'Pediatrician', 3);
 

-- PATIENTS 

INSERT INTO patients (id, gender, date_of_birth, address) VALUES
(5, 'male', '1995-06-15', 'Rabat'),
(6, 'female', '2000-09-21', 'Casablanca'),
(7, 'male', '1988-12-03', 'Fes');
 

-- MEDICATIONS 

INSERT INTO medications (id, name, instructions) VALUES
(1, 'Paracetamol', 'Every 8 hours'),
(2, 'Ibuprofen', 'After meals'),
(3, 'Amoxicillin', '3 times a day');
 

-- APPOINTMENTS 

INSERT INTO appointments (date, time, doctor_id, patient_id, reason, status) VALUES
('2026-01-05', '10:00:00', 2, 5, 'Chest pain', 'scheduled'),
('2026-01-06', '11:30:00', 3, 6, 'Headache', 'done'),
('2026-01-07', '09:00:00', 4, 7, 'Child fever', 'cancelled');
 

-- PRESCRIPTIONS 

INSERT INTO prescriptions (date, doctor_id, patient_id, medication_id, dosage_instructions) VALUES
('2026-01-06', 3, 6, 1, '2 pills per day'),
('2026-01-07', 4, 7, 2, 'After meals'),
('2026-01-05', 2, 5, 3, 'Every 8 hours');

