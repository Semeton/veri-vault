@extends('layouts.structure')
@section('content')
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <form id="emailForm" action="{{ route('revealEncryptedMessage', $uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="grid grid-cols-3 gap-4 mt-2 items-center">
                    <div class="col-span-2">
                        <input type="text" name="secret" id="secret"
                            class="block w-full rounded-md border-0 p-3 dark:text-gray-300 text-gray-700 shadow-sm ring-1 ring-inset dark:ring-gray-700 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full"
                            placeholder="Enter secret" required>
                        <div>
                            @error('secret')
                                <span class="error text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="">
                        <button name='submit'
                            class="items-center px-4 py-4 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150 cursor-pointer">Reaveal</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
