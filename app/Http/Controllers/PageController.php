<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        SEOMeta::setTitle('About DevHub');
        SEOMeta::setDescription('DevHub is a community-driven platform offering developer tools, tutorials, code snippets, and resources to help programmers build better software.');

        OpenGraph::setTitle('About — DevHub');
        OpenGraph::setDescription('DevHub is a community-driven platform for developers.');
        OpenGraph::setUrl(route('about'));

        TwitterCard::setTitle('About — DevHub');
        TwitterCard::setDescription('DevHub is a community-driven platform for developers.');

        return view('pages.about');
    }

    public function contact()
    {
        SEOMeta::setTitle('Contact Us — DevHub');
        SEOMeta::setDescription('Get in touch with the DevHub team. We\'d love to hear from you — questions, feedback, or partnership inquiries.');

        OpenGraph::setTitle('Contact Us — DevHub');
        OpenGraph::setDescription('Get in touch with the DevHub team.');
        OpenGraph::setUrl(route('contact'));

        TwitterCard::setTitle('Contact Us — DevHub');
        TwitterCard::setDescription('Get in touch with the DevHub team.');

        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Mail::raw(
            "Contact from {$validated['name']} ({$validated['email']})\nSubject: {$validated['subject']}\n\n{$validated['message']}",
            fn ($m) => $m->to(config('mail.from.address', 'admin@devhub.com'))
                ->replyTo($validated['email'], $validated['name'])
                ->subject('Contact: ' . $validated['subject'])
        );

        return back()->with('success', 'Message sent! We\'ll get back to you soon.');
    }

    public function privacy()
    {
        SEOMeta::setTitle('Privacy Policy — DevHub');
        SEOMeta::setDescription('Read the DevHub privacy policy. Learn how we collect, use, and protect your personal information.');

        OpenGraph::setTitle('Privacy Policy — DevHub');
        OpenGraph::setDescription('Read the DevHub privacy policy.');
        OpenGraph::setUrl(route('privacy'));

        TwitterCard::setTitle('Privacy Policy — DevHub');
        TwitterCard::setDescription('Read the DevHub privacy policy.');

        return view('pages.privacy');
    }

    public function advertise()
    {
        SEOMeta::setTitle('Advertise With Us — Reach Developers');
        SEOMeta::setDescription('Promote your product or service to thousands of developers every month. Header banners, sidebar ads, in-article placements, and sponsored posts.');

        OpenGraph::setTitle('Advertise With Us — DevHub');
        OpenGraph::setDescription('Promote your product or service to thousands of developers every month.');
        OpenGraph::setUrl(route('advertise'));

        TwitterCard::setTitle('Advertise With Us — DevHub');
        TwitterCard::setDescription('Promote your product or service to thousands of developers every month.');

        return view('pages.advertise');
    }

    public function advertiseContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'placement' => 'required|in:header,sidebar,in-article,sponsored-post',
            'message' => 'required|string|max:2000',
        ]);

        Mail::raw(
            "Advertise Inquiry from {$validated['name']} ({$validated['email']})\n" .
            "Company: " . ($validated['company'] ?? 'N/A') . "\n" .
            "Placement: {$validated['placement']}\n\n" .
            $validated['message'],
            fn ($m) => $m->to(config('mail.from.address', 'admin@devhub.com'))
                ->subject('New Advertising Inquiry — ' . ($validated['company'] ?? $validated['name']))
        );

        return back()->with('success', 'Thanks! We\'ll get back to you within 24 hours.');
    }
}
