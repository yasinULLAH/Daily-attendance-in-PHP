<?php
// attendance_tracker.php

// Define CSV file paths
define('STUDENTS_FILE', 'students.csv');
define('ATTENDANCE_FILE', 'attendance.csv');

// Initialize CSV files if they don't exist
if (!file_exists(STUDENTS_FILE)) {
    $file = fopen(STUDENTS_FILE, 'w');
    fputcsv($file, ['ID', 'Name']);
    fclose($file);
}

if (!file_exists(ATTENDANCE_FILE)) {
    $file = fopen(ATTENDANCE_FILE, 'w');
    fputcsv($file, ['Date', 'Student ID', 'Status']);
    fclose($file);
}

// Function to read students from CSV
function read_students()
{
    $students = [];
    if (($handle = fopen(STUDENTS_FILE, 'r')) !== FALSE) {
        fgetcsv($handle); // Skip header
        while (($data = fgetcsv($handle)) !== FALSE) {
            $students[$data[0]] = $data[1];
        }
        fclose($handle);
    }
    return $students;
}

// Function to read attendance from CSV
function read_attendance()
{
    $attendance = [];
    if (($handle = fopen(ATTENDANCE_FILE, 'r')) !== FALSE) {
        fgetcsv($handle); // Skip header
        while (($data = fgetcsv($handle)) !== FALSE) {
            $attendance[] = [
                'Date' => $data[0],
                'StudentID' => $data[1],
                'Status' => $data[2]
            ];
        }
        fclose($handle);
    }
    return $attendance;
}

// Function to add a new student
function add_student($id, $name)
{
    // Check for duplicate ID
    $students = read_students();
    if (array_key_exists($id, $students)) {
        return "Error: Student ID already exists.";
    }

    // Append to CSV
    $file = fopen(STUDENTS_FILE, 'a');
    fputcsv($file, [$id, $name]);
    fclose($file);
    return "Student added successfully.";
}

// Function to save attendance
function save_attendance($date, $attendance_data)
{
    // Remove existing attendance for the date
    $all_attendance = read_attendance();
    $filtered_attendance = array_filter($all_attendance, function ($record) use ($date) {
        return $record['Date'] !== $date;
    });

    // Add new attendance records
    foreach ($attendance_data as $student_id => $status) {
        $filtered_attendance[] = [
            'Date' => $date,
            'StudentID' => $student_id,
            'Status' => $status
        ];
    }

    // Write back to CSV
    $file = fopen(ATTENDANCE_FILE, 'w');
    fputcsv($file, ['Date', 'Student ID', 'Status']);
    foreach ($filtered_attendance as $record) {
        fputcsv($file, [$record['Date'], $record['StudentID'], $record['Status']]);
    }
    fclose($file);
    return "Attendance saved successfully.";
}

// Handle form submissions
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manage Students
    if (isset($_POST['action']) && $_POST['action'] === 'add_student') {
        $student_id = trim($_POST['student_id']);
        $student_name = trim($_POST['student_name']);
        if ($student_id === '' || $student_name === '') {
            $message = "Please provide both Student ID and Name.";
        } else {
            $result = add_student($student_id, $student_name);
            $message = $result;
        }
    }

    // Mark Attendance
    if (isset($_POST['action']) && $_POST['action'] === 'mark_attendance') {
        $date = trim($_POST['attendance_date']);
        $attendance = isset($_POST['attendance']) ? $_POST['attendance'] : [];
        if ($date === '') {
            $message = "Please provide a date.";
        } else {
            // Validate date format
            if (!DateTime::createFromFormat('Y-m-d', $date)) {
                $message = "Invalid date format. Use YYYY-MM-DD.";
            } else {
                $result = save_attendance($date, $attendance);
                $message = $result;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Attendance Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background-color: #fff;
            margin-top: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        nav {
            margin-bottom: 20px;
            text-align: center;
        }
        nav a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #4287f5;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        nav a.active {
            background-color: #306edc;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }
        .error {
            text-align: center;
            color: red;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom:20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        input[type="text"], input[type="date"] {
            padding: 8px;
            width: 200px;
            margin-right: 10px;
        }
        input[type="submit"], button {
            padding: 8px 16px;
            background-color: #4287f5;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover, button:hover {
            background-color: #306edc;
        }
    </style>
    <script>
        function showSection(section) {
            document.getElementById('manage_students').style.display = 'none';
            document.getElementById('mark_attendance').style.display = 'none';
            document.getElementById('view_attendance').style.display = 'none';
            document.getElementById(section).style.display = 'block';

            var navLinks = document.querySelectorAll('nav a');
            navLinks.forEach(function(link) {
                link.classList.remove('active');
            });
            document.getElementById(section + '_link').classList.add('active');
        }

        window.onload = function() {
            // Show Manage Students by default
            showSection('manage_students');
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Student Attendance Tracker</h2>
        <nav>
            <a href="javascript:void(0);" id="manage_students_link" onclick="showSection('manage_students')">Manage Students</a>
            <a href="javascript:void(0);" id="mark_attendance_link" onclick="showSection('mark_attendance')">Mark Attendance</a>
            <a href="javascript:void(0);" id="view_attendance_link" onclick="showSection('view_attendance')">View Attendance</a>
        </nav>

        <?php if ($message !== ""): ?>
            <?php if (strpos($message, 'Error') === 0): ?>
                <div class="error"><?php echo htmlspecialchars($message); ?></div>
            <?php else: ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Manage Students Section -->
        <div id="manage_students" style="display:none;">
            <h3>Manage Students</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_student">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required>
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>
                <input type="submit" value="Add Student">
            </form>

            <h4>Student List</h4>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                <?php
                $students = read_students();
                foreach ($students as $id => $name) {
                    echo "<tr><td>" . htmlspecialchars($id) . "</td><td>" . htmlspecialchars($name) . "</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Mark Attendance Section -->
        <div id="mark_attendance" style="display:none;">
            <h3>Mark Attendance</h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="mark_attendance">
                <label for="attendance_date">Date:</label>
                <input type="date" id="attendance_date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>
                <input type="submit" value="Load Students">
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_attendance' && $message !== "Attendance saved successfully."):
                $date = trim($_POST['attendance_date']);
                $students = read_students();
                $attendance_records = [];

                // Load existing attendance for the date
                $all_attendance = read_attendance();
                foreach ($all_attendance as $record) {
                    if ($record['Date'] === $date) {
                        $attendance_records[$record['StudentID']] = $record['Status'];
                    }
                }

                ?>
                <h4>Attendance for <?php echo htmlspecialchars($date); ?></h4>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="mark_attendance">
                    <input type="hidden" name="attendance_date" value="<?php echo htmlspecialchars($date); ?>">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                        <?php foreach ($students as $id => $name): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($id); ?></td>
                                <td><?php echo htmlspecialchars($name); ?></td>
                                <td>
                                    <select name="attendance[<?php echo htmlspecialchars($id); ?>]">
                                        <option value="Present" <?php echo (isset($attendance_records[$id]) && $attendance_records[$id] === 'Present') ? 'selected' : ''; ?>>Present</option>
                                        <option value="Absent" <?php echo (isset($attendance_records[$id]) && $attendance_records[$id] === 'Absent') ? 'selected' : ''; ?>>Absent</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <input type="submit" value="Save Attendance">
                </form>
            <?php endif; ?>
        </div>

        <!-- View Attendance Section -->
        <div id="view_attendance" style="display:none;">
            <h3>View Attendance</h3>
            <form method="GET" action="">
                <label for="view_option">View By:</label>
                <select id="view_option" name="view_option" onchange="toggleViewOptions(this.value)">
                    <option value="">--Select--</option>
                    <option value="date">Date</option>
                    <option value="student">Student</option>
                </select>
            </form>

            <div id="view_by_date" style="display:none; margin-top:20px;">
                <form method="GET" action="">
                    <input type="hidden" name="view_option" value="date">
                    <label for="view_date">Select Date:</label>
                    <input type="date" id="view_date" name="view_date" required>
                    <input type="submit" value="View">
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['view_option']) && $_GET['view_option'] === 'date' && isset($_GET['view_date'])):
                    $view_date = trim($_GET['view_date']);
                    if (!DateTime::createFromFormat('Y-m-d', $view_date)) {
                        echo "<div class='error'>Invalid date format.</div>";
                    } else {
                        $attendance = read_attendance();
                        $students = read_students();
                        $records = array_filter($attendance, function($record) use ($view_date) {
                            return $record['Date'] === $view_date;
                        });
                        ?>
                        <h4>Attendance for <?php echo htmlspecialchars($view_date); ?></h4>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['StudentID']); ?></td>
                                    <td><?php echo htmlspecialchars($students[$record['StudentID']] ?? 'Unknown'); ?></td>
                                    <td><?php echo htmlspecialchars($record['Status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php
                    }
                endif;
                ?>
            </div>

            <div id="view_by_student" style="display:none; margin-top:20px;">
                <form method="GET" action="">
                    <input type="hidden" name="view_option" value="student">
                    <label for="student_id_select">Select Student:</label>
                    <select id="student_id_select" name="student_id" required>
                        <option value="">--Select--</option>
                        <?php
                        $students = read_students();
                        foreach ($students as $id => $name) {
                            echo "<option value='" . htmlspecialchars($id) . "'>" . htmlspecialchars($id) . " - " . htmlspecialchars($name) . "</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="View">
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['view_option']) && $_GET['view_option'] === 'student' && isset($_GET['student_id'])):
                    $student_id = trim($_GET['student_id']);
                    $students = read_students();
                    if (!array_key_exists($student_id, $students)) {
                        echo "<div class='error'>Student ID not found.</div>";
                    } else {
                        $attendance = read_attendance();
                        $records = array_filter($attendance, function($record) use ($student_id) {
                            return $record['StudentID'] === $student_id;
                        });
                        ?>
                        <h4>Attendance for <?php echo htmlspecialchars($students[$student_id]); ?> (<?php echo htmlspecialchars($student_id); ?>)</h4>
                        <table>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($record['Date']); ?></td>
                                    <td><?php echo htmlspecialchars($record['Status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php
                    }
                endif;
                ?>
            </div>
        </div>
    </div>

    <script>
        function toggleViewOptions(value) {
            document.getElementById('view_by_date').style.display = 'none';
            document.getElementById('view_by_student').style.display = 'none';
            if (value === 'date') {
                document.getElementById('view_by_date').style.display = 'block';
            } else if (value === 'student') {
                document.getElementById('view_by_student').style.display = 'block';
            }
        }

        // Maintain active tab on page reload
        <?php
        if (isset($_GET['view_option'])) {
            echo "document.getElementById('view_attendance_link').classList.add('active');";
        }
        ?>
    </script>
</body>
</html>
