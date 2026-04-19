<!-- START OF MASTER -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <link rel="icon" href="{{ asset('dist/assets/images/favicon.svg') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="{{ asset('dist/assets/fonts/tabler-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/assets/fonts/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/assets/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/assets/fonts/material.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('dist/assets/css/style-preset.css') }}">
</head>
<body>

  @include('layouts.header-content')
  @include('layouts.sidebar')
  @yield('content')

  @stack('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- END OF MASTER -->
