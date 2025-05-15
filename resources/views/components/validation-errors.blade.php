@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">{{ __('Whoops! Ada sesuatu yang salah') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>Kredensial ini tidak sesuai dengan catatan sistem</li>
            @endforeach
        </ul>
    </div>
@endif
