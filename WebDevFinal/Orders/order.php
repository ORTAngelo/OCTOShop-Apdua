<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Octo ThaiParts</title>
    <link rel="stylesheet" href="../Css/styles.css">
</head>

<body>
    <?php
    function displayOrderSummary()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            echo "<div class='summary'>";
            echo "<h2>📝 Order Summary</h2>";

            // Define the prices for different coffee types, sizes, and extras
            $parts_prices = [
                "Tire" => 950,
                "Rims" => 1300,
                "Mags" => 5350,
                "Seat" => 2200,
                "Suspension" => 3400,
            ];

            $name = htmlspecialchars($_POST["name"]);
            $partsType = htmlspecialchars($_POST["parts"]);
            $size = htmlspecialchars($_POST["size"]);
            $instructions = htmlspecialchars($_POST["instructions"]);

            $parts_type = $_POST["parts"];
            $size = $_POST["size"];

            displayOrderDetails($name, $parts_type, $parts_prices, $size);

            // Generate the receipt content based on the order details
            $receiptContent = generateReceiptContent($name, $partsType, $parts_prices, $size, $instructions);

            // Save the receipt content to a text file
            saveReceiptToFile($receiptContent);

            // Insert order details into the database
            insertOrderToDatabase($name, $partsType, $size, $instructions);

            // Close the order summary section
            echo "</div>";
        }
    }

    /**
     * Displays the details of the coffee order in a table format.
     * 
     * @param string $name The name of the customer
     * @param array $coffee_prices The array containing the prices of different coffee types
     * @param array $size_prices The array containing the prices of different sizes
     * @param array $extras_prices The array containing the prices of different extras
     * @param string $coffee_type The type of coffee ordered
     * @param string $size The size of the coffee ordered
     * @param array $extras The selected extras for the coffee
     * @param float $total_price The total price of the order
     */
    function displayOrderDetails($name, $parts_type, $parts_prices, $size)
    {
        echo "<table>";

        // Display the customer's name
        echo "<tr><td>Name:</td><td>" . htmlspecialchars($name) . "</td></tr>";

        // Display the type of coffee ordered along with its price
        echo "<tr><td>Parts Type:</td><td>" . htmlspecialchars($parts_type) . " (₱" . number_format($parts_prices[$parts_type], 2) . ")</td></tr>";

        // Display the size of the coffee ordered along with its price
        echo "<tr><td>Size:</td><td>" . htmlspecialchars($size) . "</td></tr>";

        // Display the total price of the order
        echo "<tr><td>Total Price:</td><td>₱" . number_format($parts_prices[$parts_type], 2) . "</td></tr>";

        // Display any special instructions provided by the customer
        echo "<tr><td>Special Instructions:</td><td>" . htmlspecialchars($_POST["instructions"]) . "</td></tr>";

        echo "</table>";
    }

    /**
     * Generates the content for the receipt based on the provided parameters.
     * 
     * @param string $name The name of the customer
     * @param string $coffeeType The type of coffee ordered
     * @param array $coffee_prices Array containing the prices of different coffee types
     * @param string $size The size of the coffee ordered
     * @param array $size_prices Array containing the prices of different coffee sizes
     * @param array $extras Array containing the selected extras
     * @param array $extras_prices Array containing the prices of different extras
     * @param float $total_price The total price of the order
     * @param string $instructions Any special instructions provided
     * @return string The content of the receipt
     */
    function generateReceiptContent($name, $partsType, $parts_prices, $size, $instructions)
    {
        // Initialize the receipt content with a title and separator
        $receiptContent = "Order Summary\n";
        $receiptContent .= "-----------------\n";

        // Add customer name to the receipt content
        $receiptContent .= "Name: " . $name . "\n";

        // Add coffee type with its price to the receipt content
        $receiptContent .= "Parts Type: " . $partsType . " (₱" . number_format($parts_prices[$partsType], 2) . ")\n";

        // Add coffee size with its price to the receipt content
        $receiptContent .= "Size: " . $size . "\n";

        // Add the total price to the receipt content
        $receiptContent .= "Total Price: ₱" . number_format($parts_prices[$partsType], 2) . "\n";

        // Add any special instructions to the receipt content
        $receiptContent .= "Special Instructions: " . $instructions . "\n";

        // Add a thank you message to the receipt content
        $receiptContent .= "\n";
        $receiptContent .= "Thank you for your order!";

        // Return the complete receipt content
        return $receiptContent;
    }


    /**
     * Saves the receipt content to a text file.
     * 
     * @param string $receiptContent The content of the receipt to be saved
     */
    function saveReceiptToFile($receiptContent)
    {
        // Open a file for writing. If the file does not exist, it will be created.
        // If the file cannot be opened, display an error message and terminate the script.
        $file = fopen("Octo ThaiParts Order Summary.txt", "w") or die("Unable to open file!");

        // Write the receipt content to the file.
        fwrite($file, $receiptContent);

        // Close the file after writing is complete.
        fclose($file);

        // Display a success message indicating that the receipt was created.
        echo "Receipt created successfully as Octo ThaiParts Order Summary.txt!";
    }

    // Call the displayOrderSummary function
    displayOrderSummary();

    function insertOrderToDatabase($name, $partsType, $size, $instructions)
    {

        include "../database/database.php";

        try {
            // Open the database connection
            $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);

            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepares an SQL statement for execution
            $stmt = $conn->prepare("INSERT INTO orders (name, partsType, size, instructions) 
                VALUES (:name, :parts_type, :size, :instructions)");

            // Convert the array into a single string
            //$extras_string = implode(", ", $extras);

            // Bind the value of the variable to the parameter
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':parts_type', $partsType);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':instructions', $instructions);

            // Executes the prepared statement
            $stmt->execute();

            echo "<br /> Order details inserted into the database successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    // Close the database connection
    $conn = null;
    ?>

    <br />

    <form action="../pages/homepage.html">
        <button type="submit">New Order</button>
    </form>
</body>

</html>