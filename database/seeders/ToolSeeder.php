<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $devtools = Category::where('slug', 'devtools')->first();

        if (!$devtools) {
            return;
        }

        $tools = [
            [
                'name' => 'JSON Formatter',
                'slug' => 'json-formatter',
                'description' => 'Format, validate, and beautify your JSON data with syntax highlighting. Instantly spot errors and clean up messy JSON.',
                'tool_type' => 'Formatter',
                'is_featured' => true,
            ],
            [
                'name' => 'Base64 Encoder/Decoder',
                'slug' => 'base64-encoder-decoder',
                'description' => 'Encode text to Base64 or decode Base64 strings back to plain text. Works in real-time as you type.',
                'tool_type' => 'Encoder',
                'is_featured' => true,
            ],
            [
                'name' => 'Regex Tester',
                'slug' => 'regex-tester',
                'description' => 'Test your regular expressions with live matching highlights. Supports global, case-insensitive, and multiline flags.',
                'tool_type' => 'Tester',
                'is_featured' => true,
            ],
            [
                'name' => 'Password Generator',
                'slug' => 'password-generator',
                'description' => 'Generate strong, secure passwords with customizable length and character sets. Includes a strength indicator.',
                'tool_type' => 'Generator',
                'is_featured' => true,
            ],
            [
                'name' => 'Word & Character Counter',
                'slug' => 'word-character-counter',
                'description' => 'Count words, characters, sentences, and paragraphs instantly. Includes reading time estimates.',
                'tool_type' => 'Counter',
                'is_featured' => true,
            ],
            [
                'name' => 'Color Converter',
                'slug' => 'color-converter',
                'description' => 'Convert colors between HEX, RGB, and HSL formats. Pick any color and get all format equivalents instantly.',
                'tool_type' => 'Converter',
                'is_featured' => true,
            ],
            [
                'name' => 'Markdown Previewer',
                'slug' => 'markdown-previewer',
                'description' => 'Write Markdown and see rendered HTML in real-time. Split-view editor powered by league/commonmark.',
                'tool_type' => 'Previewer',
                'is_featured' => false,
            ],
            [
                'name' => 'CSS Gradient Generator',
                'slug' => 'css-gradient-generator',
                'description' => 'Create beautiful CSS gradients visually. Pick colors, set direction, and copy the CSS code instantly.',
                'tool_type' => 'Generator',
                'is_featured' => false,
            ],
        ];

        foreach ($tools as $tool) {
            Tool::updateOrCreate(
                ['slug' => $tool['slug']],
                array_merge($tool, ['category_id' => $devtools->id])
            );
        }
    }
}
