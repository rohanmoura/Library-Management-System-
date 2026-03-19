-- =============================================
-- Library Management System - Database Schema
-- Database: library_management_system
-- =============================================

CREATE DATABASE IF NOT EXISTS library_management_system;
USE library_management_system;

-- =============================================
-- 1. ADMINS TABLE
-- =============================================
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 2. LIBRARIAN DETAILS TABLE
-- =============================================
CREATE TABLE librarian_details (
    librarian_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 3. LIBRARIAN LOGIN TABLE
-- =============================================
CREATE TABLE librarian_login (
    login_id INT AUTO_INCREMENT PRIMARY KEY,
    librarian_id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (librarian_id) REFERENCES librarian_details(librarian_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- 4. MEMBER DETAILS TABLE
-- =============================================
CREATE TABLE member_details (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- 5. MEMBER LOGIN TABLE
-- =============================================
CREATE TABLE member_login (
    login_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    status ENUM('issued', 'returned') DEFAULT 'issued',
    issued_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES member_details(member_id) ON DELETE CASCADE,
    FOREIGN KEY (issued_by) REFERENCES librarian_details(librarian_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- 8. ISSUE RECORDS TABLE (History/Log)
-- =============================================
CREATE TABLE issue_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    action ENUM('issued', 'returned') NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    remarks VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (issue_id) REFERENCES book_issues(issue_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES member_details(member_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- SAMPLE DATA
-- =============================================

-- Admin (password: admin123)
INSERT INTO admins (username, password, full_name, email)
VALUES ('admin', '$2y$10$8K1p/a0dR1xFc0aGMz9Oy.0NKGC8U1V6M8fE3cJ1Ue2vK9TqW5iq', 'System Admin', 'admin@library.com');

-- Librarian details
INSERT INTO librarian_details (full_name, email, phone, address)
VALUES ('Rahul Sharma', 'rahul@library.com', '9876543210', '123 Library Street');

-- Librarian login (password: librarian123)
INSERT INTO librarian_login (librarian_id, username, password, status)
VALUES (1, 'rahul', '$2y$10$8K1p/a0dR1xFc0aGMz9Oy.0NKGC8U1V6M8fE3cJ1Ue2vK9TqW5iq', 'active');

-- Member details
INSERT INTO member_details (full_name, email, phone, address)
VALUES ('Priya Patel', 'priya@gmail.com', '9123456780', '456 College Road');

-- Member login (password: member123)
INSERT INTO member_login (member_id, username, password, status)
VALUES (1, 'priya', '$2y$10$8K1p/a0dR1xFc0aGMz9Oy.0NKGC8U1V6M8fE3cJ1Ue2vK9TqW5iq', 'active');

-- Books (5 sample books)
INSERT INTO books (title, author, category, quantity, available_quantity) VALUES
('The C Programming Language', 'Brian Kernighan', 'Programming', 5, 5),
('Database System Concepts', 'Abraham Silberschatz', 'Database', 3, 3),
('Introduction to Algorithms', 'Thomas H. Cormen', 'Algorithms', 4, 4),
('Operating System Concepts', 'Abraham Silberschatz', 'Operating Systems', 3, 3),
('Computer Networking', 'James Kurose', 'Networking', 2, 2);