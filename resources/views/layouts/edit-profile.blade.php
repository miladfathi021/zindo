<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="/css/app.css">
    <title>Edit Profile | {{ $user->name }}</title>
</head>
<body style="background-color: #edf2f7;">
    <div id="app">
        <nav class="nav-edit-profile">

            <div class="avatar flex items-center justify-center mt-24">
                <avatar :user="{{ $user }}"></avatar>
            </div>

            <div class="name w-full text-center mt-6 text-2xl text-white">
                        <p>{{ $user->name }}</p>
            </div>

            <div class="username w-full text-center mt-2 text-lg text-yellow-500">
                <p>{{ '@'. $user->username }}</p>
            </div>

            <div class="bio w-full text-center px-8 mt-8 text-lg text-black">
                @if($user->profile->bio != null)
                    <p>{{ $user->profile->bio }}</p>
                @endif
            </div>

            <div class="home w-full text-center mt-24 text-sm text-white">
                <a href="/">Home</a>
            </div>
        </nav>
        <main class="nav-edit-profile-content">
        @yield('content')
        </main>
    </div>
    <script src="/js/app.js"></script>
    @yield('script')
</body>
</html>
