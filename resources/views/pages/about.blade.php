@extends('layouts.app')

@section('full-width')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">About DevHub</h1>

        <div class="prose prose-indigo max-w-none">
            <p class="text-lg text-gray-600 leading-relaxed">
                DevHub is a community-driven platform built for developers who want to learn, build, and share. We provide high-quality tutorials, free online tools, reusable code snippets, and curated resources to help programmers at every level write better software.
            </p>

            <h2>Our Mission</h2>
            <p>We believe every developer deserves access to great tools and knowledge — for free. DevHub exists to make the everyday tasks of software development faster, easier, and more enjoyable.</p>

            <h2>What We Offer</h2>
            <ul>
                <li><strong>Blog</strong> — In-depth articles and tutorials on web development, Laravel, JavaScript, CSS, and career growth.</li>
                <li><strong>Developer Tools</strong> — Free online utilities like JSON Formatter, Regex Tester, Password Generator, Color Converter, and more.</li>
                <li><strong>Code Snippets</strong> — A community library of copy-paste-ready code in PHP, JavaScript, Python, CSS, and other languages.</li>
                <li><strong>Newsletter</strong> — A weekly digest of the best developer content, delivered to your inbox.</li>
            </ul>

            <h2>Built With</h2>
            <p>DevHub is proudly built with <strong>Laravel</strong>, <strong>Tailwind CSS</strong>, and <strong>Alpine.js</strong> — the same modern stack we write about. The entire platform is open and transparent about the technologies it uses.</p>

            <h2>Get Involved</h2>
            <p>DevHub is only as good as its community. You can contribute by:</p>
            <ul>
                <li>Writing and sharing blog posts</li>
                <li>Submitting useful code snippets</li>
                <li>Reporting bugs or suggesting features</li>
                <li>Spreading the word with fellow developers</li>
            </ul>

            <p>Have questions or ideas? <a href="{{ route('contact') }}">Get in touch</a> — we'd love to hear from you.</p>
        </div>
    </div>
@endsection
