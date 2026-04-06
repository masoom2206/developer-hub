<?php

namespace Database\Seeders;

use App\Models\Snippet;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SnippetSeeder extends Seeder
{
    public function run(): void
    {
        $snippets = [
            [
                'title' => 'Eloquent Scope for Published Posts',
                'language' => 'PHP',
                'description' => 'A reusable query scope that filters posts by published status and ensures the publish date is not in the future.',
                'tags' => ['laravel', 'eloquent', 'php'],
                'code' => <<<'CODE'
<?php

// Add this method to your Post model

public function scopePublished(Builder $query): Builder
{
    return $query
        ->where('status', 'published')
        ->where('published_at', '<=', now());
}

// Usage in controllers:
$posts = Post::published()->latest()->paginate(15);

// Chain with other scopes:
$featured = Post::published()
    ->where('is_featured', true)
    ->with(['author', 'category'])
    ->take(5)
    ->get();
CODE,
            ],
            [
                'title' => 'Laravel Rate Limiting API Route',
                'language' => 'PHP',
                'description' => 'Configure per-user rate limiting for API routes using Laravel\'s built-in RateLimiter, falling back to IP-based limits for guests.',
                'tags' => ['laravel', 'api', 'rate-limiting'],
                'code' => <<<'CODE'
<?php

// In AppServiceProvider boot() or RouteServiceProvider

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)
        ->by($request->user()?->id ?: $request->ip())
        ->response(function () {
            return response()->json([
                'message' => 'Too many requests. Please slow down.',
            ], 429);
        });
});

// Apply to routes:
Route::middleware(['auth:sanctum', 'throttle:api'])
    ->prefix('api/v1')
    ->group(function () {
        Route::apiResource('posts', PostController::class);
    });
CODE,
            ],
            [
                'title' => 'Laravel Collection Grouping',
                'language' => 'PHP',
                'description' => 'Group a collection of Eloquent models by a computed key such as month, useful for building charts or activity timelines.',
                'tags' => ['laravel', 'collections', 'php'],
                'code' => <<<'CODE'
<?php

// Group users by their registration month
$grouped = User::all()->groupBy(
    fn (User $user) => $user->created_at->format('Y-m')
);

// Result: ['2026-01' => [...users], '2026-02' => [...users]]

// Transform into chart data
$chartData = $grouped->map(fn ($users, $month) => [
    'month' => Carbon::parse($month)->format('M Y'),
    'count' => $users->count(),
])->values();

// Group orders by status and sum totals
$summary = Order::all()
    ->groupBy('status')
    ->map(fn ($orders) => [
        'count' => $orders->count(),
        'total' => $orders->sum('amount'),
        'average' => $orders->avg('amount'),
    ]);
CODE,
            ],
            [
                'title' => 'PHP Array Flatten Function',
                'language' => 'PHP',
                'description' => 'Recursively flatten a nested multidimensional array into a single-level array using array_merge and array_map.',
                'tags' => ['php', 'arrays', 'utility'],
                'code' => <<<'CODE'
<?php

function array_flatten(array $array): array
{
    $result = [];

    array_walk_recursive($array, function ($value) use (&$result) {
        $result[] = $value;
    });

    return $result;
}

// Usage
$nested = [1, [2, 3], [4, [5, 6]], [[7]]];

$flat = array_flatten($nested);
// [1, 2, 3, 4, 5, 6, 7]

// Real-world: flatten tag arrays from multiple posts
$allTags = array_flatten(
    array_map(fn ($post) => $post['tags'], $posts)
);
$uniqueTags = array_unique($allTags);
CODE,
            ],
            [
                'title' => 'PHP Str Helper — Slug Generator',
                'language' => 'PHP',
                'description' => 'Generate URL-safe slugs from any string using Laravel\'s Str helper, with examples for unique slug generation.',
                'tags' => ['laravel', 'strings', 'utility'],
                'code' => <<<'CODE'
<?php

use Illuminate\Support\Str;

// Basic slug
Str::slug('Hello World 2026!');
// "hello-world-2026"

// With custom separator
Str::slug('Hello World', '_');
// "hello_world"

// Unicode support
Str::slug('Über die Brücke');
// "uber-die-brucke"

// Generate unique slug for database
function generateUniqueSlug(string $title, string $model): string
{
    $slug = Str::slug($title);
    $original = $slug;
    $counter = 1;

    while ($model::where('slug', $slug)->exists()) {
        $slug = $original . '-' . $counter++;
    }

    return $slug;
}

$slug = generateUniqueSlug('My Post Title', Post::class);
// "my-post-title" or "my-post-title-2" if taken
CODE,
            ],
            [
                'title' => 'Debounce Function',
                'language' => 'JavaScript',
                'description' => 'Delay function execution until after a pause in calls — essential for search inputs, resize handlers, and scroll events.',
                'tags' => ['javascript', 'utility', 'performance'],
                'code' => <<<'CODE'
function debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

// Usage: search input that waits for user to stop typing
const searchInput = document.getElementById('search');

const handleSearch = debounce((query) => {
    fetch(`/api/search?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(results => renderResults(results));
}, 400);

searchInput.addEventListener('input', (e) => {
    handleSearch(e.target.value);
});

// Usage: window resize handler
const handleResize = debounce(() => {
    console.log('Window resized to:', window.innerWidth);
    recalculateLayout();
}, 250);

window.addEventListener('resize', handleResize);
CODE,
            ],
            [
                'title' => 'Deep Clone Object',
                'language' => 'JavaScript',
                'description' => 'Create a true deep copy of any object using structuredClone, which handles Dates, Maps, Sets, and circular references correctly.',
                'tags' => ['javascript', 'utility', 'es2024'],
                'code' => <<<'CODE'
// Modern: structuredClone (handles Dates, Maps, Sets, circular refs)
const original = {
    name: 'Config',
    created: new Date(),
    tags: new Set(['a', 'b', 'c']),
    nested: { deep: { value: 42 } },
};

const clone = structuredClone(original);

// Verify it's a true deep copy
clone.nested.deep.value = 99;
console.log(original.nested.deep.value); // 42 (unchanged)
console.log(clone.created instanceof Date); // true (preserved)
console.log(clone.tags instanceof Set);     // true (preserved)

// Why NOT to use JSON.parse(JSON.stringify()):
const broken = JSON.parse(JSON.stringify(original));
console.log(broken.created); // string, not Date!
console.log(broken.tags);    // {} empty object, not Set!

// structuredClone works with:
// - Date, RegExp, Map, Set, ArrayBuffer, Blob
// - Circular references
// - Nested objects of any depth
CODE,
            ],
            [
                'title' => 'Fetch with Timeout',
                'language' => 'JavaScript',
                'description' => 'Wrap the Fetch API with an AbortController timeout so requests automatically cancel after a specified duration.',
                'tags' => ['javascript', 'api', 'async'],
                'code' => <<<'CODE'
async function fetchWithTimeout(url, options = {}, timeout = 5000) {
    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), timeout);

    try {
        const response = await fetch(url, {
            ...options,
            signal: controller.signal,
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        if (error.name === 'AbortError') {
            throw new Error(`Request timed out after ${timeout}ms`);
        }
        throw error;
    } finally {
        clearTimeout(id);
    }
}

// Usage
try {
    const data = await fetchWithTimeout('/api/users', {}, 3000);
    console.log(data);
} catch (err) {
    console.error(err.message);
    // "Request timed out after 3000ms"
}
CODE,
            ],
            [
                'title' => 'LocalStorage with Expiry',
                'language' => 'JavaScript',
                'description' => 'Store values in localStorage with an automatic expiration time, perfect for caching API responses or user preferences.',
                'tags' => ['javascript', 'storage', 'utility'],
                'code' => <<<'CODE'
function setWithExpiry(key, value, ttlMs) {
    const item = {
        value: value,
        expiry: Date.now() + ttlMs,
    };
    localStorage.setItem(key, JSON.stringify(item));
}

function getWithExpiry(key) {
    const raw = localStorage.getItem(key);
    if (!raw) return null;

    const item = JSON.parse(raw);

    if (Date.now() > item.expiry) {
        localStorage.removeItem(key);
        return null; // expired
    }

    return item.value;
}

// Usage: cache API response for 5 minutes
const FIVE_MINUTES = 5 * 60 * 1000;

async function getCategories() {
    const cached = getWithExpiry('categories');
    if (cached) return cached;

    const res = await fetch('/api/categories');
    const data = await res.json();

    setWithExpiry('categories', data, FIVE_MINUTES);
    return data;
}
CODE,
            ],
            [
                'title' => 'Array Remove Duplicates',
                'language' => 'JavaScript',
                'description' => 'Remove duplicate values from arrays using Set, with variations for primitive values and objects with a specific key.',
                'tags' => ['javascript', 'arrays', 'utility'],
                'code' => <<<'CODE'
// Primitives: use Set spread
const unique = (arr) => [...new Set(arr)];

console.log(unique([1, 2, 2, 3, 3, 3]));
// [1, 2, 3]

console.log(unique(['a', 'b', 'a', 'c']));
// ['a', 'b', 'c']


// Objects: deduplicate by a specific key
function uniqueBy(arr, key) {
    const seen = new Set();
    return arr.filter((item) => {
        const val = typeof key === 'function' ? key(item) : item[key];
        if (seen.has(val)) return false;
        seen.add(val);
        return true;
    });
}

const users = [
    { id: 1, name: 'Alice' },
    { id: 2, name: 'Bob' },
    { id: 1, name: 'Alice (dup)' },
];

console.log(uniqueBy(users, 'id'));
// [{ id: 1, name: 'Alice' }, { id: 2, name: 'Bob' }]

// With a function key
uniqueBy(dates, (d) => d.toDateString());
CODE,
            ],
            [
                'title' => 'Flexbox Center',
                'language' => 'CSS',
                'description' => 'The simplest way to perfectly center any content both horizontally and vertically using three Flexbox properties.',
                'tags' => ['css', 'flexbox', 'layout'],
                'code' => <<<'CODE'
/* Center anything — horizontally and vertically */
.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

/* Center with column direction (stacked items) */
.flex-center-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    gap: 1rem;
}

/* Center with wrapping (responsive grid of items) */
.flex-center-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    padding: 2rem;
}

/* Modern alternative: place-items on grid */
.grid-center {
    display: grid;
    place-items: center;
    min-height: 100vh;
}
CODE,
            ],
            [
                'title' => 'CSS Custom Scrollbar',
                'language' => 'CSS',
                'description' => 'Style browser scrollbars with custom widths, colors, and rounded corners for a polished UI — works in all Chromium browsers and Firefox.',
                'tags' => ['css', 'ui', 'scrollbar'],
                'code' => <<<'CODE'
/* Chromium browsers (Chrome, Edge, Brave, Opera) */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #6366f1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #4f46e5;
}

/* Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: #6366f1 transparent;
}

/* Apply only to a specific container */
.code-panel {
    overflow-y: auto;
    max-height: 400px;
    scrollbar-width: thin;
    scrollbar-color: #94a3b8 #f1f5f9;
}

.code-panel::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 3px;
}
CODE,
            ],
            [
                'title' => 'Responsive Container with CSS',
                'language' => 'CSS',
                'description' => 'A modern responsive container using min() and margin-inline that centers content with a max-width and automatic side padding — no media queries needed.',
                'tags' => ['css', 'layout', 'responsive'],
                'code' => <<<'CODE'
/* Modern responsive container — no media queries */
.container {
    width: min(1200px, 100% - 2rem);
    margin-inline: auto;
}

/* Narrow container for text content */
.container-narrow {
    width: min(720px, 100% - 2rem);
    margin-inline: auto;
}

/* Wide container for dashboards */
.container-wide {
    width: min(1440px, 100% - 3rem);
    margin-inline: auto;
}

/* With fluid padding that scales with viewport */
.container-fluid {
    width: min(1200px, 100% - clamp(1rem, 5vw, 3rem));
    margin-inline: auto;
}

/*
  Why this works:
  - min() picks the smaller of the two values
  - On wide screens: uses 1200px (the max width)
  - On narrow screens: uses 100% - 2rem (full width minus padding)
  - margin-inline: auto centers it horizontally
  - No breakpoints needed — it's inherently responsive
*/
CODE,
            ],
            [
                'title' => 'Git Undo Last Commit (Keep Changes)',
                'language' => 'Bash',
                'description' => 'Undo the most recent Git commit while keeping all changes staged in the working directory — safe to use before pushing.',
                'tags' => ['git', 'terminal', 'version-control'],
                'code' => <<<'CODE'
# Undo last commit, KEEP changes staged
git reset --soft HEAD~1

# Undo last commit, KEEP changes but unstaged
git reset --mixed HEAD~1   # (or just: git reset HEAD~1)

# Undo last commit, DISCARD all changes (dangerous!)
git reset --hard HEAD~1

# Undo last N commits (keep changes)
git reset --soft HEAD~3

# Safer alternative: create a new "undo" commit
# (better for shared branches since it doesn't rewrite history)
git revert HEAD

# Oops, I already pushed! Undo safely:
git revert HEAD
git push

# Check what you're about to undo
git log --oneline -5

# Recover if you went too far (reflog saves everything)
git reflog
git reset --soft HEAD@{1}
CODE,
            ],
            [
                'title' => 'Find Large Files in Directory',
                'language' => 'Bash',
                'description' => 'Recursively find the largest files in a directory tree, sorted by size — useful for cleaning up disk space or finding bloated assets.',
                'tags' => ['bash', 'terminal', 'devops'],
                'code' => <<<'CODE'
# Find files larger than 10MB, sorted by size
find . -type f -size +10M -exec ls -lh {} + | sort -k5 -rh | head -20

# macOS version (uses stat differently)
find . -type f -size +10M -print0 | xargs -0 ls -lhS | head -20

# Find large files with human-readable output using du
du -ah . | sort -rh | head -20

# Find large files by extension
find . -name "*.log" -size +5M -exec ls -lh {} +
find . -name "*.sql" -size +50M

# Find and delete old log files larger than 10MB
find /var/log -name "*.log" -size +10M -mtime +30 -delete

# Disk usage summary by directory (top 10)
du -sh */ | sort -rh | head -10

# Find large files in a git repo (including history)
git rev-list --objects --all \
  | git cat-file --batch-check='%(objecttype) %(objectname) %(objectsize) %(rest)' \
  | awk '/^blob/ {print $3, $4}' \
  | sort -rn \
  | head -20
CODE,
            ],
        ];

        foreach ($snippets as $data) {
            $tags = $data['tags'];
            unset($data['tags']);

            $slug = Str::slug($data['title']);
            $originalSlug = $slug;
            $counter = 1;
            while (Snippet::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $snippet = Snippet::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'user_id' => 1,
                    'slug' => $slug,
                    'is_public' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                ])
            );

            $tagIds = collect($tags)->map(function ($name) {
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                )->id;
            });

            $snippet->tags()->sync($tagIds);
        }
    }
}
