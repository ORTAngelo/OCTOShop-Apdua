<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Octo ThaiParts</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <?php
        include "../database/database.php";

        // Define arrays for coffee prices, size prices, and extras prices
        $parts_prices = [
            "Tire" => 950,
                "Rims" => 1300,
                "Mags" => 5350,
                "Seat" => 2200,
                "Suspension" => 3400,
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $orderID = $_POST["order_id"];

            try {
                // Open the database connection
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                
                // Set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare and execute the update query
                $stmt = $conn->prepare("SELECT * FROM orders WHERE orderID = :orderID");
                $stmt->bindParam(':orderID', $orderID);
                $stmt->execute();

                // Fetch the orders
                $result = $stmt->fetch();

                if ($result) {
                    // Retrieve existing values
                    $name = isset($_POST["name"]) && $_POST["name"] !== "" ? $_POST["name"] : $result['name'];
                    $partsType = isset($_POST["parts"]) && $_POST["parts"] !== "" ? $_POST["parts"] : $result['parts_type'];
                    $size = isset($_POST["size"]) && $_POST["size"] !== "" ? $_POST["size"] : $result['size'];
                    $instructions = isset($_POST["instructions"])  && $_POST["instructions"] !== "" ? $_POST["instructions"] : $result['instructions'];

                    // Update the order details
                    $updateStmt = $conn->prepare("UPDATE orders SET name=:name, partsType=:partsType, size=:size, instructions=:instructions WHERE orderID=:orderID");
                    $updateStmt->execute(array(
                        ':name' => $name,
                        ':partsType' => $partsType,
                        ':size' => $size,
                        ':instructions' => $instructions,
                        ':orderID' => $orderID
                    ));

                    echo "Order details updated successfully!";
                } else {
                    echo "Order not found. Please check the Order ID and try again.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            // Close the database connection
            $conn = null;
        }
        ?>

        <br />
        <form action="../pages/update.html">
            <button type="submit">Back</button>
        </form>
    </div>
</body>

</html>