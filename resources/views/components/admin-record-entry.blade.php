@props(['record_active', 'record_type', 'record_time', 'record_human_date', 'record_admin', 'record_reason', 'record_date', 'color', 'textColor', 'borderColor'])

@switch($record_type)
    @case(0)
        @php($record_type = 'Kick')
        @php($color = 'bg-blue-500/5')
        @php($textColor = 'text-blue-500')
        @php($borderColor = 'border-blue-500/20')
        @break
    @case(1)
        @php($record_type = 'Admin Jail')
        @php($color = 'bg-orange-400/5')
        @php($textColor = 'text-orange-400')
        @php($borderColor = 'border-orange-400/20')
        @break
    @case(2)
        @php($record_type = 'Ban')
        @php($color = 'bg-red-400/5')
        @php($textColor = 'text-red-400')
        @php($borderColor = 'border-red-400/20')
        @break
    @default
        @php($record_type = 'Unknown')
        @php($color = 'bg-gray-500/5')
        @php($textColor = 'text-gray-500')
        @php($borderColor = 'border-gray-500/20')
        @break
@endswitch

@if($record_active)
    <div x-data="{ open: false }" class="{{$color}} p-3 rounded-lg inline-flex w-full space-x-2 border-4 {{$borderColor}}">
        <x-heroicon-m-scale class="w-5 h-5 {{$textColor}}" />
        <div class="w-full">
            <div class="inline-flex justify-between w-full items-center">
                <div class="font-medium {{$textColor}}">
                    {{$record_type}}
                </div>
                <div>
                    <template x-if="open">
                        <button @click="open = !open" class="text-gray-400 underline"><x-heroicon-m-chevron-up class="h-5 w-5 text-gray-500"/></button>
                    </template>
                    <template x-if="!open">
                        <button @click="open = !open" class="text-gray-400 underline"><x-heroicon-m-chevron-down class="h-5 w-5 text-gray-500"/></button>
                    </template>
                </div>
            </div>
            <div class="space-y-2 divide-white/5 divide-y">
                <div>
                    <span class="text-gray-400">{{$record_reason}}</span>
                    <span class="text-gray-600">(<span class="underline">{{$record_time}} minutes left</span>)</span>
                </div>
                <div x-show="open" x-transition class="grid grid-cols-2 py-2">
                    <div class="flex flex-col">
                        <span class="text-gray-400">Admin</span>
                        <div class="inline-flex items-center space-x-1 text-gray-500">
                            <x-heroicon-m-shield-check class="w-5 h-5" />
                            <span>{{$record_admin}}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-400">Time</span>
                        <div class="inline-flex items-center space-x-1 text-gray-500">
                            <x-heroicon-m-clock class="w-5 h-5" />
                            <span>{{$record_date}} GMT+0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div x-data="{ open: false }" class="{{$color}} p-3 rounded-lg inline-flex w-full space-x-2 ">
        <x-heroicon-m-scale class="w-5 h-5 {{$textColor}}" />
        <div class="w-full">
            <div class="inline-flex justify-between w-full items-center">
                <div class="font-medium {{$textColor}}">
                    {{$record_type}}
                </div>
                <div>
                    <template x-if="open">
                        <button @click="open = !open" class="text-gray-400 underline"><x-heroicon-m-chevron-up class="h-5 w-5 text-gray-500"/></button>
                    </template>
                    <template x-if="!open">
                        <button @click="open = !open" class="text-gray-400 underline"><x-heroicon-m-chevron-down class="h-5 w-5 text-gray-500"/></button>
                    </template>
                </div>
            </div>
            <div class="space-y-2 divide-white/5 divide-y">
                <div>
                    <span class="text-gray-400">{{$record_reason}}</span>
                    <span class="text-gray-600">(<span class="underline">expired {{$record_human_date}}</span>)</span>
                </div>
                <div x-show="open" x-transition class="grid grid-cols-2 py-2">
                    <div class="flex flex-col">
                        <span class="text-gray-400">Admin</span>
                        <div class="inline-flex items-center space-x-1 text-gray-500">
                            <x-heroicon-m-shield-check class="w-5 h-5" />
                            <span>{{$record_admin}}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-400">Time</span>
                        <div class="inline-flex items-center space-x-1 text-gray-500">
                            <x-heroicon-m-clock class="w-5 h-5" />
                            <span>{{$record_date}} GMT+0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

