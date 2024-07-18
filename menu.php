<?php
session_start();
$_SESSION['lastpage'] = "Location: menu.php";

$con = new mysqli('localhost', 'root', '', 'client_registration');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aiden's Menu</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="menu.css">
</head>

<body class="">
<!--header section -->
<header>
<nav class="navbar">
<a href="index.php">Home</a>
<a href="about_us.php">About Us</a>
<a class="active" href="menu.php">Menu</a>
<a href="location.php">Location</a>
<a href="contact_us.php">Contact Us</a>
</nav>

<div class="icons">
<i class="fas fa-bars" id="menu-bars"></i>
<a class="fas fa-shopping-cart">
<span id="cart-count">0</span>
</a>
</div>
</header>
<!--header section end -->

<!-- Milk Tea Section -->
<section class="milktea" id="milktea">
<h1 class="heading">Milk Tea Series</h1>
<div class="box-container">
<?php
$result = $con->query("SELECT * FROM milktea");
if ($result) {
while ($row = $result->fetch_assoc()) {
$name = $row['name'];
$small = $row['small'];
$medium = $row['medium'];
$large = $row['large'];
$avail = $row['availability'];

if ($avail == 0){
echo "<div class='box'>";
echo "<img style='width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $small</span>";
echo " <div class='size-buttons'>";
echo " <a href='#' class='btn-s' style='background-color:#912ec2;' data-price='$small'>S</a>";
echo " <a href='#' class='btn-s' data-price='$medium'>M</a>";
echo " <a href='#' class='btn-s' data-price='$large'>L</a>";
echo " </div>";
echo " <a href='#' class='btn addToCart'>Add to Cart</a>";
echo "</div>";
}
else{
echo "<div class='box'>";
echo "<img style='opacity: 60%; width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $small</span>";
echo " <div class='size-buttons'>";
echo " <a href='#' class='btn-s' style='background-color:#912ec2;' data-price='$small'>S</a>";
echo " <a href='#' class='btn-s' data-price='$medium'>M</a>";
echo " <a href='#' class='btn-s' data-price='$large'>L</a>";
echo " </div>";
echo " <a href='#' class='btn addToCart' style='pointer-events: none; background-color: #55465c;'>Unavailable</a>";
echo "</div>";
}
}
$result->free_result();

} else {
echo "Error: " . $con->error;
}
?>
</div>
</section>

<!-- Fruit Tea Section -->
<section class="milktea" id="fruittea">
<h1 class="heading">Fruit Tea Series</h1>
<div class="box-container">
<?php
$result = $con->query("SELECT * FROM fruit_tea");
if ($result) {
while ($row = $result->fetch_assoc()) {
$name = $row['name'];
$small = $row['small'];
$medium = $row['medium'];
$large = $row['large'];
$avail = $row['availability'];

if ($avail == 0){
echo "<div class='box'>";
echo "<img style='width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $small</span>";
echo " <div class='size-buttons'>";
echo " <a href='#' class='btn-s' style='background-color:#912ec2;' data-price='$small'>S</a>";
echo " <a href='#' class='btn-s' data-price='$medium'>M</a>";
echo " <a href='#' class='btn-s' data-price='$large'>L</a>";
echo " </div>";
echo " <a href='#' class='btn addToCart'>Add to Cart</a>";
echo "</div>";
}
else{
echo "<div class='box'>";
echo "<img style='opacity: 60%; width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $small</span>";
echo " <div class='size-buttons'>";
echo " <a href='#' class='btn-s' style='background-color:#912ec2;' data-price='$small'>S</a>";
echo " <a href='#' class='btn-s' data-price='$medium'>M</a>";
echo " <a href='#' class='btn-s' data-price='$large'>L</a>";
echo " </div>";
echo " <a href='#' class='btn addToCart' style='pointer-events: none; background-color: #55465c;'>Unavailable</a>";
echo "</div>";
}
}
$result->free_result();
} else {
echo "Error: " . $con->error;
}
?>
</div>
</section>

<!-- Infusion Fruit Tea Section -->
<section class="milktea" id="infusion-fruit-tea">
<h1 class="heading">Infusion Fruit Tea Series</h1>
<div class="box-container">
<?php
$result = $con->query("SELECT * FROM inf_fruit_tea");
if ($result) {
while ($row = $result->fetch_assoc()) {
$name = $row['name'];
$small = $row['small'];
$medium = $row['medium'];
$large = $row['large'];
$avail = $row['availability'];

if ($avail == 0){
echo "<div class='box'>";
echo "<img style='width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $small</span>";
echo " <div class='size-buttons'>";
echo " <a href='#' class='btn-s' style='background-color:#912ec2;' data-price='$small'>S</a>";
echo " <a href='#' class='btn-s' data-price='$medium'>M</a>";
echo " <a href='#' class='btn-s' data-price='$large'>L</a>";
echo " </div>";
echo " <a href='#' class='btn addToCart'>Add to Cart</a>";
echo "</div>";
}
else{
echo "<div class='box'>";
echo "<img style='opacity: 60%; width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $small</span>";
echo " <div class='size-buttons'>";
echo " <a href='#' class='btn-s' style='background-color:#912ec2;' data-price='$small'>S</a>";
echo " <a href='#' class='btn-s' data-price='$medium'>M</a>";
echo " <a href='#' class='btn-s' data-price='$large'>L</a>";
echo " </div>";
echo " <a href='#' class='btn addToCart' style='pointer-events: none; background-color: #55465c;'>Unavailable</a>";
echo "</div>";
}
}
$result->free_result();
} else {
echo "Error: " . $con->error;
}
?>
</div>
</section>

<!-- Snacks Section -->
<section class="milktea" id="snacks">
<h1 class="heading">Snacks and Pastries</h1>
<div class="box-container">
<?php
$result = $con->query("SELECT * FROM snacks");
if ($result) {
while ($row = $result->fetch_assoc()) {
$name = $row['name'];
$price = $row['price'];

$avail = $row['availability'];

if ($avail == 0){
echo "<div class='box'>";
echo "<img style='width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $price</span>";
echo " <div><a href='#' class='btn addToCart'>Add to Cart</a></div>";
echo "</div>";
}
else{
echo "<div class='box'>";
echo "<img style='opacity: 60%; width:140px;' src='img/" . strtolower($name) . ".png' alt=''>";
echo " <h3>$name</h3>";
echo " <div class='stars'>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star'></i>";
echo " <i class='fas fa-star-half-alt'></i>";
echo " </div>";
echo " <span class='price'>₱ $price</span>";
echo " <div><a href='#' class='btn addToCart' style='pointer-events: none; background-color: #55465c;'>Unavailable</a></div>";
echo "</div>";
}
}
$result->free_result();
} else {
echo "Error: " . $con->error;
}

$con->close();
?>
</div>
</section>

<div id="modal" class="modal">
<div class="modal-content">
<p id="modal-message">Added to cart!</p>
</div>
</div>

<!-- Shopping Cart -->
<div class="cartTab">
<h1>Shopping Cart</h1>
<div class="listCart">

</div>
<div class="foot">
<h3>Total</h3>
<h2 id= "total">Php 0.00</h2>
</div>
<div class="cartbtn">
<button class="clearCart">CLEAR CART</button>
<button class="close">CLOSE</button>
<button class="checkOut">CHECK OUT</button>
</div>
</div>

<!-- Footer -->
<div class="footer">
<p>&copy; 2024 Aiden's Milk Tea House. All rights reserved.</p>
<p>
<a href="#">About</a> |
<a href="#">Menu</a> |
<a href="#">Contact Us</a>
</p>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {

const checkoutBtn = document.querySelector('.checkOut');
checkoutBtn.addEventListener('click', async () => {
const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
if (cart.length > 0) {
try {
const response = await fetch('save_cart.php', {
method: 'POST',
headers: {
'Content-Type': 'application/json',
},
body: JSON.stringify({ cart: cart })
});
const data = await response.json();
if (data.success) {
window.location.href = 'checkout.php';
} else {
alert('There was an error processing your request. Please try again.');
}
} catch (error) {
console.error(error);
}
} else {
alert('Your cart is empty. Add items to proceed to checkout.');
}
});
// Toggle menu
let menu = document.querySelector('#menu-bars');
let navbar = document.querySelector('.navbar');

menu.onclick = () => {
menu.classList.toggle('fa-times');
navbar.classList.toggle('active');
};

// Handle size selection and add to cart functionality
const boxes = document.querySelectorAll('.box');

boxes.forEach(box => {
const sizeButtons = box.querySelectorAll('.size-buttons a');
const priceSpan = box.querySelector('.price');

sizeButtons.forEach(button => {
button.addEventListener('click', (event) => {
event.preventDefault();

const previouslySelected = box.querySelector('.size-selected');
if (previouslySelected) {
previouslySelected.classList.remove('size-selected');
}

// Reset all button colors
sizeButtons.forEach(otherButton => {
otherButton.style.backgroundColor = null;
otherButton.classList.remove('size-selected'); // Remove previously selected class (optional)
});

priceSpan.textContent = `₱ ${button.dataset.price}`;
button.classList.add('size-selected');
button.style.backgroundColor = '#912ec2';
});
});

function updateCartCount() {
const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
let totalItems = 0;

cart.forEach(item => {
totalItems += item.quantity;
});

const cartCount = document.getElementById('cart-count');
if (cartCount) {
cartCount.textContent = totalItems.toString();
}
}


// Initial call to update cart count on page load
updateCartCount();

// Add to cart button
const addToCartBtn = box.querySelector('.addToCart');
addToCartBtn.addEventListener('click', function(event) {
event.preventDefault();

if (!<?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>) {
        window.location.href = 'login.php';
        return;
    }

const itemName = box.querySelector('h3').textContent.trim();
const itemPrice = parseFloat(priceSpan.textContent.replace('₱ ', ''));
const itemSize = box.querySelector('.size-selected')?.textContent.trim() || 'S';
const itemQuantity = 1;

let cart = JSON.parse(sessionStorage.getItem('cart')) || [];

const existingItem = cart.find(item => item.name === itemName && item.size === itemSize);

if (existingItem) {
existingItem.quantity++;
} else {
cart.push({ name: itemName, price: itemPrice, size: itemSize, quantity: itemQuantity });
}

sessionStorage.setItem('cart', JSON.stringify(cart));
updateCartDisplay();
showModal();
});
});


// Update cart display function
function updateCartDisplay() {
const listCart = document.querySelector('.listCart');
listCart.innerHTML = '';

const cart = JSON.parse(sessionStorage.getItem('cart')) || [];

let totalItems = 0; // Variable to calculate total items in cart

cart.forEach((item, index) => {
const newItem = document.createElement('div');
newItem.classList.add('item');

const itemImage = document.createElement('img');
itemImage.src = `img/${item.name.toLowerCase()}.png`;
newItem.appendChild(itemImage);

const itemNameEl = document.createElement('div');
itemNameEl.classList.add('name');
itemNameEl.textContent = `${item.name} ${item.size}`;
newItem.appendChild(itemNameEl);

const totalPriceEl = document.createElement('div');
totalPriceEl.classList.add('totalPrice');
totalPriceEl.textContent = `Php ${(item.quantity * item.price).toFixed(2)}`;
newItem.appendChild(totalPriceEl);

const quantityEl = document.createElement('div');
quantityEl.classList.add('quantity');
quantityEl.innerHTML = `
<span class="minus" data-index="${index}">-</span>
<span class="quantity-value">${item.quantity}</span>
<span class="plus" data-index="${index}">+</span>
`;
newItem.appendChild(quantityEl);

listCart.appendChild(newItem);

totalItems += item.quantity; // Increment totalItems with current item's quantity
});

// Update the cart count in the header
const cartCount = document.getElementById('cart-count');
if (cartCount) {
cartCount.textContent = totalItems.toString();
}

calculateTotal(cart);

// Event delegation for quantity buttons
listCart.addEventListener('click', event => {
if (event.target.classList.contains('minus')) {
const itemIndex = event.target.getAttribute('data-index');
if (itemIndex !== null) {
cart[itemIndex].quantity--;
if (cart[itemIndex].quantity < 1) {
cart.splice(itemIndex, 1); 
}
sessionStorage.setItem('cart', JSON.stringify(cart));
updateCartDisplay();
}
}

if (event.target.classList.contains('plus')) {
const itemIndex = event.target.getAttribute('data-index');
if (itemIndex !== null) {
cart[itemIndex].quantity++;
sessionStorage.setItem('cart', JSON.stringify(cart));
updateCartDisplay();
}
}
});
}

// Calculate total function
function calculateTotal(cart) {
let total = 0;
cart.forEach(item => {
total += item.quantity * item.price;
});

let totalElement = document.getElementById('total');
if (totalElement) {
totalElement.textContent = `Php ${total.toFixed(2)}`;
}
}

// Toggle cart display
const iconCart = document.querySelector('.fa-shopping-cart');
const body = document.querySelector('body');
const closeCart = document.querySelector('.close');
const clearCartBtn = document.querySelector('.clearCart');

iconCart.addEventListener('click', () => {
body.classList.toggle('showCart');
updateCartDisplay();
});

closeCart.addEventListener('click', () => {
body.classList.toggle('showCart');
});

clearCartBtn.addEventListener('click', () => {
sessionStorage.removeItem('cart');
updateCartDisplay();
});

function showModal() {
const modal = document.getElementById('modal');
const modalMessage = document.getElementById('modal-message');

modal.style.display = 'block';
modalMessage.textContent = `Added to cart!`;
setTimeout(() => {
modal.style.opacity = '0';
setTimeout(() => {
modal.style.display = 'none';
modal.style.opacity = '1'; // Reset opacity for future displays
}, 500); // Wait for 1 second after fading out
}, 1000); // Show modal for 2 seconds
}
});

// Checkout button event listener
const checkoutBtn = document.querySelector('.checkOut');
checkoutBtn.addEventListener('click', () => {

    if (!<?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>) {
        window.location.href = 'login.php';
        return;
    }

const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
if (cart.length > 0) {
sessionStorage.setItem('cart', JSON.stringify(cart)); // Store cart data in session storage
window.location.href = 'checkout.php'; // Redirect to checkout page
} else {

}
});
</script>
<div id="orderSentModal" class="modal">
    <div class="modal-content">
        <h2 id="modal-message">Order Sent!</h2>
        <p id="modal-caption">Please wait for the order confirmation in your email.</p>
    </div>
</div>
</body>

</html>
