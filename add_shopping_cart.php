<?php
// Include the classes
include 'cart.php';
// Start or resume a session
session_start();



// Initialize ShoppingCart object in the session if it's not set
if (!isset($_SESSION['shopping_cart'])) {
    $_SESSION['shopping_cart'] = new ShoppingCart();
}

// Check if the add to cart button has been submitted
if (isset($_POST['add_to_cart'])) {
    $recordID = $_POST['recordID']; 
    $quantity = $_POST['quantity'];

   // Database credentials
   require_once("recordplayerdb_conn.php");

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT title, price FROM record WHERE recordID = ?");
    $stmt->bind_param("i", $recordID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the title and price from the database
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $price = $row['price'];

        // Create a new Product object
        $product = new Product($recordID, $title, $price, $quantity);

        // Add the product to the cart
        $_SESSION['shopping_cart']->addProduct($product);
    } else {
        // Handle the case where the product doesn't exist in the database
        echo "Product with ID $recordID not found.";
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();

    // Redirect back to the product page or shopping cart page
    header("test.php");
    exit();
} else {
    // Redirect to home page or show an error if the form wasn't submitted correctly
    header("Location: index.php");
    exit();
}
?>