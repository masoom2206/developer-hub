<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Snippet;
use App\Models\Tool;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(1.0))
            ->add(Url::create(route('posts.index'))->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.9))
            ->add(Url::create(route('tools.index'))->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)->setPriority(0.9))
            ->add(Url::create(route('snippets.index'))->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.8))
            ->add(Url::create(route('advertise'))->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.3));

        Post::where('status', 'published')->each(function (Post $post) use ($sitemap) {
            $sitemap->add(
                Url::create(route('posts.show', $post->slug))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        });

        Tool::each(function (Tool $tool) use ($sitemap) {
            $sitemap->add(
                Url::create(route('tools.show', $tool->slug))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        });

        Snippet::where('is_public', true)->each(function (Snippet $snippet) use ($sitemap) {
            $sitemap->add(
                Url::create(route('snippets.show', $snippet->slug))
                    ->setLastModificationDate($snippet->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        });

        return $sitemap->toResponse(request());
    }

    public function robots()
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /dashboard\nDisallow: /profile\n\nSitemap: {$sitemapUrl}\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
