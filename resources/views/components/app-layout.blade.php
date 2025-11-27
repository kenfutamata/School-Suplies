<x-layouts.app>
    @isset($header)
        <div class="mb-4 pb-3 border-bottom border-1 border-dark-subtle">
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($content)
        {{ $content }}
    @endisset
</x-layouts.app>




