<!DOCTYPE html>

<html lang="en" class="light-style layout-wide" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Invoice</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/tabler-icons.css" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../../assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />

  <!-- Page CSS -->

  <link rel="stylesheet" href="../../assets/vendor/css/pages/app-invoice-print.css" />

  <!-- Helpers -->
  <script src="../../assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
  <script src="../../assets/vendor/js/template-customizer.js"></script>
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../../assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->

  <div class="invoice-print p-5">

    
    <div class="card-header">
      <h1 class="text-center">Invoice</h1>
      <h1 class="text-center"> {{$data->inv_no}}</h1>
    </div>
    <div class="table-responsive">
      <table class="table m-0">
        <thead>
          <tr>
            <th>Item</th>
            <th>Product name</th>
            <th>Batch No</th>
            <th>Purchase Price</th>
            <th>Qty</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data->sale_items as $index=> $p_item)
          <tr>
            <td class="text-center ps-2 py-4">{{$index+1}}</td>
            <td class="text-center ps-2 py-4">{{$p_item->product->name}}</td>
            <td class="text-center ps-2 py-4">{{$p_item->batch}}</td>
            <td class="text-center ps-2 py-4">{{$p_item->purchase_price}}</td>
            <td class="text-center ps-2 py-4">{{$p_item->qty}}</td>
            <td class="text-center ps-2 py-4">{{$p_item->total_price}}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="3" class="align-top px-4 py-4">
              <p class="mb-2 mt-3">
                <span class="ms-3 fw-medium">Salesperson:</span>
                <span>{{$data->customer->name}}</span>
              </p>
              <p class="mb-2 mt-3">
                <span class="ms-3 fw-medium">Phone:</span>
                <span>{{$data->customer->phone}}</span>
              </p>
              <span class="ms-3">Thanks for your business</span>
            </td>
            <td class="text-end pe-3 py-4">
              <p class="mb-0 pb-3">Total:</p>
            </td>
            <td class="ps-2 py-3">
              <p class="fw-medium mb-2">{{$data->total}}</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


    <div class="row">
      <div class="col-12">
        <span class="fw-medium">Note:</span>
        <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
          projects. Thank You!</span>
      </div>
    </div>
  </div>

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->

  <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../../assets/vendor/libs/popper/popper.js"></script>
  <script src="../../assets/vendor/js/bootstrap.js"></script>
  <script src="../../assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
  <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
  <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="../../assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="../../assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="../../assets/js/app-invoice-print.js"></script>
</body>

</html>