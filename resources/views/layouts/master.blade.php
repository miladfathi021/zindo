<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/line-awesome.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <title>@yield('title')</title>
</head>
<body class="bg-gray-100">
    <div id="app">
        <nav class="main-nav">
            <div class="container">
                <div class="flex items-center justify-between">
                    @guest
                        <a href="/" class="main-logo">Zin<span>D</span>o</a>
                        <div class="flex items-center">
                            <a href="/" class="mr-8 text-lg">Signup</a>
                            <a href="/" class="text-lg">Login</a>
                        </div>
                    @endguest
                    @auth
                        <a href="/{{ auth()->user()->username }}" class="main-logo">Zin<span>D</span>o</a>
                        <div class="flex items-center">
                            <user-dropdown :user="{{ auth()->user()->load('profile') }}"></user-dropdown>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
