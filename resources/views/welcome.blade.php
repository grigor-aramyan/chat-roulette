<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            #app {
                background: #780206;  /* fallback for old browsers */
                background: -webkit-linear-gradient(to bottom, #061161, #780206);  /* Chrome 10-25, Safari 5.1-6 */
                background: linear-gradient(to bottom, #061161, #780206); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
                min-height: 100vh;
            }
        </style>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    </head>
    <body>
        <!--div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div-->

        <div id="app">
            <home-component></home-component>
        </div>

        <!-- The core Firebase JS SDK is always required and must be listed first -->
        <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>

        <!-- TODO: Add SDKs for Firebase products that you want to use
            https://firebase.google.com/docs/web/setup#available-libraries -->
        <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-database.js"></script>

        <script>
            // Your web app's Firebase configuration
            var firebaseConfig = {
                apiKey: '{{ env('FIREBASE_RD_API_KEY') }}',
                authDomain: '{{ env('FIREBASE_RD_AUTH_DOMAIN') }}',
                databaseURL: '{{ env('FIREBASE_RD_DATABASE_URL') }}',
                projectId: '{{ env('FIREBASE_RD_PROJECT_ID') }}',
                storageBucket: '{{ env('FIREBASE_RD_STORAGE_BUCKET') }}',
                messagingSenderId: '{{ env('FIREBASE_RD_MESSAGING_SENDER_ID') }}',
                appId: '{{ env('FIREBASE_RD_APP_ID') }}'
            };
            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            window.RD = firebase.database();
        </script>

        <script defer src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
