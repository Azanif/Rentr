-- Create Database
CREATE DATABASE IF NOT EXISTS rentrdb;
USE rentrdb;

-- ======================
-- Table: ADMIN
-- ======================
CREATE TABLE ADMIN (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    Admin_Name VARCHAR(100) NOT NULL,
    Admin_PhoneNum VARCHAR(20),
    Admin_Address VARCHAR(255),
    Admin_Email VARCHAR(100)
);

-- ======================
-- Table: OWNER
-- ======================
CREATE TABLE OWNER (
    OwnerID INT AUTO_INCREMENT PRIMARY KEY,
    Owner_Name VARCHAR(100) NOT NULL,
    Owner_PhoneNum VARCHAR(20),
    Owner_Address VARCHAR(255),
    Owner_Email VARCHAR(100)
);

-- ======================
-- Table: PROPERTIES
-- ======================
CREATE TABLE PROPERTIES (
    PropertyID INT AUTO_INCREMENT PRIMARY KEY,
    Property_Name VARCHAR(100) NOT NULL,
    Property_Type VARCHAR(50),
    Property_Address VARCHAR(255),
    MonthlyRent DECIMAL(10,2),
    Status VARCHAR(50),
    AllowedGender ENUM('Male', 'Female', 'Any') DEFAULT 'Any',
    OwnerID INT,
    FOREIGN KEY (OwnerID) REFERENCES OWNER(OwnerID)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ======================
-- Table: TENANT
-- ======================
CREATE TABLE TENANT (
    Tenant_ID INT AUTO_INCREMENT PRIMARY KEY,
    Tenant_Name VARCHAR(100) NOT NULL,
    Tenant_PhoneNum VARCHAR(20),
    Tenant_Address VARCHAR(255),
    Tenant_Email VARCHAR(100),
    Tenant_Gender ENUM('Male', 'Female') NOT NULL
);

-- ======================
-- Table: REGISTRATION
-- ======================
CREATE TABLE REGISTRATION (
    Registration_ID INT AUTO_INCREMENT PRIMARY KEY,
    Registration_Time TIME,
    Registration_Date DATE,
    Start_Date DATE,
    End_Date DATE,
    Contract_Status VARCHAR(50),
    TenantID INT,
    PropertiesID INT,
    OwnerID INT,
    AdminID INT,
    FOREIGN KEY (TenantID) REFERENCES TENANT(Tenant_ID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (PropertiesID) REFERENCES PROPERTIES(PropertyID)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (OwnerID) REFERENCES OWNER(OwnerID)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (AdminID) REFERENCES ADMIN(AdminID)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ======================
-- Table: PAYMENT
-- ======================
CREATE TABLE PAYMENT (
    Payment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Payment_Amount DECIMAL(10,2) NOT NULL,
    Payment_Date DATE,
    Tenant_ID INT,
    PropertyID INT,
    FOREIGN KEY (Tenant_ID) REFERENCES TENANT(Tenant_ID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (PropertyID) REFERENCES PROPERTIES(PropertyID)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- ======================
-- Optional: Sample Data
-- ======================
INSERT INTO ADMIN (Admin_Name, Admin_PhoneNum, Admin_Address, Admin_Email)
VALUES ('Afiq Admin', '0112233445', 'Kangar, Perlis', 'afiqadmin@rentr.com');

INSERT INTO OWNER (Owner_Name, Owner_PhoneNum, Owner_Address, Owner_Email)
VALUES 
('Ali Owner', '0123456789', 'Alor Setar', 'aliowner@gmail.com'),
('Fatimah Owner', '0139876543', 'Kuala Lumpur', 'fatimahowner@gmail.com');

INSERT INTO PROPERTIES (Property_Name, Property_Type, Property_Address, MonthlyRent, Status, AllowedGender, OwnerID)
VALUES 
('Kaman Apartment', 'Apartment', 'Jalan Bukit Lagi, Kangar', 800.00, 'Available', 'Any', 1),
('Putra Residency', 'Condo', 'Jalan Putra, Alor Setar', 1200.00, 'Available', 'Any', 2);

INSERT INTO TENANT (Tenant_Name, Tenant_PhoneNum, Tenant_Address, Tenant_Email, Tenant_Gender)
VALUES 
('Ahmad Tenant', '0145556677', 'Arau, Perlis', 'ahmadtenant@gmail.com', 'Male'),
('Siti Tenant', '0169988776', 'Kangar, Perlis', 'sititenant@gmail.com', 'Female');

INSERT INTO REGISTRATION (Registration_Time, Registration_Date, Start_Date, End_Date, Contract_Status, TenantID, PropertiesID, OwnerID, AdminID)
VALUES 
('10:30:00', '2025-10-01', '2025-10-01', '2026-09-30', 'Active', 1, 1, 1, 1),
('11:15:00', '2025-10-10', '2025-10-10', '2026-10-09', 'Active', 2, 2, 2, 1);

INSERT INTO PAYMENT (Payment_Amount, Payment_Date, Tenant_ID, PropertyID)
VALUES 
(800.00, '2025-10-05', 1, 1),
(1200.00, '2025-10-12', 2, 2);
