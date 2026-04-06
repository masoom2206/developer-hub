<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPostViews
{
    public function handle(Request $request, Closure $next): Response
    {
        $post = $request->route('post');

        if ($post && !$request->session()->has('viewed_post_' . $post->id)) {
            $post->increment('views');
            $request->session()->put('viewed_post_' . $post->id, true);
        }

        return $next($request);
    }
}
