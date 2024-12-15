<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Fee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="{{ secure_asset('public/app_assets/css/style.css') }}"/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main>
        <div class="wrraper">
            <div class="inner_wrraper inner_wrraper_two">
                <div class="wrraper_left">
                    <div class="wrraper_left_header">
                        <h4>Fee information</h4>
                    </div>
                    <div class="inner_items">
                        <form class="feesclass"  method="post" id="fees_form" action="">
                        <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 input_name">
                            <p>Name/Title</p>
                            <input type="text" name="fees_name" id="fees_name" value="" placeholder="Extra Features" class="form-control">
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 input_name">
                            <p>Price</p>
                            <input type="number" name="price" id="price" value="" class="form-control" min="1" max="999">
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6 Type_fees pt-2">
                            <p>Type of Fees</p>
                            <select class="form-select" name="fees_type" aria-label="Default select example">
                              <option value="fixed">Fixed</option>
                              <option value="percent">Percent</option>
                            </select>
                        </div>
                         <div class="col-12 submit_button">
                        <input type="submit" value="Submit" id="submit" class="btn btn-primary">
                    </div>
                         </div>
                        </form>
                    </div>
                </div>
                <div class="wrraper_right" style="display: none;">
                    <div class="wrraper_right_header">
                        <h4>Preview</h4>
                    </div>
                    <div class="wrraper_right_body">
                        <div class="item_specification">
                            <div class="item_price">
                                <p>Price</p>
                            </div>
                            <div class="item_quantity">
                                <p>Quantity</p>
                            </div>
                            <div class="item_total">
                                <p>Total</p>
                            </div>
                        </div>
                        <div class="item_detailes">
                            <div class="item_img">
                                <img src="images/download.jpg" alt="">
                            </div>
                            <div class="item_Inner_detailes">
                                <div class="item_name">
                                    <p>Fossil Watch</p>
                                </div>
                                <div class="item_extra">
                                    <div class="extra">
                                        <p>Extra Features: </p>
                                    </div>
                                    <div class="item_headphone radio_icon">
                                        <input type="radio" name="selection" id=""><span></span> <label for=""> Head Phones - <b>$199.00</b></label>
                                    </div>
                                    <div class="item_wireless radio_icon">
                                        <input type="radio" name="selection" id=""><span></span> <label for=""> wireless - <b>$149.00</b></label>
                                    </div>
                                </div>
                                <div class="remove_btn">
                                    <a href="#" class="remove">Remove</a>
                                </div>
                            </div>
                            <div class="wrraper_right_body_bottom">
                                <div class="item_price_bottom">
                                    <p>Price</p>
                                    <div class="item_amount">
                                        <b><p>$150.00</p></b>
                                    </div>
                                </div>
                                <div class="item_quantity_bottom">
                                    <p>Quantity</p>
                                    <div class="item_quantity">
                                    <input type="text" name="" id="" value="1">
                                    </div>
                                </div>
                                <div class="item_total_bottom">
                                    <p>Total</p>
                                    <div class="item_total">
                                        <b><p>$150.00</p></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrraper_right_footer">
                            <div class="sub_total">
                                <p>Subtotal <span><b>$150.00</b></span></p>
                            </div>
                            <div class="fotter_btn">
                                <button type="submit" class="btn update_btn">Update Cart</button>
                                <button type="submit" class="btn check_out_btn">Check Out</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script>
    if ($("#fees_form").length > 0) {
            $("#fees_form").validate({
                rules: {
                    fees_name: {
                        required: true
                    }, 
                    price: {
                        required: true
                    }  
                },
            messages: {
                fees_name: {
                    required: "Please enter Fees Name"
                },
                price: {
                    required: "Please enter Fees Price"
                },
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var store_url = window.location.href;
                $('#submit').html('Please Wait...');
                $("#submit"). attr("disabled", true);
                $.ajax({
                    url: "{{url('api/v1/cart-create-fees')}}",
                    type: "POST",
                    data: $('#fees_form').serialize()+"&store_url="+store_url,
                    success: function( response ) {
                        $('#submit').html('Submit');
                        $("#submit"). attr("disabled", false);
                        //alert('Success');
                        Swal.fire(
                          'Good job!',
                          'Your fees created successfully.!',
                          'success'
                        )
                        document.getElementById("fees_form").reset(); 
                    }
                });
            }
        })
    }
</script>
</html>