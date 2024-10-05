```markdown
# Daily Attendance in PHP

A simple PHP-based web application to manage and track daily student attendance. This application utilizes CSV files for data storage, providing an easy-to-use interface for educators to efficiently handle attendance records.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Manage Students](#manage-students)
  - [Mark Attendance](#mark-attendance)
  - [View Attendance](#view-attendance)
- [Dependencies](#dependencies)
- [File Structure](#file-structure)
- [Data Persistence](#data-persistence)
- [Input Validation](#input-validation)
- [Extensibility](#extensibility)
- [License](#license)

## Features

- **Manage Students**: Add and view students with unique IDs.
- **Mark Attendance**: Mark students as present or absent for a selected date.
- **View Attendance**: View attendance records by date or by individual student.
- **Data Persistence**: Stores data in CSV files (`students.csv` and `attendance.csv`).
- **User-Friendly Interface**: Intuitive web interface built with HTML, CSS, and minimal JavaScript.
- **Input Validation**: Ensures data integrity with checks for duplicate IDs and correct date formats.

## Installation

### Prerequisites

- **PHP**: Ensure PHP is installed on your system. You can download it from [php.net](https://www.php.net/downloads.php).
- **Web Server**: Use a local web server like [XAMPP](https://www.apachefriends.org/index.html), [WAMP](http://www.wampserver.com/), or [MAMP](https://www.mamp.info/en/) to run the application.

### Steps

1. **Clone the Repository**

   Open your terminal or command prompt and execute:

   ```bash
   git clone https://github.com/yasinULLAH/Daily-attendance-in-PHP.git
   cd Daily-attendance-in-PHP
   ```

2. **Set Up the Web Server**

   - **Using XAMPP (Example):**
     - Place the cloned repository folder (`Daily-attendance-in-PHP`) inside the `htdocs` directory (e.g., `C:\xampp\htdocs\`).
     - Start the Apache server using the XAMPP control panel.

3. **Verify Installation**

   Ensure that PHP is working by accessing `http://localhost` in your web browser. You should see the XAMPP dashboard or your configured homepage.

## Usage

Access the application by navigating to `http://localhost/Daily-attendance-in-PHP/attendance_tracker.php` in your web browser.

### Manage Students

1. **Navigate to the "Manage Students" Section**

   - Click on the **"Manage Students"** tab in the navigation menu.

2. **Add a New Student**

   - Enter a unique **Student ID** and the **Student Name** in the provided input fields.
   - Click the **"Add Student"** button.
   - The student will be added to the `students.csv` file and displayed in the student list below.

3. **View Existing Students**

   - The list below the input form displays all added students with their IDs and names.

   ![Manage Students](screenshots/manage_students.png)

### Mark Attendance

1. **Navigate to the "Mark Attendance" Section**

   - Click on the **"Mark Attendance"** tab in the navigation menu.

2. **Select the Date**

   - Choose the date for which you want to mark attendance using the date picker. The current date is pre-filled by default.

3. **Load Students**

   - Click the **"Load Students"** button to display the list of students.
   - Existing attendance for the selected date will be loaded if available.

4. **Mark Attendance**

   - For each student, select **"Present"** or **"Absent"** from the dropdown menu.
   - After marking, click the **"Save Attendance"** button to record the attendance in the `attendance.csv` file.

   ![Mark Attendance](screenshots/mark_attendance.png)

### View Attendance

1. **Navigate to the "View Attendance" Section**

   - Click on the **"View Attendance"** tab in the navigation menu.

2. **Choose View Option**

   - **View by Date**: See all students' attendance for a specific date.
   - **View by Student**: See a particular student's attendance history.

3. **View by Date**

   - Select the **"View by Date"** radio button.
   - Enter the desired date using the date picker.
   - Click the **"View"** button to display attendance records for that date.

4. **View by Student**

   - Select the **"View by Student"** radio button.
   - Choose a **Student ID** from the dropdown menu.
   - Click the **"View"** button to display the selected student's attendance history.

   ![View Attendance](screenshots/view_attendance.png)

## Dependencies

- **PHP 7.x or higher**
- **Web Server**: Apache, Nginx, or any compatible server.
- **CSV Files**: Utilizes PHP's built-in CSV handling functions.

## File Structure

```
Daily-attendance-in-PHP/
├── attendance_tracker.php
├── students.csv
├── attendance.csv
├── README.md
└── screenshots/
    ├── manage_students.png
    ├── mark_attendance.png
    └── view_attendance.png
```

- **attendance_tracker.php**: Main application script handling all functionalities.
- **students.csv**: Stores student information (ID and Name).
- **attendance.csv**: Stores attendance records (Date, Student ID, Status).
- **README.md**: This readme file.
- **screenshots/**: Directory containing screenshots of the application (optional for visual guidance).

## Data Persistence

- **Students Data (`students.csv`)**:

  | ID    | Name        |
  |-------|-------------|
  | S001  | John Doe    |
  | S002  | Jane Smith  |
  | ...   | ...         |

- **Attendance Data (`attendance.csv`)**:

  | Date       | Student ID | Status  |
  |------------|------------|---------|
  | 2024-04-25 | S001       | Present |
  | 2024-04-25 | S002       | Absent  |
  | ...        | ...        | ...     |

- **File Initialization**: Upon first access, if `students.csv` or `attendance.csv` do not exist, the application creates them with appropriate headers.

## Input Validation

- **Unique Student IDs**: The application checks for duplicate Student IDs to maintain data integrity.
- **Date Format**: Ensures that dates are entered in the `YYYY-MM-DD` format using HTML5 date pickers and server-side validation.
- **Mandatory Fields**: Both Student ID and Name are required when adding a new student.

## Extensibility

This basic application can be extended with additional features such as:

- **Editing Student Details**: Modify existing student information.
- **Deleting Students**: Remove students from the database.
- **Exporting Reports**: Generate and export attendance reports in formats like PDF or Excel.
- **Authentication**: Add user login to secure the application.
- **Advanced UI**: Enhance the interface with more sophisticated designs and functionalities.
- **Database Integration**: Transition from CSV files to a relational database like MySQL for better scalability and concurrency handling.

Feel free to contribute and enhance the application to better suit your needs!

## License

This project is licensed under the [MIT License](LICENSE).

```

---

**Notes:**

1. **Screenshots Directory**: The README references screenshots stored in a `screenshots/` directory. Ensure you add relevant images to this directory in your repository to enhance the visual guidance.

2. **License File**: The README mentions an `LICENSE` file. Make sure to include a `LICENSE` file in your repository, preferably the MIT License as indicated.

3. **Repository Link**: Replace `https://github.com/yasinULLAH/Daily-attendance-in-PHP.git` with your actual repository link if different.

4. **Customization**: Feel free to customize the README further to match any additional features or specific instructions related to your application.

5. **Security Considerations**: Since this application uses CSV files for data storage and lacks authentication, it is suitable for local or single-user environments. For multi-user or production environments, consider implementing proper security measures and using a robust database system.

---

By following this README, users and contributors will have a clear understanding of the project's purpose, setup, and usage, facilitating easier collaboration and deployment.
