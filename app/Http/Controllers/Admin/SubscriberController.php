<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function export(): StreamedResponse
    {
        $filename = 'subscribers-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Status', 'Subscribed At']);

            NewsletterSubscriber::orderBy('created_at', 'desc')
                ->chunk(500, function ($subscribers) use ($handle) {
                    foreach ($subscribers as $sub) {
                        fputcsv($handle, [
                            $sub->email,
                            $sub->status,
                            $sub->created_at->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed.');
    }
}
