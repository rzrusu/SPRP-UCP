{{--<div x-data="{open: true}" x-show="open" class="absolute top-0 left-0 w-screen h-svh z-50 bg-black/75 inline-flex justify-center items-center z-50 p-8 md:p-0">
    <div x-trap.inert="open" x-trap.noscroll="open" @if($closeable) @click.outside="open = false" @endif class="bg-gray-700 border rounded-lg border-stroke-primary w-full md:w-1/2 lg:w-1/3 xl:w-1/4 p-4">
        {{$slot}}
    </div>
</div>--}}

<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
            <div x-trap.inert="open" x-trap.noscroll="open" @if($closeable) @click.outside="open = false" @endif class="relative transform bg-gray-700 border rounded-lg border-stroke-primary w-full md:w-1/2 lg:w-1/3 xl:w-1/4 p-4">
                {{$slot}}
            </div>
        </div>
    </div>
</div>
