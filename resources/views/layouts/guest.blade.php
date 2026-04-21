<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Buku Tamu') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-400 font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center py-12">
        {{ $slot }}
    </div>
</body>
</html>
