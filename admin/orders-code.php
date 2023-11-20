<?php

include('../config/function.php');

if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}

if (!isset($_SESSION['productItemsIds'])) {
    $_SESSION['productItemsIds'] = [];
}


// ============================ Add Item ============================== 
if (isset($_POST['addItem'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    // check available product or not 
    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId' LIMIT 1");
    if ($checkProduct) {
        if (mysqli_num_rows($checkProduct) > 0) {
            $row = mysqli_fetch_assoc($checkProduct);
            if ($row['quantity'] <  $quantity) {
                redirect('order-create.php', 'Only' . $row['quantity'] . 'quantity available');
            }
            $productData = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if (!in_array($row['id'],$_SESSION['productItemsIds'])) {
                array_push($_SESSION['productItemsIds'], $row['id']);
                array_push($_SESSION['productItems'], $productData);
            } else {
                foreach($_SESSION['productItems'] as $key => $productSessionItem){
                    if ($productSessionItem['product_id'] == $row['id'] ) {
                        $newQuantity = $productSessionItem['quantity'] + $quantity;

                        $productData = [
                            'product_id' => $row['id'],
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'price' => $row['price'],
                            'quantity' =>  $newQuantity,
                        ];
                        $_SESSION['productItems'][$key] =  $productData;
                    }
                }
            }
            redirect('order-create.php', 'Item Added' .$row['name']);
        } else {
            redirect('order-create.php', 'No such product found.');
        }
    } else {
        redirect('order-create.php', 'Something Went Wrong!');
    }
}
