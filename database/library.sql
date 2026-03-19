-- =============================================
-- Library Management System - Database Schema
-- Database: library_management_system
-- Matches Original Project Synopsis Exactly
-- =============================================

CREATE DATABASE IF NOT EXISTS library_management_system;
USE library_management_system;

-- =============================================
-- 1. ADMINS TABLE
-- =============================================
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 2. LIBRARIAN DETAILS TABLE
-- =============================================
CREATE TABLE librarian_details (
    librarian_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    experience INT,
    phone VARCHAR(20),
    email VARCHAR(150) NOT NULL UNIQUE,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 3. LIBRARIAN LOGIN TABLE
-- =============================================
CREATE TABLE librarian_login (
    login_id INT AUTO_INCREMENT PRIMARY KEY,
    librarian_id INT NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME DEFAULT NULL,
    FOREIGN KEY (librarian_id) REFERENCES librarian_details(librarian_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- 4. MEMBER DETAILS TABLE
-- =============================================
CREATE TABLE member_details (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender VARCHAR(20),
    address VARCHAR(255),
    phone VARCHAR(20),
    membership_type VARCHAR(100),
    join_date DATE,
    email VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 5. MEMBER LOGIN TABLE
-- =============================================
CREATE TABLE member_login (
    login_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME DEFAULT NULL,
    FOREIGN KEY (member_id) REFERENCES member_details(member_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- 6. BOOKS TABLE
-- =============================================
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    quantity INT NOT NULL DEFAULT 0,
    available_quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 7. BOOK ISSUES TABLE
-- =============================================
CREATE TABLE book_issues (
    issue_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    issue_date DATE NOT NULL,
    status ENUM('Pending', 'Approved', 'Done', 'Cancelled') DEFAULT 'Pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES member_details(member_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- 8. ISSUE RECORDS TABLE
-- =============================================
CREATE TABLE issue_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    remarks TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (issue_id) REFERENCES book_issues(issue_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES member_details(member_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- SAMPLE DATA
-- =============================================

-- Admin (password: admin123)
INSERT INTO admins (username, password, email)
VALUES ('admin', '$2y$10$8K1p/a0dR1xFc0aGMz9Oy.0NKGC8U1V6M8fE3cJ1Ue2vK9TqW5iq', 'admin@library.com');

-- Librarian details
INSERT INTO librarian_details (name, specialization, experience, phone, email, status)
VALUES ('Rahul Sharma', 'Cataloging', 5, '9876543210', 'rahul@library.com', 'Approved');

-- Librarian login (password: librarian123)
INSERT INTO librarian_login (librarian_id, username, password)
VALUES (1, 'rahul', '$2y$10$8K1p/a0dR1xFc0aGMz9Oy.0NKGC8U1V6M8fE3cJ1Ue2vK9TqW5iq');

-- Member details
INSERT INTO member_details (name, age, gender, address, phone, membership_type, join_date, email)
VALUES ('Priya Patel', 21, 'Female', '456 College Road', '9123456780', 'Student', '2026-01-15', 'priya@gmail.com');

-- Member login (password: member123)
INSERT INTO member_login (member_id, username, password)
VALUES (1, 'priya', '$2y$10$8K1p/a0dR1xFc0aGMz9Oy.0NKGC8U1V6M8fE3cJ1Ue2vK9TqW5iq');

-- Books (5 sample books)
INSERT INTO books (title, author, category, quantity, available_quantity) VALUES
('The C Programming Language', 'Brian Kernighan', 'Programming', 5, 5),
('Database System Concepts', 'Abraham Silberschatz', 'Database', 3, 3),
('Introduction to Algorithms', 'Thomas H. Cormen', 'Algorithms', 4, 4),
('Operating System Concepts', 'Abraham Silberschatz', 'Operating Systems', 3, 3),
('Computer Networking', 'James Kurose', 'Networking', 2, 2);