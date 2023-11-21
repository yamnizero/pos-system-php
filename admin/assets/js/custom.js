$(document).ready(function () {

    alertify.set('notifier', 'position', 'top-right');


    $(document).on('click', '.increment', function () {
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();

        var currentValue = parseInt($quantityInput.val());

        if (!isNaN(currentValue)) {
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }

    });

    $(document).on('click', '.decrement', function () {
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();

        var currentValue = parseInt($quantityInput.val());

        if (!isNaN(currentValue) && currentValue > 1) {
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }

    });

    function quantityIncDec(prodId, qty) {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'productIncDec': true,
                'product_id': prodId,
                'quantity': qty
            },
            success: function (response) {
                var res = JSON.parse(response);
                console.log(res);
                if (res.status == 200) {
                    // window.location.reload();
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
                } else {
                    $('#productArea').load(' #productContent');
                    alertify.error(res.message);
                }
            }
        });
    }


    // proceed to place order button click
    $(document).on('click', '.proceedToPlace', function () {
        var payment_mode = $('#payment_mode').val();
        var cphone = $('#cphone').val();

        if (payment_mode == '') {
            //
            swal("Select Payment Mode", "Select your payment mode", "warning");
            return false;
        }

        if (cphone == '' && !$.isNumeric(cphone)) {
            //
            swal("Enter Phone Number", "Enter Valid Phone Number", "warning");
            return false;
        }

        var data = {
            'proceedToPlaceBtn': true,
            'cphone': cphone,
            'payment_mode': payment_mode
        };

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function (response) {
                var res = JSON.parse(response);

                if (res.status == 200) {
                    window.location.href = 'order-summary.php';
                    // $('#productArea').load(' #productContent');

                } else if (res.status == 404) {
                    swal(res.message, res.message, res.status_type, {
                        buttons: {
                            catch: {
                                text: "Add Customer",
                                value: "catch",
                            },
                            cancel: "cancel"

                        }
                    })
                        .then((value) => {
                            switch (value) {
                                case "catch":
                                    $('#c_phone').val(cphone);
                                    $('#addCustomerModal').modal('show');
                                    // console.log('Pop the Customer add modal');
                                   
                                    break;

                                default:

                            }
                        });
                } else {
                    swal(res.message, res.message, res.status_type)
                }
            }
        });

    });

    // add customer to customer table 
    $(document).on('click', '.saveCustomer', function () {
        var c_name = $('#c_name').val();
        var c_phone = $('#c_phone').val();
        var c_email = $('#c_email').val();

        if (c_name != '' && c_phone != '') {
            if ($.isNumeric(c_phone)) {
                var data = {
                    'saveCustomerBtn': true,
                    'name': c_name,
                    'phone': c_phone,
                    'email': c_email,

                };

                $.ajax({
                    type: "POST",
                    url: "orders-code.php",
                    data: data,
                    success: function (response) {
                        var res = JSON.parse(response);

                        if (res.status == 200) {
                            swal(res.message, res.message, res.status_type);
                            $('#addCustomerModal').modal('hide');


                        } else if(res.status == 422) {
                            swal(res.message, res.message, res.status_type)
                        }
                        else {
                            swal(res.message, res.message, res.status_type)
                        }
                    }
                });

            } else {
                swal("Please Valid Phone Number", "", "Warning")
            }
        } else {
            swal("Please Fill requierd fields", "", "Warning")
        }
    });

});