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