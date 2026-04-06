@php
$langColors = [
    'PHP' => 'bg-purple-100 text-purple-700',
    'JavaScript' => 'bg-yellow-100 text-yellow-800',
    'CSS' => 'bg-blue-100 text-blue-700',
    'Python' => 'bg-green-100 text-green-700',
    'Bash' => 'bg-gray-200 text-gray-700',
    'SQL' => 'bg-orange-100 text-orange-700',
    'HTML' => 'bg-red-100 text-red-700',
    'TypeScript' => 'bg-indigo-100 text-indigo-700',
    'Vue' => 'bg-emerald-100 text-emerald-700',
    'React' => 'bg-cyan-100 text-cyan-700',
];
$cls = $langColors[$lang] ?? 'bg-gray-100 text-gray-700';
@endphp
<span class="inline-flex items-center text-xs font-semibold rounded-full px-2.5 py-0.5 {{ $cls }}">{{ $lang }}</span>
