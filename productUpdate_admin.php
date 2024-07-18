<?php
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = [];
$product = [
    'id' => '',
    'category' => '',
    'name' => '',
    'small' => '',
    'medium' => '',
    'large' => '',
    'price' => '',
    'image' => '',
    'code' => '',
    'availability' => ''
];

if (isset($_POST['update_product'])) {
    // Capture form data
    $id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    $product_category = $_POST['product_category'];
    $product_name = $_POST['product_name'];
    $product_price = isset($_POST['product_price']) ? floatval($_POST['product_price']) : null;
    $product_small_price = isset($_POST['product_small_price']) ? floatval($_POST['product_small_price']) : null;
    $product_medium_price = isset($_POST['product_medium_price']) ? floatval($_POST['product_medium_price']) : null;
    $product_large_price = isset($_POST['product_large_price']) ? floatval($_POST['product_large_price']) : null;
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'img/' . $product_image;
    $product_code = $_POST['product_code'];
    $product_availability = isset($_POST['product_availability']) && $_POST['product_availability'] == 'Unavailable' ? 0 : 1;

    // Validate form data
    $valid = true;
    
    if (!is_numeric($product_price) || $product_price < 0) {
        $message[] = 'Price must be a non-negative number!';
        $valid = false;
    }
    if (!is_numeric($product_small_price) || $product_small_price < 0) {
        $message[] = 'Small price must be a non-negative number!';
        $valid = false;
    }
    if (!is_numeric($product_medium_price) || $product_medium_price < 0) {
        $message[] = 'Medium price must be a non-negative number!';
        $valid = false;
    }
    if (!is_numeric($product_large_price) || $product_large_price < 0) {
        $message[] = 'Large price must be a non-negative number!';
        $valid = false;
    }

    if ($valid) {
        // Proceed with database update
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
            $update_query = '';
            if ($table_name === 'snacks') {
                $update_query = "UPDATE $table_name SET category = ?, name = ?, price = ?, image = ?, code = ?, availability = ? WHERE id = ?";
            } else {
                $update_query = "UPDATE $table_name SET category=?, name=?, small=?, medium=?, large=?, image=?, code=?, availability=? WHERE id=?";
            }

            $stmt = mysqli_prepare($conn, $update_query);
            if ($stmt) {
                if ($table_name === 'snacks') {
                    mysqli_stmt_bind_param($stmt, 'sssdsii', $product_category, $product_name, $product_price, $product_image, $product_code, $product_availability, $id);
                } else {
                    mysqli_stmt_bind_param($stmt, 'ssdddssii', $product_category, $product_name, $product_small_price, $product_medium_price, $product_large_price, $product_image, $product_code, $product_availability, $id);
                }

                if (mysqli_stmt_execute($stmt)) {
                    move_uploaded_file($product_image_tmp_name, $product_image_folder);
                    $message[] = 'Product Updated Successfully!';
                } else {
                    $message[] = 'Unable to update product: ' . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            } else {
                $message[] = 'Database error: ' . mysqli_error($conn);
            }
        }
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
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
        $query = '';
        if ($table_name === 'snacks') {
            // Query for snacks category
            $query = "SELECT id, category, name, price, image, code, availability FROM $table_name WHERE id = $id";
        } else {
            // Query for milktea, infusion tea, fruit tea categories
            $query = "SELECT id, category, name, small, medium, large, image, code, availability FROM $table_name WHERE id = $id";
        }

        $result = mysqli_query($conn, $query);
        if ($result) {
            $product = mysqli_fetch_assoc($result);
        } else {
            $message[] = 'Error fetching product data: ' . mysqli_error($conn);
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
<title>Update Product</title>
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

.btn1{
display: block;
width: 80%;
cursor: pointer;
border-radius: .5rem;
margin-top: 1rem;
font-size: 1.7rem;
padding: 1rem 3rem;
background: purple;
color: white;
text-align: center;
}

.btn1:hover{
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
margin: 1rem -1.3rem;
}

.admin-product-form-container form .box input[type="checkbox"] {
display: inline-block;
margin-right: 10px; /* Adjust as needed for spacing */
}

.admin-product-form-container form .box label {
display: inline-block;
margin-right: 20px; /* Adjust as needed for spacing */
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
<ul>
<li><a href="ordersummary.php"><i class='bx bx-cart'></i> Orders</a></li>
<li><a href="product_admin.php"><i class='bx bx-plus'></i>Product</a></li>
<li><a href="report.php"><i class='bx bx-chart'></i> Dashboard</a></li>
<li><a href="adminmodule.php"><i class='bx bx-cog'></i> Users</a></li>
<li><a href="logout.php"><i class='bx bx-exit'></i> Logout</a></li>
</ul>
</ul>
</div>

<div class="admin-product-form-container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="update-product-form">
<h3>Update Product</h3>
<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
<label for="product_category">Category:<span style="color: red;"></span></label>
<select name="product_category" id="product_category">
<option value="" disabled>Select a Category</option>
<option value="Milktea" <?php if ($product['category'] == 'Milktea') echo 'selected'; ?>>Milktea</option>
<option value="Fruit Tea" <?php if ($product['category'] == 'Fruit Tea') echo 'selected'; ?>>Fruit Tea</option>
<option value="Infusion Fruit Tea" <?php if ($product['category'] == 'Infusion Fruit Tea') echo 'selected'; ?>>Infusion Fruit Tea</option>
<option value="Snacks" <?php if ($product['category'] == 'Snacks') echo 'selected'; ?>>Snacks</option>
</select>
<label for="product_name">Product Name:<span style="color: red;"></span></label>
<input type="text" id="product_name" name="product_name" value="<?php echo $product['name']; ?>" class="box" required>

<!-- Check the category to determine which fields to display -->
<div id="non-snacks-prices" style="display: <?php echo $product['category'] == 'Snacks' ? 'none' : 'block'; ?>">
<label for="product_small_price">Small Price:<span style="color: red;"></span></label>
<input type="number" step="0.01" id="product_small_price" name="product_small_price" value="<?php echo $product['small']; ?>" class="box" required>
<label for="product_medium_price">Medium Price:<span style="color: red;"></span></label>
<input type="number" step="0.01" id="product_medium_price" name="product_medium_price" value="<?php echo $product['medium']; ?>" class="box" required>
<label for="product_large_price">Large Price:<span style="color: red;"></span></label>
<input type="number" step="0.01" id="product_large_price" name="product_large_price" value="<?php echo $product['large']; ?>" class="box" required>
</div>
<div id="snacks-price" style="display: <?php echo $product['category'] == 'Snacks' ? 'block' : 'none'; ?>">
<label for="product_price">Price:<span style="color: red;"></span></label>
<input type="number" step="0.01" id="product_price" name="product_price" value="<?php echo $product['price']; ?>" class="box" required>
</div>

<label for="product_image">Image:<span style="color: red;"></span></label>
<input type="file" id="product_image" name="product_image" class="box">
<label for="product_code">Code:<span style="color: red;"></span></label>
<input type="text" id="product_code" name="product_code" value="<?php echo $product['code']; ?>" class="box" required>
<div class="box">
<label>Product Availability:</label><br>
<input type="checkbox" id="product_available" name="product_availability" value="Available" <?php if ($product['availability'] == 1) echo 'checked'; ?>>
<label for="product_available">Available</label>
<input type="checkbox" id="product_unavailable" name="product_availability" value="Unavailable" <?php if ($product['availability'] == 0) echo 'checked'; ?>>
<label for="product_unavailable">Unavailable</label>
</div>

<input type="submit" class="btn" name="update_product" value="Update Product">
<a href="product_admin.php" class="btn1">Go Back</a>
</form>
</div>
</div>
<script>
document.getElementById('product_category').addEventListener('change', function() {
var selectedCategory = this.value;
var nonSnacksPrices = document.getElementById('non-snacks-prices');
var snacksPrice = document.getElementById('snacks-price');

if (selectedCategory === 'Snacks') {
nonSnacksPrices.style.display = 'none';
snacksPrice.style.display = 'block';
} else {
nonSnacksPrices.style.display = 'block';
snacksPrice.style.display = 'none';
}
});

// JavaScript to toggle checkboxes
document.addEventListener('DOMContentLoaded', function() {
var availableCheckbox = document.getElementById('product_available');
var unavailableCheckbox = document.getElementById('product_unavailable');

// Check if one is already checked
if (availableCheckbox.checked) {
unavailableCheckbox.checked = false;
} else if (unavailableCheckbox.checked) {
availableCheckbox.checked = false;
}

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
</body>
</html>