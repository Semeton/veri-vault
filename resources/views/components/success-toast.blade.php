<div class="shadow-lg rounded-lg bg-white mx-auto m-8 p-4 notification-box flex">
    <div class="pr-2">
        <svg class="fill-current text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22"
            height="22">
            <path class="heroicon-ui"
                d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.46a1 1 0 0 1 1.42-1.42 3 3 0 0 0 4.24 0 1 1 0 0 1 1.42 1.42 5 5 0 0 1-7.08 0zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </svg>
    </div>
    <div>
        <div class="text-sm pb-2">
            Success
            <span class="float-right">
                <svg class="fill-current text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    width="22" height="22">
                    <path class="heroicon-ui"
                        d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z" />
                </svg>
            </span>
        </div>
        <div class="text-sm text-gray-600  tracking-tight ">
            {{ $message }}
        </div>
    </div>
</div>
