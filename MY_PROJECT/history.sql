create database my_project ;
use my_project ;
CREATE TABLE users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user'
);

CREATE TABLE boxes
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    box_name VARCHAR(50)
);
CREATE TABLE classes
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50),
    box_id INT,

    FOREIGN KEY (box_id) REFERENCES boxes(id)
);
CREATE TABLE students
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    photo VARCHAR(255),
    class_id INT,

    FOREIGN KEY (class_id) REFERENCES classes(id)
);
CREATE TABLE attendance
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    status ENUM('present', 'absent') DEFAULT 'present' ,

    attendance_date DATE ,

    student_id INT ,

    FOREIGN KEY (student_id) REFERENCES students(id)
);



INSERT INTO users
(username, email, password, role)

VALUES

(
    '7ariss_3am',
    '7ariss_3am@gmail.com',
    '$2y$10$CCNSl2iBT5ZOJNl6HpxM/OQ3bAD6tprCsIakpafHhb.HmJSOEKbeG',
    'admin'
),

(
    'omar',
    'omar@gmail.com',
    '$2y$10$OXXsJ9ovgZghJ8WHSn1yOeRf1kwAvqOTItYrWhEKK5kdsuy9UOt4u',
    'user'
),

(
    'sara',
    'sara@gmail.com',
    '$2y$10$G3GCzRyIBZogB6xZInFIgeNU5BlafMombe5twcUv.auR2GrDTVrBO',
    'user'
),

(
    'ali',
    'ali@gmail.com',
    '$2y$10$bpcxUEStFD/8/ohrqymuTus9nGyX4OZW7nJZo1z6RihGBdLIb6IhW',
    'user'
),

(
    'lina',
    'lina@gmail.com',
    '$2y$10$5lt/nixqQjVZCtg2FSLLF.cwzn8MzRK0Y9JKTba9W3E5CJc9sl6ne',
    'user'
);


ALTER TABLE attendance
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

drop table attendance ;
CREATE TABLE attendance
(
    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    attendance_date DATE NOT NULL,

    attendance_hour INT NOT NULL,

    status ENUM('present','absent') NOT NULL DEFAULT 'present',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(student_id, attendance_date, attendance_hour),

    FOREIGN KEY (student_id) REFERENCES students(id)
);



use my_project ;

INSERT IGNORE INTO boxes (box_name) VALUES ('Box 1');
INSERT IGNORE INTO classes (class_name, box_id) VALUES ('Class 1', 1);

INSERT INTO students (name, photo, class_id)
VALUES
('Student 1', 'default.png', 1),
('Student 2', 'default.png', 1),
('Student 3', 'default.png', 1),
('Student 4', 'default.png', 1),
('Student 5', 'default.png', 1),
('Student 6', 'default.png', 1),
('Student 7', 'default.png', 1),
('Student 8', 'default.png', 1),
('Student 9', 'default.png', 1),
('Student 10', 'default.png', 1);

DELETE FROM attendance WHERE id > 0;
INSERT INTO attendance
(student_id, attendance_date, attendance_hour, status, created_at)

VALUES

-- DAY 1
(1, '2026-05-01', 8, 'present', NOW()),
(2, '2026-05-01', 8, 'absent', NOW()),
(3, '2026-05-01', 8, 'present', NOW()),
(4, '2026-05-01', 8, 'present', NOW()),
(5, '2026-05-01', 8, 'absent', NOW()),
(6, '2026-05-01', 8, 'present', NOW()),
(7, '2026-05-01', 8, 'present', NOW()),
(8, '2026-05-01', 8, 'absent', NOW()),
(9, '2026-05-01', 8, 'present', NOW()),
(10, '2026-05-01', 8, 'present', NOW()),

-- DAY 2
(1, '2026-05-02', 8, 'absent', NOW()),
(2, '2026-05-02', 8, 'present', NOW()),
(3, '2026-05-02', 8, 'present', NOW()),
(4, '2026-05-02', 8, 'absent', NOW()),
(5, '2026-05-02', 8, 'present', NOW()),
(6, '2026-05-02', 8, 'present', NOW()),
(7, '2026-05-02', 8, 'absent', NOW()),
(8, '2026-05-02', 8, 'present', NOW()),
(9, '2026-05-02', 8, 'present', NOW()),
(10, '2026-05-02', 8, 'present', NOW()),

-- DAY 3
(1, '2026-05-03', 8, 'present', NOW()),
(2, '2026-05-03', 8, 'present', NOW()),
(3, '2026-05-03', 8, 'absent', NOW()),
(4, '2026-05-03', 8, 'present', NOW()),
(5, '2026-05-03', 8, 'present', NOW()),
(6, '2026-05-03', 8, 'absent', NOW()),
(7, '2026-05-03', 8, 'present', NOW()),
(8, '2026-05-03', 8, 'present', NOW()),
(9, '2026-05-03', 8, 'absent', NOW()),
(10, '2026-05-03', 8, 'present', NOW()),

-- DAY 4
(1, '2026-05-04', 8, 'present', NOW()),
(2, '2026-05-04', 8, 'present', NOW()),
(3, '2026-05-04', 8, 'present', NOW()),
(4, '2026-05-04', 8, 'absent', NOW()),
(5, '2026-05-04', 8, 'present', NOW()),
(6, '2026-05-04', 8, 'present', NOW()),
(7, '2026-05-04', 8, 'present', NOW()),
(8, '2026-05-04', 8, 'absent', NOW()),
(9, '2026-05-04', 8, 'present', NOW()),
(10, '2026-05-04', 8, 'present', NOW()),

-- DAY 5
(1, '2026-05-05', 8, 'absent', NOW()),
(2, '2026-05-05', 8, 'present', NOW()),
(3, '2026-05-05', 8, 'present', NOW()),
(4, '2026-05-05', 8, 'present', NOW()),
(5, '2026-05-05', 8, 'absent', NOW()),
(6, '2026-05-05', 8, 'present', NOW()),
(7, '2026-05-05', 8, 'present', NOW()),
(8, '2026-05-05', 8, 'present', NOW()),
(9, '2026-05-05', 8, 'absent', NOW()),
(10, '2026-05-05', 8, 'present', NOW());

-- Table to assign classes to users (teachers)
CREATE TABLE IF NOT EXISTS user_class
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    UNIQUE(user_id, class_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- sample assignments (make sure class ids exist in your DB)
INSERT IGNORE INTO user_class (user_id, class_id) VALUES (2, 1), (3, 1);


ALTER TABLE attendance ADD COLUMN admin_flagged TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE attendance ADD COLUMN flagged_at DATETIME NULL;




