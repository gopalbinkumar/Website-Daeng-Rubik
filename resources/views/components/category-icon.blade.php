@props([
    'code' => '',
    'name' => '',
    'size' => 34,
    'class' => '',
])

@php
    $code = strtolower(trim($code));

    $iconMap = [
        '222' => '222.svg',
        '333' => '333.svg',
        '444' => '444.svg',
        '555' => '555.svg',
        '666' => '666.svg',
        '777' => '777.svg',

        '333bf' => '333bf.svg',
        '333fm' => '333fm.svg',
        '333oh' => '333oh.svg',
        '333ft' => '333ft.svg',
        '333fk' => '333fk.svg',
        '333mbf' => '333mbf.svg',

        '444bf' => '444bf.svg',
        '555bf' => '555bf.svg',

        'clock' => 'clock.svg',
        'minx' => 'minx.svg',
        'pyram' => 'pyram.svg',
        'skewb' => 'skewb.svg',
        'sq1' => 'sq1.svg',

        'big' => '555.svg',
    ];

    $fileName = $iconMap[$code] ?? '333.svg';
    $iconUrl = asset('assets/icons/cubing/event/' . $fileName);
@endphp

<span
    class="cubing-category-icon {{ $class }}"
    style="
        --icon-url: url('{{ $iconUrl }}');
        width: {{ $size }}px;
        height: {{ $size }}px;
    "
    role="img"
    aria-label="{{ $name }}"
></span>