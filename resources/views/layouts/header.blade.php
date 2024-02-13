<!-- Section: Design Block -->
<section class="background-radial-gradient overflow-hidden">
    <style>
        .background-radial-gradient {
            background-color: hsl(218, 41%, 15%);
            background-image: radial-gradient(650px circle at 0% 0%,
                    hsl(218, 41%, 35%) 15%,
                    hsl(218, 41%, 30%) 35%,
                    hsl(218, 41%, 20%) 75%,
                    hsl(218, 41%, 19%) 80%,
                    transparent 100%),
                radial-gradient(1250px circle at 100% 100%,
                    hsl(218, 41%, 45%) 15%,
                    hsl(218, 41%, 30%) 35%,
                    hsl(218, 41%, 20%) 75%,
                    hsl(218, 41%, 19%) 80%,
                    transparent 100%);
        }

        #radius-shape-1 {
            height: 220px;
            width: 220px;
            top: -60px;
            left: -130px;
            background: radial-gradient(#090537, #4338CA);
            overflow: hidden;
        }

        #radius-shape-2 {
            border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
            bottom: -60px;
            right: -110px;
            width: 300px;
            height: 300px;
            background: radial-gradient(#090537, #4338CA);
            overflow: hidden;
        }
    </style>
    {{--  <!-- Navbar -->
    <nav class="relative flex w-full items-center justify-between bg-white py-2 shadow-sm shadow-neutral-700/10 dark:bg-neutral-800 dark:shadow-black/30  lg:flex-wrap lg:justify-start"
        data-te-navbar-ref>
        <!-- Container wrapper -->
        <div class="flex w-full flex-wrap items-center justify-between px-6">
            <div class="flex items-center">
                <!-- Toggle button -->
                <button
                    class="block border-0 bg-transparent py-2 pr-2.5 text-neutral-500 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0 dark:text-neutral-200 lg:hidden"
                    type="button" data-te-collapse-init data-te-target="#navbarSupportedContentY"
                    aria-controls="navbarSupportedContentY" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="[&>svg]:w-7">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7">
                            <path fill-rule="evenodd"
                                d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Navbar Brand -->
                <a class="text-primary dark:text-primary-400" href="#!">
                    <span class="[&>svg]:ml-2 [&>svg]:mr-3 [&>svg]:h-6 [&>svg]:w-6 [&>svg]:lg:ml-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </span>
                </a>
            </div>

            <!-- Collapsible wrapper -->
            <div class="!visible hidden flex-grow basis-[100%] items-center lg:!flex lg:basis-auto"
                id="navbarSupportedContentY" data-te-collapse-item>
                <!-- Left links -->
                <ul class="mr-auto lg:flex lg:flex-row" data-te-navbar-nav-ref>
                    <li data-te-nav-item-ref>
                        <a class="block py-2 pr-2 text-neutral-500 transition duration-150 ease-in-out hover:text-neutral-600 focus:text-neutral-600 disabled:text-black/30 dark:text-neutral-200 dark:hover:text-neutral-300 dark:focus:text-neutral-300 dark:disabled:text-white/30 lg:px-2 [&.active]:text-black/80 dark:[&.active]:text-white/80"
                            href="#!" data-te-nav-link-ref data-te-ripple-init data-te-ripple-color="light"
                            disabled>Dashboard</a>
                    </li>
                    <li data-te-nav-item-ref>
                        <a class="block py-2 pr-2 text-neutral-500 transition duration-150 ease-in-out hover:text-neutral-600 focus:text-neutral-600 disabled:text-black/30 dark:text-neutral-200 dark:hover:text-neutral-300 dark:focus:text-neutral-300 dark:disabled:text-white/30 lg:px-2 [&.active]:text-black/80 dark:[&.active]:text-white/80"
                            href="#!" data-te-nav-link-ref data-te-ripple-init
                            data-te-ripple-color="light">Team</a>
                    </li>
                    <li class="mb-2 lg:mb-0" data-te-nav-item-ref>
                        <a class="block py-2 pr-2 text-neutral-500 transition duration-150 ease-in-out hover:text-neutral-600 focus:text-neutral-600 disabled:text-black/30 dark:text-neutral-200 dark:hover:text-neutral-300 dark:focus:text-neutral-300 dark:disabled:text-white/30 lg:px-2 [&.active]:text-black/80 dark:[&.active]:text-white/80"
                            href="#!" data-te-nav-link-ref data-te-ripple-init
                            data-te-ripple-color="light">Projects</a>
                    </li>
                </ul>
                <!-- Left links -->
            </div>
            <!-- Collapsible wrapper -->

            <!-- Right elements -->
            <div class="my-1 flex items-center lg:my-0 lg:ml-auto">
                <button type="button"
                    class="mr-2 inline-block rounded px-6 pt-2.5 pb-2 text-xs font-medium uppercase leading-normal text-primary transition duration-150 ease-in-out hover:bg-neutral-500 hover:bg-opacity-10 hover:text-primary-600 focus:text-primary-600 focus:outline-none focus:ring-0 active:text-primary-700 dark:text-primary-400 dark:hover:bg-neutral-700 dark:hover:bg-opacity-60 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600"
                    data-te-ripple-init data-te-ripple-color="light">
                    Login
                </button>
                <button type="button"
                    class="inline-block rounded bg-primary px-6 pt-2.5 pb-2 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                    data-te-ripple-init data-te-ripple-color="light">
                    Sign up for free
                </button>
            </div>
            <!-- Right elements -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->  --}}
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="font-semibold text-gray-400 hover:text-white dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Dashboard</a>
            @else
                <a href="{{ route('home') }}"
                    class="font-semibold text-gray-400 hover:text-white dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Home</a>

                <a href="{{ route('login') }}"
                    class="ml-4 font-semibold text-gray-400 hover:text-white dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Log
                    in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="ml-4 font-semibold text-gray-400 hover:text-white dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Register</a>
                @endif
            @endauth
        </div>
    @endif
    
    <!-- Jumbotron -->
    <div class="px-6 md:py-12 text-center md:px-12 lg:py-24 lg:text-left">
        <div class="w-100 mx-auto text-neutral-800 sm:max-w-2xl md:max-w-3xl lg:max-w-5xl xl:max-w-7xl">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div class="mt-12 lg:mt-0" style="z-index: 10">
                    <h1 class="mt-0 mb-12 text-4xl font-bold tracking-tight md:text-5xl xl:text-6xl text-indigo-300">
                        Zero-Knowledge-Proof<br /><span class="text-indigo-300">Encrypted Messages</span>
                    </h1>
                    <p class="opacity-70 text-[hsl(218,81%,85%)] mb-4">
                        Encrypt and decrypt messages using a zero-knowledge-proof algorithm with a unique secret chosen
                        by you. Enable secure communication by sending encrypted emails and establishing end-to-end
                        zero-knowledge-proof encrypted messages and data-sharing within your application using REST
                        Services provided by VeriVault
                    </p>
                    <button type="button"
                        class="mt-6 mr-2 items-center px-4 py-4 !bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150 cursor-pointer w-64"
                        data-te-ripple-init data-te-ripple-color="light"
                        onclick="document.getElementById('encryptDecryptSection').scrollIntoView({ behavior: 'smooth' })">
                        Try it now
                    </button>
                    <button type=""
                        class="mt-6 items-center px-4 py-4 border border-indigo-700 !border-indigo-700 outline-indigo-700 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-800 focus:text-white active:text-white active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150 cursor-pointer w-64"
                        data-te-ripple-init data-te-ripple-color="light"
                        onclick="window.open('https://documenter.getpostman.com/view/19842116/2s9YsGhD6t', '_blank');">
                        API Documentation
                    </button>
                </div>
                <div class="relative mb-12 lg:mb-0">
                    <div id="radius-shape-1" class="absolute rounded-full shadow-lg"></div>
                    <div id="radius-shape-2" class="absolute shadow-lg"></div>
                    <div
                        class="relative backdrop-blur-[25px] backdrop-saturate-[200%] block rounded-lg px-6 py-12 bg-gray-800 bg-opacity-95 shadow-black/20 md:px-12 bg-dots-darker motion-safe:hover:scale-[1.01] transition-all duration-250">
                        <div class="animate-bounce text-indigo-300">
                            <i class="fa fa-user-secret fa-10x pt-8" aria-hidden="true" style="font-size: 200px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jumbotron -->
</section>
<!-- Section: Design Block -->
