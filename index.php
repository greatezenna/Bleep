<?php

require "render.php";

// Define the products data
$products = [
  ["name" => "Product 1", "description" => "This is product 1", "price" => 10],
  ["name" => "Product 2", "description" => "This is product 2", "price" => 20],
  ["name" => "Product 3", "description" => "This is product 3", "price" => 30],
];

// Define the page data
$data = [
  "title" => "Online Product Store",
  "products" => $products,
];

// Render the page using the provided `render()` function
echo render("store", $data);
