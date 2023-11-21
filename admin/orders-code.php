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


   // ======= +/- function  Item ===== 
if(isset($_POST['productIncDec'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;
    foreach($_SESSION['productItems'] as $key=> $item){
        if ($item['product_id'] == $productId) {
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }    
    }

    if ($flag) {
        jsonResponse(200, 'success', 'Quantity Updated');
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong. Please re-fresh');
    }
}

 // ======= proceed To Place  Item ===== 
if(isset($_POST['proceedToPlaceBtn'])) {
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);

    // Checking for Customer
    $checkCustomer = mysqli_query($conn,"SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if ($checkCustomer) {
        if (mysqli_num_rows($checkCustomer) > 0) {
            $_SESSION['invoice_no'] = "INV-".rand(111111,999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;
            jsonResponse(200,'success','Customer Found');

        }else{
            $_SESSION['cphone'] = $phone;
            jsonResponse(404,'warning','Customer Not Found');
        }
    } else {
       jsonResponse(500,'error','Something Went Wrong');
    }
    
}

  
   // ======= proceed To Place  Item ===== proceedToPlaceBtn
if(isset($_POST['saveCustomerBtn'])) {
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);

    if ($name != '' && $phone != '') {
        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ];
        $result = insert('customers',$data);
        if ($result) {
            jsonResponse(200,'Success','Customer Created Successfully');
        }else{
            jsonResponse(500,'error','Something Went Wrong');
        }
    } else {
        jsonResponse(422,'Warning','Please fill requird fields');
    }
    
};