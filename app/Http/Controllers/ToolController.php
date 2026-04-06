<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ToolController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Free Developer Tools — Online Utilities for Programmers');
        SEOMeta::setDescription('A collection of free online developer tools: JSON formatter, Base64 encoder, regex tester, password generator, color converter, and more.');
        SEOMeta::setKeywords(['developer tools', 'online tools', 'json formatter', 'base64 encoder', 'regex tester', 'password generator']);

        OpenGraph::setTitle('Free Developer Tools — DevHub');
        OpenGraph::setDescription('A collection of free online developer tools for everyday programming tasks.');
        OpenGraph::setUrl(route('tools.index'));

        TwitterCard::setTitle('Free Developer Tools — DevHub');
        TwitterCard::setDescription('A collection of free online developer tools for everyday programming tasks.');

        $tools = Tool::with('category')->get();

        return view('tools.index', compact('tools'));
    }

    public function show(Tool $tool)
    {
        SEOMeta::setTitle($tool->name . ' — Free Online Tool');
        SEOMeta::setDescription($tool->description);
        SEOMeta::setKeywords([$tool->name, $tool->tool_type, 'developer tool', 'online tool', 'free']);

        OpenGraph::setTitle($tool->name . ' — DevHub Tools');
        OpenGraph::setDescription($tool->description);
        OpenGraph::setUrl(route('tools.show', $tool->slug));

        TwitterCard::setTitle($tool->name . ' — DevHub Tools');
        TwitterCard::setDescription($tool->description);

        $relatedTools = Tool::where('id', '!=', $tool->id)->take(8)->get();

        $viewMap = [
            'json-formatter' => 'tools.json-formatter',
            'base64-encoder-decoder' => 'tools.base64',
            'regex-tester' => 'tools.regex-tester',
            'password-generator' => 'tools.password-generator',
            'word-character-counter' => 'tools.word-counter',
            'color-converter' => 'tools.color-converter',
            'markdown-previewer' => 'tools.markdown-previewer',
            'css-gradient-generator' => 'tools.css-gradient',
        ];

        $view = $viewMap[$tool->slug] ?? abort(404);

        return view($view, compact('tool', 'relatedTools'));
    }

    public function markdownRender(Request $request)
    {
        $request->validate(['markdown' => 'required|string']);

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return response()->json([
            'html' => $converter->convert($request->markdown)->getContent(),
        ]);
    }
}
