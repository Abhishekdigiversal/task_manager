# Project-Task Management System

A simplified project-task management system built with Laravel backend and React.js frontend.

## Table of Contents

- [Features](#features)  
- [Tech Stack](#tech-stack)  
- [Installation](#installation)  
- [Setup Backend (Laravel)](#setup-backend-laravel)  
- [Setup Frontend (React)](#setup-frontend-react)  
- [Usage](#usage)  
- [Authentication Flow](#authentication-flow)  
- [API Requests and Token Handling](#api-requests-and-token-handling)  


## Features

### Backend (Laravel)

- User authentication via Laravel Sanctum  
- Models:
  - **Project:** with status (`Active`, `Paused`, `Closed`)  
  - **Task:** with title, status (`Active`, `Paused`, `Closed`), recurrence (`daily`, `weekly`, `monthly`, `custom`), start date, due date  
- APIs:
  - Create and update Projects  
  - Create and update Tasks  
  - Mark task as complete → automatically create the next task if Project status is `Active`  
  - Prevent auto-creation of tasks if Project is `Paused` or `Closed`  

### Frontend (React.js)

- Dashboard displaying list of projects and their tasks  
- Task creation and update form with recurrence options  
- Mark tasks as complete with automatic handling of next task creation  
- API communication using Axios (or Fetch) with token-based authentication and CSRF protection  


## Tech Stack

- **Backend:** Laravel 10 with Sanctum authentication  
- **Frontend:** React.js, Axios for API calls  
- **Styling:** Bootstrap 5 (optional)  

---

## Installation

### Backend (Laravel)

1. **Clone the repository**  
   ```bash
   https://github.com/Abhishekdigiversal/task_manager.git
   cd project-task-management/backend
2. **Install PHP dependencies**
   composer install
3.   **Create environment configuration**
    cp .env.example .env
4.   **Run migrations**
    php artisan migrate
5.   **Start server**
    php artisan serve

The backend server will run at http://localhost:8000.

### Frontend  (React)

1. **Navigate to frontend directory**
  cd ../frontend
2. **Install npm dependencies**
  npm install
3. **Configure API base URL if needed**
   Open src/services/api.js
    Ensure baseURL matches backend URL ( http://localhost:8000)
4. **Start React server**
    npm start
   The frontend server will run at http://localhost:3000.

   **Usage**
     - Register and login to obtain API token
     - Access dashboard to view Projects and Tasks
     - Create/update projects and tasks via forms
     - Mark tasks complete to auto-create next tasks (if project status is Active)
     - React frontend automatically attaches token for API authentication

  **Authentication Flow**
     -Login returns token stored in localStorage
     -Axios interceptor attaches Bearer token in Authorization header
     -Laravel Sanctum manages token validation and CSRF protection

  **API Requests and Token Handling**
     - Axios instance configured with base URL, headers, and interceptors
     - CSRF token fetched and attached (if using Sanctum)
     - Authorization header set with Bearer token from localStorage
     - Handles 401 Unauthorized errors and token absence gracefully

