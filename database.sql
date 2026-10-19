
CREATE DATABASE IF NOT EXISTS itsupport_db;
USE itsupport_db;

CREATE TABLE IF NOT EXISTS tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ticket_code VARCHAR(20) UNIQUE NOT NULL,
  requester_name VARCHAR(100) NOT NULL,
  department VARCHAR(100) NOT NULL,
  category ENUM('Hardware','Software','Network','Email','Printer','Other') NOT NULL,
  priority ENUM('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  subject VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Open','In Progress','Resolved','Closed') NOT NULL DEFAULT 'Open',
  assigned_to VARCHAR(100) DEFAULT 'e-Cadre Support',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO tickets (ticket_code, requester_name, department, category, priority, subject, description, status) VALUES
('TCK-2024-001', 'Mrs. Nkosi', 'Grade 4', 'Printer', 'High', 'Printer not connecting', 'Lab printer offline since morning, 23 learners affected', 'Open'),
('TCK-2024-002', 'Mr. Dlamini', 'Admin', 'Network', 'Critical', 'WiFi down in admin block', 'Cannot access SA-SAMS', 'In Progress'),
('TCK-2024-003', 'Mrs. Mabuza', 'Foundation Phase', 'Software', 'Medium', 'MS Teams login issue', 'Teams keeps asking for password', 'Resolved');
