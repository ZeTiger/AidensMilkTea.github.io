<?php
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['add_product'])) {
    $product_category = $_POST['product_category'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_small_price = isset($_POST['product_small_price']) ? $_POST['product_small_price'] : null;
    $product_medium_price = isset($_POST['product_medium_price']) ? $_POST['product_medium_price'] : null;
    $product_large_price = isset($_POST['product_large_price']) ? $_POST['product_large_price'] : null;
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'img/';
    $product_code = $_POST['product_code'];
    $product_availability = isset($_POST['product_availability']) && $_POST['product_availability'] == 'Unavailable' ? 1 : 0;

    $errors = [];

    // Generate a unique filename
    $unique_filename = $product_name . '.png';
    $product_image_folder .= $unique_filename;

    if (empty($product_category) || empty($product_name) || empty($product_image)) {
        $errors[] = 'Please fill out all required fields!';
    }

    // Ensure empty fields are not treated as 0
    if ((!empty($product_price) && $product_price < 0) || 
        (!empty($product_small_price) && $product_small_price < 0) || 
        (!empty($product_medium_price) && $product_medium_price < 0) || 
        (!empty($product_large_price) && $product_large_price < 0)) {
        $errors[] = 'Negative values are not allowed in price fields.';
    }

    if (empty($errors)) {
        // Determine the table based on the category
        $table_name = '';
        $insert_columns = '';
        $insert_values = '';

        switch ($product_category) {
            case 'Milktea':
                $table_name = 'milktea';
                $insert_columns = '(category, name, small, medium, large, image, code, availability)';
                $insert_values = "('$product_category', '$product_name', '$product_small_price', '$product_medium_price', '$product_large_price', '$product_image', '$product_code', '$product_availability')";
                break;
            case 'Fruit Tea':
                $table_name = 'fruit_tea';
                $insert_columns = '(category, name, small, medium, large, image, code, availability)';
                $insert_values = "('$product_category', '$product_name', '$product_small_price', '$product_medium_price', '$product_large_price', '$product_image', '$product_code', '$product_availability')";
                break;
            case 'Infusion Fruit Tea':
                $table_name = 'inf_fruit_tea';
                $insert_columns = '(category, name, small, medium, large, image, code, availability)';
                $insert_values = "('$product_category', '$product_name', '$product_small_price', '$product_medium_price', '$product_large_price', '$product_image', '$product_code', '$product_availability')";
                break;
            case 'Snacks':
                $table_name = 'snacks';
                $insert_columns = '(category, name, price, image, code, availability)';
                $insert_values = "('$product_category', '$product_name', '$product_price', '$product_image', '$product_code', '$product_availability')";
                break;
            default:
                $errors[] = 'Invalid Product Category!';
                break;
        }

        if ($table_name != '') {
            // Construct the insert query
            $insert = "INSERT INTO $table_name $insert_columns VALUES $insert_values";

            $upload = mysqli_query($conn, $insert);
            if ($upload) {
                move_uploaded_file($product_image_tmp_name, $product_image_folder);
                $message[] = 'Product Added Successfully!';
            } else {
                $message[] = 'Unable to add product: ' . mysqli_error($conn);
            }
        }
    } else {
        foreach ($errors as $error) {
            $message[] = $error;
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $product_category = $_GET['category'];
    $table_name = '';

    switch ($product_category) {
        case 'Milktea':
            $table_name = 'milktea';
            break;
        case 'Fruit Tea':
            $table_name = 'fruit_tea';
            break;
        case 'Infusion Fruit Tea':
            $table_name = 'inf_fruit_tea';
            break;
        case 'Snacks':
            $table_name = 'snacks';
            break;
        default:
            $message[] = 'Invalid Product Category!';
            break;
    }

    if ($table_name != '') {
        $delete_query = "DELETE FROM $table_name WHERE id = $id";
        if (mysqli_query($conn, $delete_query)) {
            header('Location: product_admin.php');
            exit();
        } else {
            $message[] = 'Error deleting product: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
<title>Products</title>
<style>
:root{
--bg-color: #eee;
--box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
--border: .1rem solid var(--black);
}

*{
font-family: 'Poppins', sans-serif;
margin:0; padding:0;
outline: none;
text-decoration: none;
text-transform: capitalize;
}
html{
font-size: 62.5%;
overflow-x: hidden;
}

.btn{
display: block;
width: 100%;
cursor: pointer;
border-radius: .5rem;
margin-top: 1rem;
font-size: 1.7rem;
padding: 1rem 3rem;
background: purple;
color: white;
text-align: center;
}

.btn:hover{
background: black;
}
.message{
display: block;
background: #2c1c5d;
padding: 1.5rem 1rem;
font-size: 2rem;
color: white;
margin-bottom: 2rem;
text-align: center;
}
body {
background-color: #2c1c5d;
color: #fff;
margin: 0;
padding: 0;
}
.container {
display: flex;
max-width: 1200px;
margin: 0 auto;
}

.admin-product-form-container form{
max-width: 100rem;
margin: 20px 300px;
padding: 2rem 5rem;
border-radius: .5rem;
background: var(--bg-color);
color: black;
}

.admin-product-form-container form h3{
text-transform: uppercase;
color: black;
margin-bottom: 1rem;
text-align: center;
font-size: 2.5rem;
}

.admin-product-form-container form .box{
width: 100%;
border-radius: .5rem;
padding: 1rem 1.2rem;
font-size: 1.7rem;
margin: 1rem 0;
text-transform: none;
}

.admin-product-form-container form select,
.admin-product-form-container form input[type="text"],
.admin-product-form-container form input[type="number"],
.admin-product-form-container form input[type="file"] {
display: block;
width: 100%;
border-radius: .5rem;
padding: 1rem -1.6rem;
font-size: 1.7rem;
margin: 1rem -1.3rem;
text-transform: none;
}

.admin-product-form-container form label {
display: block;
font-size: 1.7rem;
}

.admin-product-form-container form .box input[type="checkbox"] {
display: inline-block;
margin-right: 10px; /* Adjust as needed for spacing */
}

.admin-product-form-container form .box label {
display: inline-block;
margin-right: 20px; /* Adjust as needed for spacing */
}


.product-display{
margin:2rem 0;
}

.product-display .product-display-table{
width:100%;
text-align: center;
}
.product-display .product-display-table thead{
font-size: 2rem;
background-color: purple;
}

.product-display .product-display-table th{
padding: 1rem;
font-size: 2rem;
}

.product-display .product-display-table td{
padding: 1rem;
font-size: 2rem;
border-bottom: var(--border);
}

.product-display .product-display-table .btn{
margin-top: 0;
width: auto;
font-size: 1.4rem;
}

.product-display .product-display-table img{
height: 10rem;
width: 10rem;
object-fit: contain;
}
.product-display .product-display-table .btn:first-child{
margin-top:0;
}
.product-display .product-display-table .btn:last-child{
background: plum;
}
.product-display .product-display-table .btn:last-child:hover{
background:black;
}

.sidebar {
background-color: #65008f;
width: 15%;
padding: 20px;
height: 100vh;
}
.sidebar .profile {
display: flex;
align-items: center;
margin-bottom: 20px;
}
.sidebar .profile img {
border-radius: 50%;
margin-right: 10px;
}
.sidebar ul {
list-style: none;
padding: 0;
}
.sidebar ul li {
margin: 25px 0;
}
.sidebar ul li a {
color: #fff;
text-decoration: none;
font-size: 18px;
}

@media (max-width:991px){
html{
font-size: 55%;
}
.product-display .product-display-table{
width:80rem;
}
}

@media (max-width:768px){
.product-display{
overflow-y: scroll;
}
.product-display .product-display-table{
width:80rem;
}
}

@media (max-width:450px){
html{
font-size: 50%;
}
}


</style>

</head>
<body>
<?php
if (isset($message)) {
foreach ($message as $msg) {
echo '<span class="message">' . $msg . '</span>';
}
}
?>
<div class="container">
<div class="sidebar">
<div class="profile">
<img src="img/logo_2-removebg-preview (1).png" alt="Profile Image" width="100%">
</div>
<ul>
<li><a href="ordersummary.php"><i class='bx bx-cart'></i> Orders</a></li>
<li><a href="product_admin.php"><i class='bx bx-plus'></i>Product</a></li>
<li><a href="report.php"><i class='bx bx-chart'></i> Dashboard</a></li>
<li><a href="report.php"><i class='bx bx-chart'></i>Report</a></li>
<li><a href="adminmodule.php"><i class='bx bx-cog'></i> Users</a></li>
<li><a href="logout.php"><i class='bx bx-exit'></i> Logout</a></li>
</ul>
</div>
<div class="admin-product-form-container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="productForm" onsubmit="return validateForm()">
<h3>Add New Product</h3>
<label for="product_category">Category:<span style="color: red;"></span></label>
<select name="product_category" id="product_category">
<option value="" disabled selected>Select a Category</option>
<option value="Milktea">Milktea</option>
<option value="Fruit Tea">Fruit Tea</option>
<option value="Infusion Fruit Tea">Infusion Fruit Tea</option>
<option value="Snacks">Snacks</option>
</select>
<input type="text" placeholder="Enter Product Name" name="product_name" class="box">
<input type="file" accept="image/png" name="product_image" class="box">

<!-- Conditional price inputs based on category -->
<div id="price_fields" style="display: none;">
<label for="product_small_price">Small Price:</label>
<input type="number" placeholder="Enter Small Price" name="product_small_price" class="box">

<label for="product_medium_price">Medium Price:</label>
<input type="number" placeholder="Enter Medium Price" name="product_medium_price" class="box">

<label for="product_large_price">Large Price:</label>
<input type="number" placeholder="Enter Large Price" name="product_large_price" class="box">
</div>

<input type="number" placeholder="Enter Product Price" name="product_price" class="box" id="default_price_input">
<input type="text" placeholder="Enter Product Code" name="product_code" class="box">
<div class="box">
<label>Product Availability:</label><br>
<input type="checkbox" id="product_available" name="product_availability" value="Available">
<label for="product_available">Available</label>
<input type="checkbox" id="product_unavailable" name="product_availability" value="Unavailable">
<label for="product_unavailable">Unavailable</label>
</div>


<script>
// JavaScript to toggle checkboxes
document.addEventListener('DOMContentLoaded', function() {
var availableCheckbox = document.getElementById('product_available');
var unavailableCheckbox = document.getElementById('product_unavailable');

// Check if one is already checked
if (availableCheckbox.checked) {
unavailableCheckbox.checked = false; // Uncheck the other checkbox
} else if (unavailableCheckbox.checked) {
availableCheckbox.checked = false; // Uncheck the other checkbox
}


});
</script>

<script>




document.addEventListener('DOMContentLoaded', function() {
    // JavaScript form validation
    document.querySelector('form').addEventListener('submit', function(event) {
        var smallPrice = document.forms["productForm"]["product_small_price"].value;
        var mediumPrice = document.forms["productForm"]["product_medium_price"].value;
        var largePrice = document.forms["productForm"]["product_large_price"].value;
        var price = document.forms["productForm"]["product_price"].value;

        if ((smallPrice !== "" && smallPrice < 0) || 
            (mediumPrice !== "" && mediumPrice < 0) || 
            (largePrice !== "" && largePrice < 0) || 
            (price !== "" && price < 0)) {
            showModal("Negative values are not allowed in price fields.");
            event.preventDefault();
        }
       
    });
    });
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('product_category').addEventListener('change', function() {
        var category = this.value;
        var priceFields = document.getElementById('price_fields');
        var defaultPriceInput = document.getElementById('default_price_input');
        if (category === 'Milktea' || category === 'Fruit Tea' || category === 'Infusion Fruit Tea') {
            priceFields.style.display = 'block';
            defaultPriceInput.style.display = 'none';
        } else {
            priceFields.style.display = 'none';
            defaultPriceInput.style.display = 'block';
        }
    });

    var availableCheckbox = document.getElementById('product_available');
    var unavailableCheckbox = document.getElementById('product_unavailable');

    availableCheckbox.addEventListener('change', function() {
        if (this.checked) {
            unavailableCheckbox.checked = false;
        }
    });

    unavailableCheckbox.addEventListener('change', function() {
        if (this.checked) {
            availableCheckbox.checked = false;
        }
    });
});
</script>


<input type="submit" class="btn" name="add_product" value="Add Product">
</form>
</div>
</div>
<?php
$product_category = isset($_GET['category']) ? $_GET['category'] : 'Snacks';
$table_name = '';

if ($product_category == 'Milktea') {
$table_name = 'milktea';
} elseif ($product_category == 'Fruit Tea') {
$table_name = 'fruit_tea';
} elseif ($product_category == 'Infusion Fruit Tea') {
$table_name = 'inf_fruit_tea';
} elseif ($product_category == 'Snacks') {
$table_name = 'snacks';
}

$select = mysqli_query($conn, "SELECT * FROM $table_name");
?>
<div class="product-display">
<table class="product-display-table">
<thead>
<tr>
<th>Product Category</th>
<th>Product Name</th>
<th>Small Price</th>
<th>Medium Price</th>
<th>Large Price</th>
<th>Price</th>
<th>Product Image</th>
<th>Product Code</th>
<th>Product Availability</th>
<th>Action</th>
</tr>
</thead>
<?php
// Fetch products from all categories
$all_categories = ['milktea', 'fruit_tea', 'inf_fruit_tea', 'snacks'];
foreach ($all_categories as $category_table) {
$select_products = mysqli_query($conn, "SELECT * FROM $category_table");
if (mysqli_num_rows($select_products) > 0) {
while ($row = mysqli_fetch_assoc($select_products)) {
?>
<tr>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo isset($row['small']) ? $row['small'] : 'N/A'; ?></td>
<td><?php echo isset($row['medium']) ? $row['medium'] : 'N/A'; ?></td>
<td><?php echo isset($row['large']) ? $row['large'] : 'N/A'; ?></td>
<td><?php echo isset($row['price']) ? $row['price'] : 'N/A'; ?></td>
<td><img src="img/<?php echo $row['name']; ?>.png" height="100" alt=""></td>
<td><?php echo $row['code']; ?></td>
<td><?php echo $row['availability'] == 0 ? 'Available' : 'Unavailable'; ?></td>
<td>
<a href="productUpdate_admin.php?edit=<?php echo $row['id']; ?>&category=<?php echo $row['category']; ?>" class="btn"> Edit </a>
<a href="product_admin.php?delete=<?php echo $row['id']; ?>&category=<?php echo $row['category']; ?>" class="btn">Delete</a>
</td>
</tr>
<?php
}
}
}
?>
</table>
</div>
</body>
</html>