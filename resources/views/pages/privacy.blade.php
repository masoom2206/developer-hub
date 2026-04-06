@extends('layouts.app')

@section('full-width')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Privacy Policy</h1>
        <p class="text-sm text-gray-400 mb-8">Last updated: {{ now()->format('F d, Y') }}</p>

        <div class="prose prose-indigo max-w-none">
            <h2>1. Information We Collect</h2>
            <p>When you use DevHub, we may collect the following information:</p>
            <ul>
                <li><strong>Account information</strong> — name, email address, and password when you register.</li>
                <li><strong>Usage data</strong> — pages visited, tools used, and interactions with the site collected via cookies and analytics.</li>
                <li><strong>Newsletter subscription</strong> — your email address when you subscribe to our newsletter.</li>
                <li><strong>Comments and snippets</strong> — content you voluntarily submit to the platform.</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <p>We use collected information to:</p>
            <ul>
                <li>Provide and improve our services</li>
                <li>Send newsletter emails (only if you subscribed)</li>
                <li>Display relevant content and advertisements</li>
                <li>Respond to contact inquiries</li>
                <li>Prevent abuse and maintain security</li>
            </ul>

            <h2>3. Cookies</h2>
            <p>DevHub uses cookies for session management, remembering preferences, and analytics. Third-party services like Google AdSense may also set cookies for ad personalization. You can disable cookies in your browser settings.</p>

            <h2>4. Third-Party Services</h2>
            <p>We may use the following third-party services that have their own privacy policies:</p>
            <ul>
                <li>Google AdSense (advertising)</li>
                <li>Google Analytics (usage tracking)</li>
                <li>Email service providers (newsletter delivery)</li>
            </ul>

            <h2>5. Data Sharing</h2>
            <p>We do not sell your personal information. We may share data with service providers who assist in operating the platform, or when required by law.</p>

            <h2>6. Data Retention</h2>
            <p>We retain your account data as long as your account is active. Newsletter subscribers can unsubscribe at any time. You may request deletion of your data by contacting us.</p>

            <h2>7. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access the personal data we hold about you</li>
                <li>Request correction of inaccurate data</li>
                <li>Request deletion of your data</li>
                <li>Unsubscribe from marketing communications</li>
            </ul>

            <h2>8. Security</h2>
            <p>We implement reasonable security measures to protect your data, including encrypted passwords and HTTPS encryption. However, no method of transmission over the internet is 100% secure.</p>

            <h2>9. Changes to This Policy</h2>
            <p>We may update this privacy policy from time to time. Changes will be posted on this page with an updated date.</p>

            <h2>10. Contact</h2>
            <p>If you have questions about this privacy policy, please <a href="{{ route('contact') }}">contact us</a>.</p>
        </div>
    </div>
@endsection
