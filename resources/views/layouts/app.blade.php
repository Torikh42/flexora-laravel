<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Flexora Studio')</title>
  @vite('resources/css/app.css')
  @stack('styles')
</head>
<body>
  @include('components.navbar')

  @yield('content')

  @stack('scripts')
</body>
</html>