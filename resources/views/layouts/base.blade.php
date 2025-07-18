<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="assets/img/logo-anex-2.png">
    <!-- CSS only -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/owl.theme.default.min.css">
    <!-- fancybox -->
    <link rel="stylesheet" href="../assets/css/jquery.fancybox.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <!-- style -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- responsive -->
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <!-- color -->
    <link rel="stylesheet" href="../assets/css/color.css">
</head>
<body>
    @yield('header')
    @yield('content')
    @yield('footer')
    @yield('messages_window')
    @yield('my-orders')
    @yield('support')
    @yield('user-supports')
</body>
@include('layouts.scripts')
