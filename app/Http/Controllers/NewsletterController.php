<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeNewsletter;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if ($subscriber->status === 'unsubscribed') {
                $subscriber->update(['status' => 'active']);
                Mail::to($request->email)->queue(new WelcomeNewsletter($request->email));
                return back()->with('newsletter_success', 'Welcome back! You have been re-subscribed.');
            }
            return back()->with('newsletter_info', 'You are already subscribed.');
        }

        NewsletterSubscriber::create([
            'email' => $request->email,
            'status' => 'active',
        ]);

        Mail::to($request->email)->queue(new WelcomeNewsletter($request->email));

        return back()->with('newsletter_success', 'Thanks for subscribing! Check your inbox for a welcome email.');
    }
}
