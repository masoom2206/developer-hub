@props(['slot' => '', 'format' => 'auto', 'class' => ''])

<div class="{{ $class }}">
    <div class="text-center">
        <span class="text-[10px] uppercase tracking-wider text-gray-400">Advertisement</span>
    </div>
    <!-- Google AdSense -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
         data-ad-slot="{{ $slot }}"
         data-ad-format="{{ $format }}"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>
