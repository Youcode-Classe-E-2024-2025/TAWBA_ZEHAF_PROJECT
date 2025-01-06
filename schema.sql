CREATE DATABASE IF NOT EXISTS project_management;
USE project_management;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    project_id INT NOT NULL,
    assigned_to INT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE project_members (
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (project_id, user_id),
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default roles
INSERT INTO roles (name) VALUES ('admin'), ('project_manager'), ('team_member');

INSERT INTO users (name, email, password, role_id) 
VALUES 
    ('Alice Johnson', 'alice@example.com', 'hashed_password_1', 1),  -- admin
    ('Bob Smith', 'bob@example.com', 'hashed_password_2', 2),    -- project_manager
    ('Charlie Davis', 'charlie@example.com', 'hashed_password_3', 3), -- team_member
    ('David Wilson', 'david@example.com', 'hashed_password_4', 3),
    ('Eve White', 'eve@example.com', 'hashed_password_5', 2),
    ('Frank Green', 'frank@example.com', 'hashed_password_6', 3),
    ('Grace Black', 'grace@example.com', 'hashed_password_7', 1),
    ('Hannah Blue', 'hannah@example.com', 'hashed_password_8', 3),
    ('Ivy Brown', 'ivy@example.com', 'hashed_password_9', 2),
    ('Jack Grey', 'jack@example.com', 'hashed_password_10', 3);
INSERT INTO projects (name, description, created_by) 
VALUES 
    ('Website Redesign', 'Redesigning the company website to improve UX and accessibility.', 1),
    ('Mobile App Launch', 'Developing a new mobile app for customer engagement.', 2),
    ('CRM System Implementation', 'Implementation of a new CRM system to manage client relationships.', 3),
    ('Marketing Campaign', 'Create and manage a digital marketing campaign for the new product.', 4),
    ('Internal Tool Development', 'Developing an internal tool to improve team collaboration.', 5);
INSERT INTO tasks (title, description, status, project_id, assigned_to, created_by) 
VALUES 
    ('Design homepage', 'Design a new homepage layout with improved navigation and accessibility.', 'pending', 1, 3, 1),
    ('Develop backend API', 'Create API endpoints for user authentication and data storage.', 'in_progress', 1, 4, 2),
    ('Test mobile app UI', 'Conduct usability testing of the mobile app interface.', 'completed', 2, 5, 3),
    ('Create ad copy', 'Write ad copy for Google and Facebook ads.', 'in_progress', 4, 6, 4),
    ('Set up CRM database', 'Configure the CRM database for client data and reporting.', 'pending', 3, 7, 5),
    ('Create project plan', 'Write the initial project plan for marketing campaign.', 'completed', 4, 8, 6),
    ('Deploy internal tool', 'Deploy the first version of the internal tool for employee feedback.', 'in_progress', 5, 9, 7),
    ('Update user roles', 'Update user roles and permissions in the system as per the new policies.', 'pending', 1, 10, 8);
INSERT INTO project_members (project_id, user_id) 
VALUES 
    (1, 3), (1, 4), (1, 5), 
    (2, 5), (2, 6), (2, 7),
    (3, 7), (3, 8), (3, 9), 
    (4, 6), (4, 10),
    (5, 9), (5, 10);
    ALTER TABLE users
MODIFY COLUMN role_id INT DEFAULT 1;

CREATE USER IF NOT EXISTS 'users'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON project_management.* TO 'users'@'localhost';
FLUSH PRIVILEGES;
