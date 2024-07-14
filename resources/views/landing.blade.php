<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ChatDug</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased">
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100  selection:bg-red-500 selection:text-white">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/users') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Users</a>
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                        in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif


        <div class="max-w-7xl mx-auto p-6 lg:p-8">

            <div class="flex flex-row mx-auto items-center justify-center">
                <div class="flex w-20 h-20 items-center justify-center">


                    <svg class="h-14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M 374.438 17.759 C 371.654 18.753, 367.905 20.497, 366.107 21.635 C 361.194 24.746, 333.958 51.987, 332.815 54.934 C 332.268 56.345, 328.779 73.856, 325.061 93.846 L 318.303 130.192 241.755 206.846 C 199.654 249.006, 164.673 284.553, 164.020 285.840 C 162.583 288.673, 162.482 295.573, 163.827 299 C 166.976 307.024, 177.196 311.120, 185.282 307.599 C 187.140 306.790, 219.132 275.523, 260.988 233.608 L 333.477 161.018 341.738 169.237 L 350 177.456 277.084 250.478 C 210.965 316.692, 204.059 323.912, 203 327.916 C 199.790 340.064, 210.903 351.210, 223.040 348.017 C 227.109 346.946, 234.256 340.095, 304.616 269.821 L 381.731 192.798 418.836 185.950 C 439.243 182.184, 456.741 178.664, 457.720 178.128 C 458.699 177.592, 466.341 170.245, 474.702 161.802 C 494.036 142.279, 495.500 139.581, 495.500 123.479 C 495.500 105.479, 496.464 106.813, 450.303 60.935 C 414.736 25.586, 410.958 22.129, 405 19.486 C 396.516 15.722, 382.387 14.924, 374.438 17.759 M 377.857 54.750 L 370.951 61.500 410.473 100.993 L 449.995 140.486 456.664 133.817 C 467.245 123.237, 469.486 126.956, 426.888 84.405 C 396.081 53.632, 390.004 48, 387.603 48 C 385.446 48, 383.104 49.622, 377.857 54.750 M 354.814 113.694 L 351.253 132.500 364.863 146.250 C 372.349 153.812, 379.154 160.012, 379.987 160.027 C 382.734 160.077, 416 153.637, 416 153.056 C 416 152.743, 403.034 139.527, 387.187 123.687 L 358.375 94.887 354.814 113.694"
                            stroke="none" fill="#A22C24" fill-rule="evenodd" />
                        <path
                            d="M 96.992 289.508 L 85.517 301.015 148.257 363.755 L 210.996 426.494 222.744 414.746 L 234.492 402.998 171.999 340.499 C 137.628 306.124, 109.272 278, 108.987 278 C 108.701 278, 103.304 283.178, 96.992 289.508 M 43.423 343.225 C 25.220 361.660, 23.099 364.172, 20.054 370.899 C 17.268 377.056, 16.627 379.826, 16.239 387.399 C 15.813 395.706, 16.399 399.642, 22.949 432.500 C 30.665 471.204, 32.250 476.178, 37.887 479.405 C 42.784 482.209, 112.313 496.004, 121.467 495.988 C 138.761 495.959, 145.519 492.005, 169.780 467.720 L 188.994 448.488 126.219 385.719 L 63.444 322.949 43.423 343.225"
                            stroke="none" fill="#F44336" fill-rule="evenodd" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-5xl font-bold text-red-500">Chat<span class="text-red-700">Dug</span></h1>
                </div>
            </div>

            <div>
                <h1 class="max-w-3xl mx-auto text-5xl font-bold text-center md:text-6xl lg:text-7xl">
                    Messaging App
                    <span class="text-red-500">for Single Students</span>
                </h1>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                    {{-- 1st Section --}}
                    <div
                        class="scale-100 p-6 bg-white  from-gray-700/50 via-transparent  rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                <svg class="w-7 h-7 stroke-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 ">Users Plethora</h2>

                            <p class="mt-4 text-gray-500  text-sm leading-relaxed">
                                One of the amazing features of ChatDug is to view all users that were currently
                                registered on the system. Need a random person to talk? No problem! You can now
                                start messaging any person as long as they are within your reach.
                            </p>
                        </div>
                        <a href="{{ Route('login') }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>

                        </a>
                    </div>

                    {{-- 2nd Section --}}
                    <div
                        class="scale-100 p-6 bg-white  from-gray-700/50 via-transparent  rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">

                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">


                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    class="w-7 h-7 stroke-red-500" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                </svg>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 ">Secure Transimissions</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                ChatDug is made to be fast but not instant. And even so, ChatDug offers immediate
                                transmission of your messages. Although all users are displayed, ChatDug promises
                                a secure transmission of your messages that even the developers have no idea
                                what conversations you have with others.
                            </p>
                        </div>
                        <a href="{{ Route('login') }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>
                    </div>

                    {{-- 3rd Section --}}
                    <div
                        class="scale-100 p-6 bg-white  from-gray-700/50 via-transparent  rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">

                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">


                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-7 h-7 stroke-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                </svg>

                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 ">Unlimited Chatting</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                ChatDug is a free-to-use app that only needs an internet connection, nothing
                                else. If you want to worry about payments and microservices, no need! ChatDug is
                                free for a lifetime and it will always stay that way. If you still insist on
                                paying, why not talk to
                                <a href="https://www.facebook.com/piercestevem.pantanosas"
                                    class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20]">Pierce</a>
                                and ask him about the service.
                            </p>
                        </div>

                        <a href="{{ Route('login') }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>

                    </div>

                    {{-- 4th Section --}}
                    <div
                        class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64" />
                                </svg>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900 ">Cross-platform Access</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                ChatDug is a web-based application that you can access with just a simple URL address.
                                No extra downloads. Just a simple website link. ChatDug is catered to be accessible
                                in any platforms and screen sizes as long as you have a browser and an internet
                                connection.
                            </p>
                        </div>

                        <a href="{{ Route('login') }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>

                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">


                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    {{-- Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) --}}
                    &#169 All rights reserved 2024. (build v0.1)
                </div>
            </div>
        </div>
    </div>
    @livewireScripts()
</body>

</html>
