<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
  overflow-x: hidden;
}
    </style>
    @stack('css')
    @include('layout.header')
</head>

<body>
    
    @yield('content')


    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
