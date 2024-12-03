<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OctoThaiParts</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <?php
        session_start();
        include "../database/database.php"; // Include your database connection file

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $First_Name = $_POST["fName"];
            $Last_Name = $_POST["lName"];
            $Email = $_POST["email"];
            $Password = $_POST["password"];

            try {
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Check if the username already exists
                $checkStmt = $conn->prepare("SELECT * FROM users WHERE Email = :email");
                $checkStmt->bindParam(':email', $Email);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    echo "Email already exists!";
                } else {
                    // Hash the password for secure storage
                    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

                    $insertStmt = $conn->prepare("INSERT INTO users (First_Name, Last_Name, Email, Password) VALUES (:fName, :lName, :email, :password)");
                    $insertStmt->bindParam(':fName', $First_Name);
                    $insertStmt->bindParam(':lName', $Last_Name);
                    $insertStmt->bindParam(':email', $Email);
                    $insertStmt->bindParam(':password', $hashedPassword);
                    $insertStmt->execute();

                    //$_SESSION['username'] = $username; // Store username in session for later use
                    header("Location: ../pages/login.html"); // Redirect to menu page after successful registration
                    exit();
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
        <form action="../pages/register.html">
            <button type="submit">Back</button>
        </form>
    </div>

</body>

</html>