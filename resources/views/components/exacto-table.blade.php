<div class="exacto-card bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        @if (session('success'))
            <div class="mb-4 exacto-alert exacto-alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="exacto-table">
            <thead>
                <tr>
                    {{ $header ?? $headers ?? '' }}
                </tr>
            </thead>
            <tbody>
                {{ $body }}
            </tbody>
        </table>
        
        @if (isset($pagination))
            <div class="mt-4">
                {{ $pagination }}
            </div>
        @endif
    </div>
</div>
