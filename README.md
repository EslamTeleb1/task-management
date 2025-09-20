## Task Management System

A modular Laravel application for managing tasks with role-based access control.
Built with:
- Laravel 11
- Laravel Sanctum for API authentication
- Spatie Permission for roles & permissions
- Laravel Modules for modular architecture

## Installation:

1. Clone the repository
   git clone https://github.com/EslamTeleb1/task-management.git
   cd task-management

2. Install dependencies
   composer install
   npm install && npm run build

3. Set up environment
   cp .env.example .env
   php artisan key:generate

4. Run migrations & seeders

     php artisan module:seed User 

   (This will create default roles (Manager, User) and seed sample users.)


5. Serve the app
   php artisan serve

## Authentication:

- Login
  POST /api/auth/login
  {
    "email": "manager@task.com",
    "password": "password123"
  }

- Logout
  POST /api/auth/logout

Authenticated requests must include the Bearer token:
Authorization: Bearer <token>

Roles & Permissions:

- Manager: Create, update, assign dependencies to tasks
- User: Update status of tasks (with restrictions)

## Routes are protected using Spatie’s role middleware.

API Endpoints:

Auth
- POST /api/auth/login
- POST /api/auth/logout

Tasks
- GET /api/tasks
- GET /api/tasks/{id}
- POST /api/tasks (Manager only)
- PUT /api/tasks/{id} (Manager or User)
- POST /api/tasks/{id}/dependencies (Manager only)

## Postman Collection:

You can import the Postman collection:
docs/TaskManagement.postman_collection.json

Development Notes:

- Modules live under Modules/ (Auth, User, Task)
- Each module has its own controllers, services, repositories, migrations, and seeders
- Dependency Injection is used with Repository Pattern
- Spatie middleware is registered in bootstrap/app.php
- Routes are protected using auth:sanctum and role middleware

## Seeded Users:

- Manager
  Email:manager@manager.com
  Password: password123
- Users
  Emails: user1@user.com … user5@user.com
  Password: password123

