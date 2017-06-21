<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
@include('_include.header')
<body>
    @include('_include.nav')
    @yield('page-content')
</body>
@yield('page-script')
</html>
