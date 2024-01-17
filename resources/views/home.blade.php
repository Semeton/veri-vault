@extends('layouts.structure')
@section('content')
    <div class="max-w-7xl mx-auto p-6 lg:p-8" id="encryptDecryptSection">
        <div class="mt-16">
            <h1 class="mt-0 mb-12 text-4xl font-bold tracking-tight md:text-5xl xl:text-6xl text-center text-indigo-300">
                Quick Encryption/Decryption
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                <div
                    class="scale-100 p-6 bg-gray-800/50 bg-gradient-to-bl from-gray-700/50 via-transparent ring-1 ring-inset ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div class="">
                        <div class="flex">
                            <div class="h-16 w-16 bg-indigo-20 flex items-center justify-center rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    class="w-7 h-7 stroke-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                </svg>
                            </div>
                            <h2 class="mt-4 text-xl font-semibold text-white">Plain Text</h2>
                        </div>
                        <div class="">
                            @livewire('crypto.plain-text-input')
                            {{--  <livewire:crypto.plain-text-input />  --}}
                        </div>
                    </div>
                </div>

                <div
                    class="scale-100 p-6 bg-gray-800/50 bg-gradient-to-bl from-gray-700/50 via-transparent ring-1 ring-inset ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-indigo-500">
                    <div class="">
                        <div class="flex">
                            <div class="h-16 w-16 bg-indigo-20 flex items-center justify-center rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" class="w-7 h-7 stroke-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64" />
                                </svg>
                            </div>
                            <h2 class="mt-4 text-xl font-semibold text-white">Encrypted Text
                            </h2>
                        </div>
                        <div class="">
                            @livewire('crypto.encrypted-text-input')
                            {{--  <livewire:crypto.encrypted-text-input />  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="mt-6 p-6 bg-gray-800/50 bg-gradient-to-bl from-gray-700/50 via-transparent ring-1 ring-inset ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <form class="md:flex items-center justify-between">
                <div class="flex">
                    <div class="h-16 w-16 bg-indigo-800/20 flex items-center justify-center rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="w-7 h-7 stroke-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-xl font-semibold text-white">Generate a random secret key
                    </h2>
                </div>
                <input type="text" id="secretKey"
                    class="md:w-96 w-full p-2 border-indigo-900 rounded-md my-3 mr-4 bg-gray-900 focus:border-indigo-600 text-white"
                    readonly>
                <button type="button" onclick="generateSecretKey()"
                    class="items-center px-4 py-3 !bg-indigo-700 !bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150 w-full md:w-56">Generate</button>
            </form>
        </div>
    </div>

    <script>
        function generateSecretKey() {
            let secretKey = crypto.getRandomValues(new Uint8Array(16));
            secretKey = Array.from(secretKey, (byte) => String.fromCharCode(byte)).join('');
            document.getElementById('secretKey').value = btoa(secretKey);
            e.preventDefault();
        }
    </script>
@endsection
