<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Orders</h4>
        </div>
        <div class="card-body">
            <?php 
                $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id ORDER BY o.id DESC";
                $orders = mysqli_query($conn,$query);
                if ($orders) {
                   if (mysqli_num_rows($orders) > 0) {
                    ?>
                     <table class="table table-striped table-bordered justify-content-center align-items-center">
                        <thead>
                            <tr>
                                <th>Tracking No</th>
                                <th>C Name</th>
                                <th>C Phone</th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Payment Mode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $orderItem): ?>
                                <tr>
                                    <td class="fw-bold"><?= $orderItem['tracking_no'] ?></td>
                                    <td ><?= $orderItem['name'] ?></td>
                                    <td ><?= $orderItem['phone'] ?></td>
                                    <td ><?= date('d M, Y',strtotime($orderItem['order_date'])) ?></td>
                                    <td ><?= $orderItem['order_status'] ?></td>
                                    <td ><?= $orderItem['payment_mode'] ?></td>
                                    <td>
                                        <a href="" class="btn btn-info mb-0 px-2 btn-sm">View</a>
                                        <a href="" class="btn btn-primary mb-0 px-2 btn-sm">Print</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                     </table>
                    <?php
                   } else {
                    echo "<h5>NO Record Available </h5>";
                   }
                   
                }else{
                    echo "<h5>Something Went Wrong</h5>";
                }
            ?>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>