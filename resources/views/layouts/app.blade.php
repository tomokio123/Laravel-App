<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Navigationファイルをrouteによって3パターンに分けたのでそのroute分岐処理 -->
            <!-- auth()内に、ガード設定を書ける -->
            @if(auth('admin')->user()) <!-- adminユーザーだったら -->
                @include('layouts.admin-navigation')<!-- layouts/navigation.blade.php を読み込んでいる -->
            @elseif(auth('owners')->user())
                @include('layouts.owner-navigation')
            @elseif(auth('users')->user())
                @include('layouts.user-navigation')
            @endif
            {{--本当は下記が正しいがちょっと置いておく。lesson76の後半--}}
            {{--上の何がまずいかって、owner画面でnavigationの部分がadminのままでownerにならない。なので「店舗情報」と「Dashboard」のナビゲーションが表示されない--}}
            {{--@if(request()->is('admin*'))
                @include('layouts.admin-navigation')
            @elseif(request()->is('owner*'))
                @include('layouts.owner-navigation')
            @else
                @include('layouts.user-navigation')
            @endif--}}

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
