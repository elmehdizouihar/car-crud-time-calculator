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
git clone https://github.com/elmehdizouihar/car-crud-time-calculator.git
```

### 2️⃣ Backend Setup (symfony)

1. **Navigate to the `backend` folder:**

    ```sh
    cd backend
    ```

2. **Install the project dependencies:**

    Run the following command to install the required PHP dependencies for the Symfony project:

    ```sh
    composer install
    ```

3. **Create the `.env` file:**

    Copy `.env.example` to `.env`:

    ```sh
    cp .env.example .env
    ```

4. **Create the database and tables:**

    1. **Create the database:**

        ```sh
        php bin/console doctrine:database:create
        ```

    2. **Run the migrations to create the tables:**

        ```sh
        php bin/console doctrine:migrations:migrate
        ```

5. **Run the Symfony development server:**

    To start the Symfony development server, use the following command:

    ```sh
    symfony serve
    ```
> **Note**: This will launch the Symfony development server in `http://localhost:8000`.

<br>

### 3️⃣ Frontend Setup (Angular)

1. **Navigate to the frontend folder:**

   ```sh
   cd frontend
   ```

2. **Install Node.js dependencies using npm:**

   ```sh
   npm install
   ```

3. **Run the Angular development server:**

   ```sh
   ng serve
    ```
   Navigate to `http://localhost:4200/` in your browser to view the application.
   
## ℹ️ Read more

For comprehensive Angular documentation and guides, visit the official [Angular documentation](https://angular.dev/).
