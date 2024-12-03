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
            $Email = $_POST["email"];
            $Password = $_POST["password"];

            try {
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Check if the email exists
                $checkStmt = $conn->prepare("SELECT * FROM users WHERE Email = :email");
                $checkStmt->bindParam(':email', $Email);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    $user = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if (password_verify($Password, $user['Password'])) {
                        $_SESSION['user_id'] = $user['id']; // Store user id in session for later use
                        header("Location: ../Pages/homepage.html"); // Redirect to menu page after successful login
                        exit();
                    } else {
                        echo "Invalid Email or Password";
                    }
                } else {
                    echo "Email does not exist!";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
        <form action="../pages/login.html">
            <button type="submit">Back</button>
        </form>
    </div>

</body>

</html>