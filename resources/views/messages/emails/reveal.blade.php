@extends('layouts.structure')
@section('content')
<div
    class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-lighter bg-center dark:bg-dots-lighter bg-gray-900 selection:bg-indigo-500 selection:text-white">
    <div class="m-10">
        <div
            class="scale-100 p-6 bg-gray-800/50 bg-gradient-to-bl via-transparent ring-inset ring-white/5 rounded-lg shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div class="">
                <div class="flex">
                    <div class="h-16 w-16 bg-indigo-800/20 flex items-center justify-center rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            class="w-7 h-7 stroke-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-xl font-semi-bold text-white">Decrypted Email</h2>
                </div>
                <div class="mt-4">
                    <p class="text-white">{{ $decryptedBody }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
