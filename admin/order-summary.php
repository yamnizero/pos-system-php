<?php
include('includes/header.php');
if (!isset($_SESSION['productItems'])) {
    echo '<script>window.location.href = "order-create.php" </script>';
}
?>


<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        Order Summary
                        <a href="order-create.php">Back to create order</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php alertmMessage(); ?>
                    <div id="myBillingArea">
                        <?php

                        if (isset($_SESSION['cphone'])) {
                            $phone = validate($_SESSION['phone']);
                            $invoiceNo = validate($_SESSION['invoice_no']);

                            $customerQuery = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
                            if ($customerQuery) {
                                if (mysqli_num_rows($customerQuery) > 0) {
                                    $cRowData = mysqli_fetch_assoc($customerQuery);
                                    ?>
                                    <table style="width:100%; margin-bottom:20px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:center;" colspan="2">
                                                <h4 style="font-size: 23px; line-height: 30px;margin: 2px;padding: 0;">Company Z</h4>
                                                <p style="font-size: 16px; line-height: 24px;margin: 2px;padding: 0;">#555 1st street, 3rd cross</p>
                                                <p style="font-size: 16px; line-height: 24px;margin: 2px;padding: 0;">Company Z pvt lyd.</p>
                                            </td>
                                        </tr>
                                    </tbody>

                                    </table>
                                    <?php
                                } else {
                                    echo '<h5>NO Customer Found</h5>';
                                    return;
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>