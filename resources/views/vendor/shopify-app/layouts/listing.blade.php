<html lang="en">
<head>
  <title>Fees</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">           
  <table id="example" class="table table-striped table-bordered" style="width:100%" width='100%' border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Fees Type</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    var store_URL = window.location.href;
    $('#example').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        "bDestroy": true,
        "ajax": {
            "url": "{{route('api-get-fees')}}",
            "data": {"store_url":store_URL},
        },
        columns: [
          { data: 'fees_name' },
          { data: 'fees_type' },
          { data: 'price' },
          { data: 'action' },
        ]
    });
  });


  $(document).on("click","#all_fees",function() {
    var store_URL = window.location.href;
    $('#example').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        "bDestroy": true,
        "ajax": {
            "url": "{{route('api-get-fees')}}",
            "data": {"store_url":store_URL},
        },
        columns: [
          { data: 'fees_name' },
          { data: 'fees_type' },
          { data: 'price' },
          { data: 'action' },
        ]
    });
  });

  var ID;
  function myFunction(ID) {
    //alert(ID)
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax(
    {
      url: "{{route('delete-fees')}}",
      type: 'delete', // replaced from put
      dataType: "JSON",
      data: {
        "id": ID // method and token not needed in data
      },
      success: function (response)
      {
        Swal.fire(
            'Deleted!',
            'Deleted.!',
            'success'
          )
        // console.log(response.message); // see the reponse sent
        var store_URL = window.location.href;
        $('#example').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            "bDestroy": true,
            "ajax": {
                "url": "{{route('api-get-fees')}}",
                "data": {"store_url":store_URL},
            },
            columns: [
              { data: 'fees_name' },
              { data: 'fees_type' },
              { data: 'price' },
              { data: 'action' },
            ]
        });
      },
      error: function(xhr) {
        console.log(xhr.responseText); // this line will save you tons of hours while debugging
        // do something here because of error
      }
    });
  }
</script>

</body>
</html>
