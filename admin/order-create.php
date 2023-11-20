<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Order
                <a href="#" class="btn  btn-danger float-end">back</a>
            </h4>
        </div>
        <div class="card-body">

            <?php alertmMessage(); ?>

            <form action="orders-code.php" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select Product</label>
                        <select name="product_id" class="form-control mySelect2">
                            <option value="">-- Select Product --</option>
                            <?php
                            $products = getAll('products');
                            if ($products) {
                                if (mysqli_num_rows($products) > 0) {
                                    foreach ($products as $productItem) {
                            ?>
                                        <option value="<?= $productItem['id']; ?>"><?= $productItem['name']; ?></option>
                            <?php
                                    }
                                } else {
                                    echo '<option value="">No product Found</option>';
                                }
                            } else {
                                echo '<option value="">Something Went Wrong!</option>';
                            }

                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="">Quantity *</label>
                        <input type="number" name="quantity" value="1" class="form-control" />
                    </div>

                    <div class="col-md-3 mb-3 text-end">
                        <br>
                        <button type="submit" name="addItem" class="btn btn-primary">Add Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="mb-0">Products</h4>
        </div>
        <div class="card-body" id="productArea">
            <?php
            if (isset($_SESSION['productItems'])) 
            {
                $sesstionProducts = $_SESSION['productItems'];
            ?>
                <div class="table-responsive mb-3" id="productContent">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Toatal Price</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                foreach($sesstionProducts as $key => $item)  : 
                                ?>
                            <tr>
                                <td><?=$i++; ?></td>
                                <td><?=$item['name']; ?></td>
                                <td><?=$item['price']; ?></td>
                                <td>
                                    <div class="input-group qtyBox">
                                        <input type="hidden"  value="<?=$item['product_id']; ?>" class="prodId" >
                                        <button class="input-group-text decrement">-</button>
                                        <input type="text" value="<?=$item['quantity']; ?>" class="qty quantityInput">
                                        <button class="input-group-text increment">+</button>
                                    </div>
                                </td>
                                <td><?=number_format($item['price'] * $item['quantity'], 0 ); ?></td>
                                <td>
                                    <a href="order-item-delete.php?index=<?=$key; ?>" class="btn btn-danger">Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
               echo '<h5>No Item Added</h5>';
            }

            ?>
        </div>
        <h5></h5>
    </div>
</div>

<?php include('includes/footer.php'); ?>