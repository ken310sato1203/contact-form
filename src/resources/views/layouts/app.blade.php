<!DOCTYPE html>

<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Sawarabi+Gothic&display=swap" rel="stylesheet">


    @yield('css')
</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <div class="header-utilities__center">
                    <a class="header__logo" href="/">
                        FashionablyLate
                    </a>
                </div>
                <div class="header-utilities__right">
                    @if (Auth::check())
                    <form class="header-utilities__form" action="/logout" method="post">
                        @csrf
                        <button class="header-nav__button">logout</button>
                    </form>
                    @else
                    @if(Request::is('login'))
                    <a href="/register">
                        <button class="header-nav__button">register</button>
                    </a>
                    @else
                    <a href="/login">
                        <button class="header-nav__button">login</button>
                    </a>
                    @endif
                    @endif
                </div>
            </div>

            <!-- <nav>
                <ul class="header-nav">
                    @if (Auth::check())
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/">HOME</a>
                    </li>
                    <li class="header-nav__item">
                        <form class="form" action="/logout" method="post">
                            @csrf
                            <button class="header-nav__button">logout</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </nav> -->
        </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>