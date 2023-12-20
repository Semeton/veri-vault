<div
    class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    {{--  <x-application-logo class="block h-12 w-auto" />  --}}

    <h1 class="mt- text-2xl font-medium text-gray-900 dark:text-white">
        Your encrypted documents/messages
    </h1>

    <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
        This page is dedicated to managing all encrypted messages and documents. Users have the ability to set
        permissions and access the encrypted messages and documents as needed.
    </p>
</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 p-6 lg:p-8">
    @if (count($documents) > 0)
        <div class="">
            @foreach ($documents as $document)
                <div class="flex items-center justify-between mt-2">
                    <div class="break-all dark:text-white">
                        {{ $document->title }}
                    </div>

                    <div class="flex items-center text-left ms-2">
                        @if ($document->created_at)
                            <div class="text-sm text-gray-400 ms-6">
                                {{ __('Created at') }} {{ $document->created_at->diffForHumans() }}
                            </div>
                        @endif

                        @if ($document->updated_at)
                            <div class="text-sm text-gray-400 ms-6">
                                {{ __('Updated at') }} {{ $document->updated_at->diffForHumans() }}
                            </div>
                        @endif

                        <button class="cursor-pointer ms-6 text-sm text-red-500" {{--  wire:click="confirmApiTokenDeletion({{ $token->id }})"  --}}>
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">No encrypted documents available</p>
    @endif

</div>
