<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shopify APP Admin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Cart Fees App</h2>
  <br>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#home" id="all_fees">All</a>
    </li>
    <!--li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu1" id="active">Active</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu2">Inactive</a>
    </li-->
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu3" id="create_fees">Create Fees</a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="home" class="container tab-pane active"><br>
      @include('shopify-app::layouts.listing')
    </div>
    <div id="menu1" class="container tab-pane fade"><br>
      @include('shopify-app::layouts.active')
    </div>
    <div id="menu2" class="container tab-pane fade"><br>
      @include('shopify-app::layouts.inactive')
    </div>
    <div id="menu3" class="container tab-pane fade"><br>
      @include('shopify-app::layouts.create_fees')
    </div>
  </div>
</div>

  

</body>
</html>
