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
        <h1>Octo ThaiParts Orders</h1>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Parts Type</th>
                <th>Size</th>
                <th>Instructions</th>
            </tr>
            <?php include '../orders/show_order.php'; ?>
        </table>

        <br />

        <form action="update.html">
            <button type="submit">Update Order</button>
        </form>
        <form action="delete.html">
            <button type="submit">Delete Order</button>
        </form>
        <form action="homepage.html">
            <button type="submit">Back to Main Menu</button>
        </form>
    </div>


</body>

</html>