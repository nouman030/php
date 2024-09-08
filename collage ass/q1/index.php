employee_db
<?php
// Database connection using PDO
$dsn = "mysql:host=localhost;dbname=employee_db;charset=utf8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle insert/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Eno = $_POST['Eno'] ?? null;
    $E_name = $_POST['E_name'];
    $Contact_No = $_POST['Contact_No'];
    $Designation = $_POST['Designation'];
    $Salary = $_POST['Salary'];

    if ($Eno) {
        // Update
        $sql = "UPDATE Emp_details SET E_name = ?, Contact_No = ?, Designation = ?, Salary = ? WHERE Eno = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$E_name, $Contact_No, $Designation, $Salary, $Eno]);
    } else {
        // Insert
        $sql = "INSERT INTO Emp_details (E_name, Contact_No, Designation, Salary) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$E_name, $Contact_No, $Designation, $Salary]);
    }

    header('Location: index.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $Eno = $_GET['delete'];
    $sql = "DELETE FROM Emp_details WHERE Eno = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$Eno]);

    header('Location: index.php');
    exit;
}

// Fetch employees
$sql = "SELECT * FROM Emp_details";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Employee Management</h1>
        
        <!-- Form for Insert/Update -->
        <div class="mb-4">
            <form id="employeeForm" action="index.php" method="POST">
                <input type="hidden" name="Eno" id="Eno">
                <div class="form-group">
                    <label for="E_name">Employee Name:</label>
                    <input type="text" class="form-control" id="E_name" name="E_name" required>
                </div>
                <div class="form-group">
                    <label for="Contact_No">Contact Number:</label>
                    <input type="text" class="form-control" id="Contact_No" name="Contact_No" required>
                </div>
                <div class="form-group">
                    <label for="Designation">Designation:</label>
                    <input type="text" class="form-control" id="Designation" name="Designation" required>
                </div>
                <div class="form-group">
                    <label for="Salary">Salary:</label>
                    <input type="number" class="form-control" id="Salary" name="Salary" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>

        <!-- Display Employees -->
        <h2 class="mb-4">Employee List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee No</th>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Designation</th>
                    <th>Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['Eno']}</td>
                        <td>{$row['E_name']}</td>
                        <td>{$row['Contact_No']}</td>
                        <td>{$row['Designation']}</td>
                        <td>{$row['Salary']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' onclick='editEmployee({$row['Eno']}, \"{$row['E_name']}\", \"{$row['Contact_No']}\", \"{$row['Designation']}\", {$row['Salary']})'>Edit</button>
                            <a href='index.php?delete={$row['Eno']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function editEmployee(Eno, E_name, Contact_No, Designation, Salary) {
            document.getElementById('Eno').value = Eno;
            document.getElementById('E_name').value = E_name;
            document.getElementById('Contact_No').value = Contact_No;
            document.getElementById('Designation').value = Designation;
            document.getElementById('Salary').value = Salary;
        }

        // JavaScript Validation
        document.getElementById('employeeForm').addEventListener('submit', function(event) {
            var contactNo = document.getElementById('Contact_No').value;
            var salary = document.getElementById('Salary').value;

            // Validate contact number (should be digits only)
            if (!/^[0-9]+$/.test(contactNo)) {
                alert('Contact Number must be digits only.');
                event.preventDefault();
            }

            // Validate salary (must be a positive number)
            if (salary <= 0) {
                alert('Salary must be a positive number.');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
