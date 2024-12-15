<html lang="en">
<head>
  <title>Active</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<div class="container">           
  <table id="example1" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Extra features</td>
                <td>Active</td>
                <td><a href="#"><i class="fa-solid fa-trash-can"></i></a></td>
            </tr>
            <tr>
                <td>PayPal</td>
                <td>Active</td>
                <td><a href="#"><i class="fa-solid fa-trash-can"></i></a></td>
            </tr>
            <tr>
                <td>Processing fee</td>
                <td>Active</td>
                <td><a href="#"><i class="fa-solid fa-trash-can"></i></a></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example1').DataTable();
  });
</script>

</body>
</html>
