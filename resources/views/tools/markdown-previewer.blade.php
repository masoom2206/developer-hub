@extends('tools._layout')

@section('tool-title', 'Markdown Previewer')
@section('tool-description', 'Write Markdown on the left and see the rendered HTML preview on the right in real-time. Powered by league/commonmark on the server.')

@section('tool-content')
<div x-data="markdownPreviewer()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border border-gray-300 rounded-xl overflow-hidden">
        {{-- Editor --}}
        <div class="border-b md:border-b-0 md:border-r border-gray-300">
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-300 flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Markdown</span>
            </div>
            <textarea x-model="markdown" @input="render()" rows="20" placeholder="# Hello World&#10;&#10;Write your **markdown** here..."
                class="w-full border-0 focus:ring-0 text-sm font-mono p-4 resize-none"></textarea>
        </div>
        {{-- Preview --}}
        <div>
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-300 flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Preview</span>
                <span x-show="loading" x-cloak class="text-xs text-gray-400">Rendering...</span>
            </div>
            <div class="p-4 prose prose-sm prose-indigo max-w-none min-h-[28rem] overflow-y-auto" x-html="html">
                <p class="text-gray-400">Preview will appear here...</p>
            </div>
        </div>
    </div>
</div>

<script>
function markdownPreviewer() {
    let timer = null;
    return {
        markdown: '# Hello World\n\nThis is a **Markdown Previewer** tool.\n\n## Features\n\n- Real-time preview\n- Server-side rendering with league/commonmark\n- Supports all standard Markdown syntax\n\n### Code Example\n\n```php\n$greeting = "Hello, Developer!";\necho $greeting;\n```\n\n> Markdown is a lightweight markup language that you can use to add formatting elements to plain text documents.\n\n---\n\n[Visit DevHub](/) | *Built with Laravel*',
        html: '', loading: false,

        init() { this.render(); },

        render() {
            clearTimeout(timer);
            timer = setTimeout(() => this._doRender(), 300);
        },

        async _doRender() {
            if (!this.markdown.trim()) { this.html = '<p class="text-gray-400">Preview will appear here...</p>'; return; }
            this.loading = true;
            try {
                const res = await fetch('{{ route("tools.markdown.render") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ markdown: this.markdown })
                });
                const data = await res.json();
                this.html = data.html;
            } catch(e) { this.html = '<p class="text-red-500">Rendering failed.</p>'; }
            this.loading = false;
        }
    }
}
</script>
@endsection

@section('tool-howto')
<ol>
    <li>Write or paste <strong>Markdown</strong> in the left panel.</li>
    <li>The rendered <strong>HTML preview</strong> appears on the right in real-time (with a small debounce delay).</li>
    <li>Rendering is handled server-side by the <code>league/commonmark</code> PHP library for accurate output.</li>
    <li>All standard Markdown features are supported: headings, bold, italic, links, images, code blocks, lists, blockquotes, and horizontal rules.</li>
</ol>
@endsection
