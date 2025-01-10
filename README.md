# Project Management System

A web application designed to manage users, projects, tasks, and teams. This system includes user authentication, project management features, task organization, an admin dashboard, data visualization charts, and a Kanban board for visual task management.

## Features

- **User Authentication**
  - Login and registration functionality
  - Email verification for user registration
  - User session management

- **Project Management**
  - Create and manage projects
  - Assign tasks to projects
  - Team member assignment to projects

- **Task Management**
  - Create, edit, and delete tasks
  - Track task status (To Do, In Progress, Done)
  - Assign tasks to users

- **Admin Dashboard**
  - View and manage users, projects, and tasks
  - Display system statistics (e.g., total users, total projects)

- **Data Visualization**
  - Charts to visualize project, task, and user data

- **Kanban Board**
  - A Kanban board for each project to track tasks visually
  - Drag and drop tasks between columns to update their status

## Installation

To set up the project locally, follow these steps:

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Youcode-Classe-E-2024-2025/TAWBA_ZEHAF_PROJECT.git
Navigate into the project directory:
cd project-management-system
Install dependencies:
im using Composer for PHP dependency management,  so run:
composer install

Run migrations:

Run database migrations to set up the necessary tables:

bash
Copier le code
php artisan migrate
Start the server:

Start the development server:

bash
Copier le code
php -S localhost:8000 -t public
Access the application:

Open your browser and navigate to http://localhost:8000.

Kanban Board
The application includes a Kanban board feature for each project. This feature allows you to visually track and manage tasks within a project.

Features of the Kanban Board:
Columns: Tasks are displayed in columns such as To Do, In Progress, and Done.
Drag and Drop: You can easily move tasks between columns by dragging and dropping them.
Task Status: Updating the task's position on the board automatically updates its status.
How to Use the Kanban Board:
Navigate to a project page from the project dashboard.
Click on the "View Kanban Board" button for the project you want to manage.
On the Kanban board, you will see tasks organized by columns based on their current status.
Drag tasks from one column to another to update their status (e.g., from To Do to In Progress).
Tasks will automatically save their new status when moved between columns.
This visual task management system makes it easy to track project progress and collaborate with team members.

Contributing
We welcome contributions to improve this project! If you'd like to contribute:

Fork the repository.
Create a new branch (git checkout -b feature-name).
Make your changes and commit them (git commit -am 'Add feature').
Push to your fork (git push origin feature-name).
Create a pull request with a description of the changes.
Project Management System
A web application designed to manage users, projects, tasks, and teams. This system includes features such as user authentication, project management, task organization, an admin dashboard, data visualization, and a Kanban board for visual task management.

Features
User Authentication

Login and registration functionality
Email verification for user registration
User session management
Project Management

Create and manage projects
Assign tasks to projects
Assign team members to projects
Task Management

Create, edit, and delete tasks
Track task status (To Do, In Progress, Done)
Assign tasks to users
Admin Dashboard

View and manage users, projects, and tasks
Display system statistics (e.g., total users, total projects)
Data Visualization

Charts to visualize project, task, and user data
Kanban Board

A Kanban board for each project to track tasks visually
Drag and drop tasks between columns to update their status
Installation
Follow these steps to set up the project locally:

1. Clone the Repository
git clone https://github.com/Youcode-Classe-E-2024-2025/TAWBA_ZEHAF_PROJECT.git
Navigate into the project directory:

cd tawba-zehaf_project
2. Install Dependencies
Make sure you have Composer installed. Then, run the following command to install PHP dependencies:

composer install
3. Set Up the Database
Run the database migrations to set up the necessary tables in your MySQL database:


php artisan migrate
Ensure you have configured your .env file with the correct database credentials.

4. Start the Server
Start the development server using PHP's built-in server:
php -S localhost:8000 -t public
5. Access the Application
Open your browser and navigate to:

arduino
http://localhost:8000
Kanban Board
The application includes a Kanban board for each project. This feature allows you to visually track and manage tasks within a project.

Features of the Kanban Board:
Columns: Tasks are displayed in columns such as To Do, In Progress, and Done.
Drag and Drop: You can easily move tasks between columns by dragging and dropping them.
Task Status: Updating the task's position on the board automatically updates its status.
How to Use the Kanban Board:
Navigate to a project page from the project dashboard.
Click on the "View Kanban Board" button for the project you want to manage.
On the Kanban board, you will see tasks organized by columns based on their current status.
Drag tasks from one column to another to update their status (e.g., from To Do to In Progress).
Tasks will automatically save their new status when moved between columns.
This visual task management system makes it easy to track project progress and collaborate with team members.

Database Schema
Below is the SQL schema for the project database. It includes tables for users, roles, permissions, projects, tasks, and other entities in the system.
CREATE DATABASE IF NOT EXISTS project_management;
USE project_management;

-- Roles table
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Permissions table
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- User table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Tasks table
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

-- Other related tables (categories, tags, activity_log, etc.) can also be added
This schema defines roles and permissions for users, as well as the structure for managing projects, tasks, categories, and tags.

Contributing
We welcome contributions to improve this project! If you'd like to contribute, please follow these steps:

Fork the repository.
Create a new branch (git checkout -b feature-name).
Make your changes and commit them (git commit -am 'Add feature').
Push to your fork (git push origin feature-name).
Create a pull request with a description of the changes.
License
This project is licensed under the MIT License - see the LICENSE file for details.