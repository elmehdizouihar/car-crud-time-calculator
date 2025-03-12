# car-crud-time-calculator
# Car CRUD Time Calculator

This web app offers CRUD operations for car models and calculates travel time based on distance and speed. The application is built with **API Platform on Symfony 5.4** for the backend, and **Angular 19** for the frontend.

## 🛠️ Prerequisites

Make sure you have the following installed on your machine:

- **Git**: `https://git-scm.com/downloads`
- **PHP 8.x** and **Composer**: `https://getcomposer.org/download/`
- **Symfony CLI** (optional but recommended): `https://symfony.com/download`
- **Node.js 18+** and **npm**: `https://nodejs.org/`
- **Angular CLI**: `npm install -g @angular/cli`

---

## 🚀 Installation and Running the Project

### 1️⃣ Clone the Project

```sh
git clone https://github.com/elmehdizouihar/car-crud-time-calculator/car-crud-time-calculator.git
cd car-crud-time-calculator

### 2️⃣ Backend Setup

1. Navigate to the `backend` folder:

   ```sh
   cd backend

### Install PHP dependencies using Composer

1. **Install Composer** (if it's not already installed):
   
   Composer is a PHP dependency manager that you need to install before you can install the project dependencies.

   - Follow the official instructions to install Composer: [Composer Installation Guide](https://getcomposer.org/download/)

2. **Install the project dependencies**:

   Once Composer is installed, navigate to the `backend` folder and run the following command to install the required PHP dependencies for the Symfony project:

   ```sh
   composer install

3. **Create the `.env` file from the `.env.example` file**:

   The project comes with a default environment configuration file named `.env.example`. To set up your environment, copy this file and rename it to `.env`:

   ```sh
   cp .env.example .env

4. **Create the database and tables (if you're using one)**:

   If your project requires a database, you need to create it and set up the necessary tables. Run the following commands:

   1. **Create the database**:

      ```sh
      php bin/console doctrine:database:create
      ```

   2. **Run the migrations to create the tables**:

      ```sh
      php bin/console doctrine:migrations:migrate
      ```

5. **Run the Symfony development server**:

   To start the Symfony development server, use the following command:

   ```sh
   symfony serve

> **Note**: This will launch the Symfony development server in `http://localhost:8000`.
