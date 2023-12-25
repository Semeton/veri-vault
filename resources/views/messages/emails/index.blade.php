<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Encrypted Messages and Documents') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    @livewire('messages.encrypted-emails')
                    <div class="my-8 pt-6">
                        <p class="dark:text-white mb-2">Total encrypted emails sent:
                            {{ count($encryptedEmails) }}
                        </p>
                        <hr class="mt-2" style="border-color: #595959;" />
                        <div class="uppercase text-xs dark:text-white my-4">Subject</div>
                        <hr class="mt-2 mb-0" style="border-color: #595959;" />
                    </div>
                    <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25">
                        @if (count($encryptedEmails) > 0)
                            <div class="">
                                @foreach ($encryptedEmails as $item)
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="break-all dark:text-white">
                                            {{ $item->subject }}
                                        </div>

                                        <div class="flex items-center text-left ms-2">
                                            @if ($item->created_at)
                                                <div class="text-sm text-gray-400 ms-6">
                                                    {{ __('Created at') }} {{ $item->created_at->diffForHumans() }}
                                                </div>
                                            @endif

                                            @if ($item->updated_at)
                                                <div class="text-sm text-gray-400 ms-6">
                                                    {{ __('Updated at') }} {{ $item->updated_at->diffForHumans() }}
                                                </div>
                                            @endif

                                            <a class="cursor-pointer ms-6 text-sm text-red-500 px-3 mb-0"
                                                href="{{ route('deleteEncryptedMail', $item->uuid) }}"
                                                onclick="return confirm('Are you sure you want to delete this encrypted email?')"><i
                                                    class="fa fa-trash" aria-hidden="true"></i>Delete</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed text-center">No encrypted
                                emails sent
                            </p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
