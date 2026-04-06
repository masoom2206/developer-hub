<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background-color: #f9fafb; padding: 40px 20px;">
    <div style="max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; padding: 40px; border: 1px solid #e5e7eb;">
        <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px;">Welcome to DevHub!</h1>
        <p style="font-size: 15px; color: #6b7280; line-height: 1.6; margin: 0 0 16px;">
            Thanks for subscribing to the DevHub newsletter, <strong>{{ $email }}</strong>.
        </p>
        <p style="font-size: 15px; color: #6b7280; line-height: 1.6; margin: 0 0 24px;">
            Every week you'll receive hand-picked articles, tool recommendations, and code snippets to help you build better software.
        </p>
        <a href="{{ url('/') }}" style="display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">Visit DevHub</a>
        <p style="font-size: 13px; color: #9ca3af; margin-top: 32px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            You're receiving this because you subscribed at {{ config('app.url') }}. You can unsubscribe at any time.
        </p>
    </div>
</body>
</html>
