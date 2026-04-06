<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsoredAd;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = SponsoredAd::latest()->paginate(15);
        return view('admin.sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        return view('admin.sponsors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'banner_image' => 'required|image|max:2048',
            'target_url' => 'required|url|max:500',
            'placement' => 'required|in:header,sidebar,in-article',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
        ]);

        $bannerPath = $request->file('banner_image')->store('sponsors', 'public');

        SponsoredAd::create([
            'company' => $validated['company'],
            'banner_url' => asset('storage/' . $bannerPath),
            'target_url' => $validated['target_url'],
            'placement' => $validated['placement'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
        ]);

        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor created successfully.');
    }

    public function destroy(SponsoredAd $sponsor)
    {
        $sponsor->delete();
        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor deleted.');
    }
}
