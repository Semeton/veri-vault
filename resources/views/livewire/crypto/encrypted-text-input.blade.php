<div>
    <form wire:submit="save">
        <div class="form-group">
            <div class="mt-2">
                <textarea id="plainText" name="plainText" rows="15" cols="100"
                    class="block w-full rounded-md border-0 p-3 dark:text-gray-300 text-gray-700 shadow-sm ring-1 ring-inset dark:ring-gray-700 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"
                    wire:model="plainText" required>{{ $encryptedText }}</textarea>
                <div class="mt-2">
                    <label for="secret"
                        class="text-xs dark:text-white text-gray-800 uppercase tracking-widest">Secret</label>
                    <input type="text" name="secret" id="secret"
                        class="block w-full rounded-md border-0 p-3 dark:text-gray-300 text-gray-700 shadow-sm ring-1 ring-inset dark:ring-gray-700 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"
                        wire:model="secret" required>
                </div>

                <div class="mt-4">
                    <button
                        class="items-center px-4 py-2 bg-indigo-700  border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-indigo-800 transition ease-in-out duration-150 w-full">Decrypt
                        Text</button>
                </div>
            </div>
        </div>
    </form>

    <div wire:ignore>
        <script>
            document.addEventListener('encryptedTextUpdated', function(e) {
                const encryptedText = e.detail;
                @this.set('encryptedText', encryptedText[0]);
                {{--  console.log(encryptedText);  --}}
            });
        </script>
    </div>
</div>
