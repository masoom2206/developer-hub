<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    private array $colors = [
        ['#4f46e5','#7c3aed'],
        ['#0891b2','#6366f1'],
        ['#eab308','#f97316'],
        ['#10b981','#059669'],
        ['#6366f1','#8b5cf6'],
        ['#dc2626','#f59e0b'],
        ['#0ea5e9','#7c3aed'],
        ['#0ea5e9','#22d3ee'],
        ['#14b8a6','#06b6d4'],
        ['#8b5cf6','#6366f1'],
    ];

    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $posts = $this->getPosts($categories);

        foreach ($posts as $i => $postData) {
            $tags = $postData['tags'];
            unset($postData['tags']);

            // Generate featured image
            $imagePath = 'posts/' . $postData['slug'] . '.svg';
            $postData['featured_image'] = $imagePath;
            $this->generateCoverImage($postData['title'], $categories->search($postData['category_id']) ?: 'Dev', $imagePath, $i);

            $post = Post::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );

            $tagIds = collect($tags)->map(function ($name) {
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                )->id;
            });

            $post->tags()->sync($tagIds);
        }
    }

    private function generateCoverImage(string $title, string $category, string $path, int $index): void
    {
        $c = $this->colors[$index % count($this->colors)];
        $catName = Category::find(Category::pluck('id', 'slug')->get($category))?->name ?? ucfirst($category);

        $words = explode(' ', $title);
        $mid = min(4, (int) ceil(count($words) / 2));
        $line1 = htmlspecialchars(implode(' ', array_slice($words, 0, $mid)));
        $line2 = htmlspecialchars(implode(' ', array_slice($words, $mid)));

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">'
            . '<defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">'
            . '<stop offset="0%" style="stop-color:' . $c[0] . '"/>'
            . '<stop offset="100%" style="stop-color:' . $c[1] . '"/>'
            . '</linearGradient></defs>'
            . '<rect width="1200" height="630" fill="url(#g)"/>'
            . '<g transform="translate(540,140)" opacity="0.1"><svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg></g>'
            . '<text x="600" y="340" text-anchor="middle" fill="white" font-family="sans-serif" font-size="42" font-weight="700">' . $line1 . '</text>'
            . '<text x="600" y="395" text-anchor="middle" fill="white" font-family="sans-serif" font-size="42" font-weight="700">' . $line2 . '</text>'
            . '<text x="600" y="460" text-anchor="middle" fill="rgba(255,255,255,0.6)" font-family="sans-serif" font-size="22">' . htmlspecialchars($catName) . ' — DevHub</text>'
            . '</svg>';

        Storage::disk('public')->put($path, $svg);
    }

    private function getPosts($categories): array
    {
        return [
            // ── Post 1 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['laravel'],
                'title' => '10 Laravel Eloquent Tips Every Developer Should Know in 2026',
                'slug' => '10-laravel-eloquent-tips-every-developer-should-know-in-2026',
                'excerpt' => 'Master these 10 Eloquent techniques to write cleaner, faster Laravel code. From eager loading to model observers, these tips will level up your database game.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(500, 2000),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['laravel', 'eloquent', 'php'],
                'content' => <<<'HTML'
<p>Laravel's Eloquent ORM is one of the most powerful tools in the PHP ecosystem. But many developers only scratch the surface. In this post, we'll explore 10 Eloquent techniques that can make your code cleaner, more efficient, and easier to maintain.</p>

<h2>1. Eager Loading to Prevent N+1 Queries</h2>
<p>The N+1 query problem is the single most common performance issue in Laravel applications. Every time you access a relationship inside a loop without eager loading, Eloquent fires a separate query.</p>
<pre><code class="language-php">// Bad: N+1 — fires 1 + N queries
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name; // query per iteration
}

// Good: Eager loaded — fires exactly 2 queries
$posts = Post::with('author')->get();
foreach ($posts as $post) {
    echo $post->author->name; // no extra query
}</code></pre>
<p>You can also use <code>withCount</code> when you only need the number of related records rather than the records themselves. This adds a single aggregate subquery instead of loading full models.</p>

<h2>2. Query Scopes for Reusable Filters</h2>
<p>Instead of repeating the same where clauses across your codebase, encapsulate them in query scopes. Local scopes keep your controllers thin and your queries readable.</p>
<pre><code class="language-php">// In the Post model
public function scopePublished(Builder $query): Builder
{
    return $query->where('status', 'published')
                 ->whereNotNull('published_at');
}

public function scopePopular(Builder $query, int $minViews = 100): Builder
{
    return $query->where('views', '>=', $minViews);
}

// Usage — reads like English
$trending = Post::published()->popular(500)->latest()->get();</code></pre>

<h2>3. Accessors and Mutators with the Attribute Cast</h2>
<p>Laravel 11 uses the <code>Attribute</code> class for defining accessors and mutators. This approach keeps getter and setter logic together in a single method, which is much cleaner than the old get/set prefix convention.</p>
<pre><code class="language-php">use Illuminate\Database\Eloquent\Casts\Attribute;

protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn () => "{$this->first_name} {$this->last_name}",
        set: fn (string $value) => [
            'first_name' => explode(' ', $value)[0],
            'last_name'  => explode(' ', $value)[1] ?? '',
        ],
    );
}</code></pre>

<h2>4. firstOrCreate and updateOrCreate</h2>
<p>These methods eliminate the repetitive pattern of checking if a record exists before creating or updating it. They are atomic and handle race conditions gracefully.</p>
<pre><code class="language-php">// Find by email or create a new user
$user = User::firstOrCreate(
    ['email' => 'dev@example.com'],
    ['name' => 'New Developer', 'role' => 'user']
);

// Update if found, create if not
$metric = DailyMetric::updateOrCreate(
    ['date' => today(), 'metric' => 'page_views'],
    ['value' => DB::raw('value + 1')]
);</code></pre>

<h2>5. Upsert for Bulk Operations</h2>
<p>When you need to insert or update thousands of records, <code>upsert</code> does it in a single query instead of looping. The second argument defines the unique columns, and the third specifies which columns to update on conflict.</p>
<pre><code class="language-php">Product::upsert([
    ['sku' => 'A001', 'name' => 'Widget', 'price' => 9.99],
    ['sku' => 'A002', 'name' => 'Gadget', 'price' => 19.99],
], uniqueBy: ['sku'], update: ['name', 'price']);</code></pre>

<h2>6. withCount, withSum, and withAvg</h2>
<p>Aggregate subqueries let you add computed columns to your queries without loading the related records. This is far more efficient than loading relationships just to count them.</p>
<pre><code class="language-php">$categories = Category::withCount('posts')
    ->withAvg('posts', 'views')
    ->orderByDesc('posts_count')
    ->get();

// Access: $category->posts_count, $category->posts_avg_views</code></pre>

<h2>7. Lazy Collections for Memory Efficiency</h2>
<p>When processing millions of rows, <code>lazy()</code> uses PHP generators to keep memory usage constant regardless of result set size.</p>
<pre><code class="language-php">// Processes 1 million rows without running out of memory
User::where('active', true)->lazy()->each(function (User $user) {
    $user->sendYearlyReport();
});</code></pre>

<h2>8. Global Scopes</h2>
<p>Global scopes apply automatically to every query on a model. They are perfect for multi-tenancy, soft deletes, or any filter that should always be active.</p>
<pre><code class="language-php">// In a scope class
class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('is_active', true);
    }
}

// In the model
protected static function booted(): void
{
    static::addGlobalScope(new ActiveScope);
}

// Bypass when needed
User::withoutGlobalScope(ActiveScope::class)->get();</code></pre>

<h2>9. Model Observers for Side Effects</h2>
<p>Observers centralise event logic that would otherwise be scattered across controllers. Use them for logging, cache invalidation, sending notifications, or syncing data to external services.</p>
<pre><code class="language-php">class PostObserver
{
    public function created(Post $post): void
    {
        Cache::forget('latest_posts');
        $post->author->notify(new PostPublished($post));
    }

    public function deleted(Post $post): void
    {
        Storage::delete($post->featured_image);
    }
}</code></pre>

<h2>10. Soft Deletes with Pruning</h2>
<p>Soft deletes keep records recoverable, but without pruning they accumulate forever. Laravel's <code>MassPrunable</code> trait lets you automatically clean up old soft-deleted records on a schedule.</p>
<pre><code class="language-php">use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes, MassPrunable;

    public function prunable(): Builder
    {
        // Permanently delete posts trashed more than 60 days ago
        return static::onlyTrashed()
            ->where('deleted_at', '<=', now()->subDays(60));
    }
}</code></pre>
<p>Then schedule the prune command: <code>$schedule->command('model:prune')->daily();</code></p>

<h2>Wrapping Up</h2>
<p>These 10 Eloquent techniques cover the patterns that experienced Laravel developers reach for every day. Start with eager loading and query scopes — they will have the biggest immediate impact on your code quality. Then gradually adopt the rest as your applications grow.</p>
<p>The key takeaway: Eloquent is not just an ORM — it is a toolkit. The more of its features you understand, the less custom code you need to write.</p>
HTML,
            ],

            // ── Post 2 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['css-design'],
                'title' => 'A Complete Guide to CSS Grid Layout in 2026',
                'slug' => 'complete-guide-to-css-grid-layout-2026',
                'excerpt' => 'Learn CSS Grid from the ground up with practical examples. Master grid-template-columns, named areas, auto-fit, and responsive layouts without a single media query.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(300, 1800),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['css', 'grid', 'layout'],
                'content' => <<<'HTML'
<p>CSS Grid is the most powerful layout system available in CSS today. Unlike Flexbox, which works in one dimension, Grid lets you control both rows and columns simultaneously. In this guide, we will build your understanding from basic syntax all the way to advanced responsive patterns.</p>

<h2>The Basic Grid</h2>
<p>Every grid starts with a container and items. You declare a grid container and then define how columns and rows are structured.</p>
<pre><code class="language-css">.container {
    display: grid;
    grid-template-columns: 200px 1fr 200px;
    grid-template-rows: auto 1fr auto;
    gap: 1rem;
}</code></pre>
<p>This creates a classic three-column layout where the sidebar columns are fixed at 200 pixels and the center column stretches to fill the remaining space. The <code>gap</code> property replaces the old <code>grid-gap</code> and creates consistent spacing between all cells.</p>

<h2>The fr Unit</h2>
<p>The <code>fr</code> unit stands for "fraction of available space." It is the backbone of flexible grid layouts. When you write <code>1fr 2fr</code>, the second column gets twice as much space as the first.</p>
<pre><code class="language-css">.container {
    display: grid;
    grid-template-columns: 1fr 3fr;
    /* sidebar takes 25%, main content takes 75% */
}</code></pre>
<p>Unlike percentages, the <code>fr</code> unit automatically accounts for gaps and fixed-size columns. You never need to calculate "100% minus padding minus gap" — the browser handles it.</p>

<h2>Named Grid Areas</h2>
<p>Named areas let you describe your layout visually in CSS, which is remarkably intuitive. Each string represents a row, and each word is a column cell.</p>
<pre><code class="language-css">.container {
    display: grid;
    grid-template-columns: 250px 1fr;
    grid-template-rows: auto 1fr auto;
    grid-template-areas:
        "header  header"
        "sidebar main"
        "footer  footer";
    gap: 1rem;
    min-height: 100vh;
}

.header  { grid-area: header; }
.sidebar { grid-area: sidebar; }
.main    { grid-area: main; }
.footer  { grid-area: footer; }</code></pre>
<p>Reading the <code>grid-template-areas</code> value, you can immediately visualize the page structure. The header and footer span both columns, while the sidebar and main content sit side by side.</p>

<h2>auto-fit vs auto-fill</h2>
<p>These two keywords are the secret to responsive grids without media queries. Both work with the <code>repeat()</code> function and <code>minmax()</code> to create fluid column counts.</p>
<pre><code class="language-css">/* Columns expand to fill available space */
.grid-fit {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

/* Columns hold their minimum size, leaving empty tracks */
.grid-fill {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}</code></pre>
<p>The practical difference: <code>auto-fit</code> collapses empty tracks, so your items stretch to fill the row. <code>auto-fill</code> keeps the empty tracks, so items hold their minimum size even when there is extra space. For most card grids, <code>auto-fit</code> is what you want.</p>

<h2>Responsive Layout Without Media Queries</h2>
<p>Here is a complete responsive card layout using only four lines of grid CSS. No media queries, no breakpoints, no JavaScript.</p>
<pre><code class="language-css">.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 2rem;
}</code></pre>
<p>On a wide screen, this produces four or five columns. On a tablet, it drops to two or three. On a phone, it collapses to a single column. The browser does all the math based on the 300-pixel minimum you specified.</p>

<h2>Spanning Rows and Columns</h2>
<p>Individual grid items can span multiple cells using <code>grid-column</code> and <code>grid-row</code>. This is how you create featured items that stand out in a grid.</p>
<pre><code class="language-css">.featured-card {
    grid-column: span 2;  /* takes up 2 columns */
    grid-row: span 2;     /* takes up 2 rows */
}

/* Or use explicit line numbers */
.full-width-banner {
    grid-column: 1 / -1;  /* spans from first to last column line */
}</code></pre>

<h2>Alignment and Justification</h2>
<p>Grid provides six alignment properties — three for the grid container and three for individual items.</p>
<pre><code class="language-css">.container {
    /* Align all items within their cells */
    justify-items: center;  /* horizontal */
    align-items: center;    /* vertical */

    /* Align the entire grid within the container */
    justify-content: center;
    align-content: center;
}

/* Override for a single item */
.special-item {
    justify-self: end;
    align-self: start;
}</code></pre>

<h2>A Real-World Dashboard Layout</h2>
<p>Let's combine everything into a practical dashboard layout that adapts to any screen size.</p>
<pre><code class="language-css">.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    grid-auto-rows: minmax(150px, auto);
    gap: 1.5rem;
    padding: 1.5rem;
}

.stats-card { /* normal size — 1x1 */ }
.chart-card { grid-column: span 2; grid-row: span 2; }
.table-card { grid-column: 1 / -1; }</code></pre>
<p>The stats cards flow naturally into the grid. The chart takes up a 2×2 area for more visual impact. The table spans the full width at the bottom. On small screens, everything stacks into a single column without any additional code.</p>

<h2>When to Use Grid vs Flexbox</h2>
<p>The rule of thumb is simple. Use <strong>Grid</strong> when you are designing a two-dimensional layout — anything with both rows and columns. Use <strong>Flexbox</strong> when you are aligning items along a single axis — navigation bars, button groups, or centering content. In practice, most pages use both: Grid for the overall page structure and Flexbox for components within each grid cell.</p>

<h2>Conclusion</h2>
<p>CSS Grid has eliminated the need for float hacks, clearfixes, and many media queries. With <code>auto-fit</code>, <code>minmax()</code>, and named areas, you can build sophisticated responsive layouts in remarkably few lines of code. The browser is your layout engine — trust it to do the work.</p>
HTML,
            ],

            // ── Post 3 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['javascript'],
                'title' => 'JavaScript ES2025 Features You Must Know',
                'slug' => 'javascript-es2025-features-you-must-know',
                'excerpt' => 'Explore the most impactful JavaScript features landing in ES2025: new array methods, structuredClone, the Temporal API, and more — with before-and-after code comparisons.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(400, 2000),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['javascript', 'es2025', 'modern-js'],
                'content' => <<<'HTML'
<p>JavaScript keeps evolving, and ES2025 brings several features that will change how you write everyday code. In this post, we will look at the most impactful additions, with practical before-and-after comparisons so you can start using them today.</p>

<h2>1. Array Grouping with Object.groupBy()</h2>
<p>Grouping array elements by a key has always required a manual <code>reduce</code> call. ES2025 makes it a one-liner with <code>Object.groupBy()</code>.</p>
<pre><code class="language-javascript">const products = [
    { name: 'Laptop', category: 'electronics' },
    { name: 'Shirt', category: 'clothing' },
    { name: 'Phone', category: 'electronics' },
    { name: 'Jeans', category: 'clothing' },
];

// Before: manual reduce
const grouped = products.reduce((acc, item) => {
    (acc[item.category] ??= []).push(item);
    return acc;
}, {});

// After: Object.groupBy()
const grouped = Object.groupBy(products, (item) => item.category);
// { electronics: [{...}, {...}], clothing: [{...}, {...}] }</code></pre>
<p>There is also <code>Map.groupBy()</code> when you need non-string keys or want a Map instead of a plain object.</p>

<h2>2. The at() Method for Negative Indexing</h2>
<p>Accessing the last element of an array used to require <code>arr[arr.length - 1]</code>. The <code>at()</code> method accepts negative indices, making this cleaner.</p>
<pre><code class="language-javascript">const colors = ['red', 'green', 'blue', 'purple'];

// Before
const last = colors[colors.length - 1]; // 'purple'
const secondLast = colors[colors.length - 2]; // 'blue'

// After
const last = colors.at(-1); // 'purple'
const secondLast = colors.at(-2); // 'blue'</code></pre>
<p>This works on arrays, strings, and TypedArrays. It is a small change that eliminates an entire class of off-by-one mental math.</p>

<h2>3. structuredClone() for Deep Copying</h2>
<p>Before <code>structuredClone</code>, deep cloning an object required hacks like <code>JSON.parse(JSON.stringify(obj))</code>, which failed on dates, maps, sets, and circular references.</p>
<pre><code class="language-javascript">const original = {
    name: 'Config',
    created: new Date(),
    tags: new Set(['a', 'b']),
    nested: { deep: { value: 42 } },
};

// Before: JSON hack loses Date and Set
const broken = JSON.parse(JSON.stringify(original));
// broken.created is a string, broken.tags is empty {}

// After: structuredClone preserves types
const clone = structuredClone(original);
// clone.created is a Date, clone.tags is a Set
clone.nested.deep.value = 99;
console.log(original.nested.deep.value); // still 42</code></pre>
<p>It handles Date objects, RegExp, Map, Set, ArrayBuffer, and even circular references. This should be your default deep copy method going forward.</p>

<h2>4. Promise.withResolvers()</h2>
<p>Sometimes you need to resolve or reject a promise from outside its constructor callback. The old pattern was awkward — you had to hoist variables into the outer scope.</p>
<pre><code class="language-javascript">// Before: awkward variable hoisting
let resolve, reject;
const promise = new Promise((res, rej) => {
    resolve = res;
    reject = rej;
});

// After: clean destructuring
const { promise, resolve, reject } = Promise.withResolvers();

// Now you can resolve from anywhere
setTimeout(() => resolve('done!'), 1000);</code></pre>
<p>This is especially useful in event-driven architectures where the resolution trigger is far from the promise creation site.</p>

<h2>5. Temporal API Basics</h2>
<p>The Temporal API is the long-awaited replacement for the notoriously unreliable <code>Date</code> object. It introduces immutable, timezone-aware date and time types.</p>
<pre><code class="language-javascript">// Current date and time in a specific timezone
const now = Temporal.Now.zonedDateTimeISO('America/New_York');

// Create a specific date
const launch = Temporal.PlainDate.from('2026-03-15');

// Date arithmetic that actually works
const deadline = launch.add({ days: 30, hours: 12 });

// Duration between two dates
const diff = launch.until(deadline);
console.log(diff.toString()); // P30DT12H

// Compare without timezone bugs
const isAfter = Temporal.PlainDate.compare(deadline, launch) > 0;</code></pre>
<p>Key types to know: <code>PlainDate</code> (date only), <code>PlainTime</code> (time only), <code>PlainDateTime</code> (date and time without timezone), and <code>ZonedDateTime</code> (full timezone-aware representation). All types are immutable — every operation returns a new instance.</p>

<h2>6. New Set Methods</h2>
<p>Sets finally get the mathematical operations that developers have been building manually for years.</p>
<pre><code class="language-javascript">const frontend = new Set(['js', 'css', 'html', 'react']);
const backend = new Set(['js', 'php', 'python', 'sql']);

// Elements in both sets
frontend.intersection(backend);    // Set {'js'}

// Elements in either set
frontend.union(backend);           // Set {'js','css','html','react','php','python','sql'}

// Elements in frontend but not backend
frontend.difference(backend);      // Set {'css', 'html', 'react'}

// Elements in either but not both
frontend.symmetricDifference(backend); // Set {'css','html','react','php','python','sql'}

// Subset / superset checks
frontend.isSubsetOf(backend);      // false
frontend.isSupersetOf(new Set(['js', 'css'])); // true</code></pre>

<h2>7. Decorators</h2>
<p>Stage 3 decorators bring standardized metadata and behavior modification to classes. They are already widely used in TypeScript and frameworks like Angular.</p>
<pre><code class="language-javascript">function logged(originalMethod, context) {
    return function (...args) {
        console.log(`Calling ${context.name} with`, args);
        const result = originalMethod.call(this, ...args);
        console.log(`${context.name} returned`, result);
        return result;
    };
}

class Calculator {
    @logged
    add(a, b) {
        return a + b;
    }
}

new Calculator().add(2, 3);
// Calling add with [2, 3]
// add returned 5</code></pre>

<h2>8. Regular Expression v Flag</h2>
<p>The <code>v</code> flag (unicodeSets mode) enables set operations inside character classes, making complex Unicode matching much simpler.</p>
<pre><code class="language-javascript">// Match any emoji
const emojiRegex = /[\p{Emoji}--\p{ASCII}]/v;
emojiRegex.test('😀'); // true
emojiRegex.test('A');   // false

// Intersection: Greek letters that are also math symbols
const greekMath = /[\p{Script=Greek}&&\p{Math}]/v;</code></pre>

<h2>Wrapping Up</h2>
<p>ES2025 is one of the most practical releases in recent years. <code>Object.groupBy()</code> and the Set methods eliminate utility library dependencies. <code>structuredClone()</code> kills the JSON deep-copy hack. And the Temporal API finally makes date handling sane.</p>
<p>The best part: most of these features are already available in modern browsers and Node.js 22+. Start using them in your projects today.</p>
HTML,
            ],

            // ── Post 4 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['web-development'],
                'title' => 'How to Write Clean PHP Code: 12 Best Practices',
                'slug' => 'how-to-write-clean-php-code-12-best-practices',
                'excerpt' => 'Writing clean PHP is not about following arbitrary rules — it is about making your code readable, maintainable, and testable. Here are 12 practices that make a real difference.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(200, 1500),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['php', 'clean-code', 'best-practices'],
                'content' => <<<'HTML'
<p>Clean code is code that is easy to read, easy to understand, and easy to change. In PHP — a language that has evolved enormously over the past decade — writing clean code means taking advantage of modern features while following time-tested principles. Here are 12 practices that will measurably improve your PHP codebase.</p>

<h2>1. Use Strict Type Declarations</h2>
<p>Start every PHP file with <code>declare(strict_types=1)</code>. This forces PHP to enforce type declarations strictly, catching type mismatches at call time instead of silently coercing values.</p>
<pre><code class="language-php">declare(strict_types=1);

function calculateTotal(float $price, int $quantity): float
{
    return $price * $quantity;
}

calculateTotal('10.50', 3); // TypeError — caught immediately</code></pre>

<h2>2. Meaningful Names Over Comments</h2>
<p>A well-named variable or function eliminates the need for a comment. If you need a comment to explain what a variable holds, rename the variable instead.</p>
<pre><code class="language-php">// Bad
$d = 86400; // seconds in a day

// Good
$secondsInOneDay = 86400;

// Bad
function process($data) { ... }

// Good
function sendWelcomeEmail(User $newUser): void { ... }</code></pre>

<h2>3. Early Returns to Reduce Nesting</h2>
<p>Guard clauses at the top of a function handle edge cases first and return early. This eliminates deep nesting and makes the happy path obvious.</p>
<pre><code class="language-php">// Bad: deeply nested
function getDiscount(User $user): float
{
    if ($user->isActive()) {
        if ($user->isVip()) {
            return 0.20;
        } else {
            return 0.10;
        }
    } else {
        return 0;
    }
}

// Good: early returns
function getDiscount(User $user): float
{
    if (! $user->isActive()) {
        return 0;
    }

    if ($user->isVip()) {
        return 0.20;
    }

    return 0.10;
}</code></pre>

<h2>4. Use Enums Instead of Magic Strings</h2>
<p>PHP 8.1 introduced native enums. Use them everywhere you would otherwise pass string or integer constants. They provide autocompletion, type safety, and eliminate typo-related bugs.</p>
<pre><code class="language-php">enum OrderStatus: string
{
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Shipped   = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}

function updateOrder(Order $order, OrderStatus $status): void
{
    $order->status = $status->value;
    $order->save();
}

// Typos are now compile-time errors
updateOrder($order, OrderStatus::Shipped);</code></pre>

<h2>5. Single Responsibility Principle</h2>
<p>Each class and method should do one thing. If you find a method that validates input, queries the database, and sends an email, split it into three methods or services.</p>
<pre><code class="language-php">// Bad: one method doing three jobs
public function register(Request $request): Response
{
    // validate, create user, send email — all in one
}

// Good: each concern is separate
public function register(RegisterRequest $request): Response
{
    $user = $this->userService->create($request->validated());
    $this->emailService->sendWelcome($user);
    return response()->json($user, 201);
}</code></pre>

<h2>6. Return Types and Union Types</h2>
<p>Always declare return types. If a method can return different types, use union types or null-safe patterns rather than leaving the return type undeclared.</p>
<pre><code class="language-php">function findUser(int $id): ?User
{
    return User::find($id); // returns User or null
}

function parseInput(string|int $value): string
{
    return (string) $value;
}</code></pre>

<h2>7. Value Objects Over Primitive Obsession</h2>
<p>Instead of passing raw strings for concepts like email addresses, money, or coordinates, wrap them in value objects. This makes invalid states unrepresentable.</p>
<pre><code class="language-php">final readonly class EmailAddress
{
    public function __construct(public string $value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: {$value}");
        }
    }
}

// Usage: impossible to pass a non-email string
function invite(EmailAddress $email): void { ... }</code></pre>

<h2>8. Proper Exception Handling</h2>
<p>Catch specific exceptions, not <code>Exception</code> or <code>Throwable</code>. Create custom exceptions that describe the domain error. Never swallow exceptions silently.</p>
<pre><code class="language-php">// Bad
try { $result = $api->call(); }
catch (Exception $e) { /* silently ignored */ }

// Good
try {
    $result = $api->fetchUser($id);
} catch (ApiRateLimitException $e) {
    return retry(fn () => $api->fetchUser($id), delay: 1000);
} catch (ApiNotFoundException $e) {
    throw UserNotFoundException::fromApi($id, $e);
}</code></pre>

<h2>9. Readonly Properties and Classes</h2>
<p>Mark properties as <code>readonly</code> when they should not change after construction. In PHP 8.2+, you can mark an entire class as <code>readonly</code>.</p>
<pre><code class="language-php">final readonly class Product
{
    public function __construct(
        public string $name,
        public float $price,
        public string $sku,
    ) {}
}</code></pre>

<h2>10. Avoid Static Methods for Business Logic</h2>
<p>Static methods create hidden dependencies and make code harder to test. Use dependency injection instead. Reserve static methods for pure utility functions.</p>

<h2>11. Use Constructor Promotion</h2>
<p>Constructor promotion reduces boilerplate for data classes and service constructors.</p>
<pre><code class="language-php">// Before: verbose
class OrderService
{
    private PaymentGateway $gateway;
    private Logger $logger;

    public function __construct(PaymentGateway $gateway, Logger $logger)
    {
        $this->gateway = $gateway;
        $this->logger = $logger;
    }
}

// After: promoted
class OrderService
{
    public function __construct(
        private readonly PaymentGateway $gateway,
        private readonly Logger $logger,
    ) {}
}</code></pre>

<h2>12. Write Testable Code</h2>
<p>If your code is hard to test, it is a signal that the design needs improvement. Inject dependencies, avoid global state, and keep methods small enough that each test covers a single behavior.</p>

<h2>Conclusion</h2>
<p>Clean PHP code is not about perfection — it is about consistency and intentionality. Pick the practices that matter most for your current project, apply them incrementally, and your codebase will thank you six months from now.</p>
HTML,
            ],

            // ── Post 5 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['devtools'],
                'title' => 'Git Commands Every Developer Uses Daily',
                'slug' => 'git-commands-every-developer-uses-daily',
                'excerpt' => 'Go beyond git add and git commit. Learn stash, cherry-pick, rebase, bisect, reflog, worktrees, aliases, and hooks — the Git commands that separate beginners from pros.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(300, 1900),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['git', 'version-control', 'terminal'],
                'content' => <<<'HTML'
<p>Most developers know <code>git add</code>, <code>git commit</code>, and <code>git push</code>. But Git has a rich set of commands that can save you hours of work when you know how to use them. This post covers the commands that experienced developers reach for every day.</p>

<h2>1. git stash — Shelve Work in Progress</h2>
<p>You are halfway through a feature when an urgent bug report comes in. You don't want to commit half-finished code, and you don't want to lose your changes. <code>git stash</code> saves your working directory and staged changes to a temporary stack.</p>
<pre><code class="language-bash"># Save current changes
git stash

# Save with a descriptive message
git stash push -m "WIP: user avatar upload"

# List all stashes
git stash list

# Restore the most recent stash
git stash pop

# Restore a specific stash
git stash pop stash@{2}

# Apply without removing from stash stack
git stash apply</code></pre>

<h2>2. git cherry-pick — Apply a Single Commit</h2>
<p>Sometimes you need one specific commit from another branch — not the whole branch. Cherry-pick copies a commit and applies it to your current branch.</p>
<pre><code class="language-bash"># Apply a single commit by its hash
git cherry-pick a1b2c3d

# Cherry-pick without committing (stage only)
git cherry-pick --no-commit a1b2c3d

# Cherry-pick a range of commits
git cherry-pick a1b2c3d..f4e5d6c</code></pre>
<p>Common use case: a critical bugfix was merged into <code>main</code> but you need it on your release branch before the next merge.</p>

<h2>3. git rebase — Rewrite History Cleanly</h2>
<p>Rebase replays your branch's commits on top of another branch, creating a linear history without merge commits.</p>
<pre><code class="language-bash"># Rebase your feature branch onto the latest main
git checkout feature/auth
git rebase main

# Interactive rebase: squash, reword, reorder commits
git rebase -i HEAD~5</code></pre>
<p>Interactive rebase is one of Git's most powerful features. You can squash multiple commits into one, reword commit messages, drop commits entirely, or reorder them. Use it to clean up a messy branch before opening a pull request.</p>

<h2>4. git bisect — Binary Search for Bugs</h2>
<p>When a bug was introduced somewhere in the last 100 commits, bisect uses binary search to find the exact commit. It tests the midpoint, you tell it good or bad, and it narrows the range.</p>
<pre><code class="language-bash"># Start bisecting
git bisect start

# Mark the current commit as bad (has the bug)
git bisect bad

# Mark a known good commit (before the bug)
git bisect good v2.3.0

# Git checks out the midpoint. Test it, then:
git bisect good    # if the bug is not present
git bisect bad     # if the bug is present

# Repeat until Git identifies the exact commit
# When done:
git bisect reset</code></pre>
<p>You can even automate it with a test script: <code>git bisect run ./test.sh</code></p>

<h2>5. git reflog — Undo Almost Anything</h2>
<p>Reflog is your safety net. It records every change to HEAD — commits, rebases, resets, checkouts — even if those changes are no longer reachable from any branch.</p>
<pre><code class="language-bash"># View the reflog
git reflog

# Output looks like:
# a1b2c3d HEAD@{0}: commit: Add login feature
# f4e5d6c HEAD@{1}: rebase: checkout main
# 7g8h9i0 HEAD@{2}: commit: WIP

# Recover a lost commit
git checkout HEAD@{2}

# Or create a branch from it
git branch recovered-work HEAD@{2}</code></pre>
<p>Accidentally ran <code>git reset --hard</code> and lost work? Reflog has your back. The only thing reflog cannot recover is uncommitted, unstaged changes.</p>

<h2>6. git worktree — Multiple Working Directories</h2>
<p>Worktrees let you check out multiple branches simultaneously in separate directories. No more stashing or committing WIP just to switch branches.</p>
<pre><code class="language-bash"># Create a worktree for a hotfix branch
git worktree add ../hotfix-login hotfix/login

# Work in that directory normally
cd ../hotfix-login
# make changes, commit, push

# Remove when done
git worktree remove ../hotfix-login</code></pre>
<p>This is invaluable when you need to compare two branches side by side, or when a long build process blocks your main directory.</p>

<h2>7. Git Aliases — Custom Shortcuts</h2>
<p>If you type a command more than twice a day, alias it. Store aliases in your global <code>.gitconfig</code>.</p>
<pre><code class="language-bash"># Add aliases
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.st "status --short"
git config --global alias.lg "log --oneline --graph --decorate --all"
git config --global alias.undo "reset HEAD~1 --mixed"
git config --global alias.amend "commit --amend --no-edit"

# Usage
git co feature/new    # instead of git checkout
git lg                # pretty log graph
git undo              # undo last commit, keep changes</code></pre>

<h2>8. Git Hooks — Automate Quality Checks</h2>
<p>Git hooks are scripts that run automatically at specific points in the Git workflow. The most commonly used hooks are:</p>
<pre><code class="language-bash"># .git/hooks/pre-commit — runs before each commit
#!/bin/sh
# Run linting
./vendor/bin/pint --test
if [ $? -ne 0 ]; then
    echo "Code style check failed. Please run: ./vendor/bin/pint"
    exit 1
fi

# Run tests
php artisan test --parallel
if [ $? -ne 0 ]; then
    echo "Tests failed. Fix them before committing."
    exit 1
fi</code></pre>
<p>Other useful hooks include <code>pre-push</code> for running the full test suite, <code>commit-msg</code> for enforcing commit message format, and <code>post-merge</code> for auto-running migrations after pulling.</p>

<h2>Bonus: Useful One-Liners</h2>
<pre><code class="language-bash"># See what changed in the last commit
git diff HEAD~1

# Find which commit introduced a specific line
git log -S "function calculateTax" --oneline

# Clean up merged branches
git branch --merged main | grep -v main | xargs git branch -d

# Show file at a specific commit without checking out
git show HEAD~3:src/config.php</code></pre>

<h2>Wrapping Up</h2>
<p>These commands form the toolkit of a productive Git user. Start with <code>stash</code> and <code>reflog</code> — they solve the most common everyday problems. Then gradually add <code>rebase</code>, <code>bisect</code>, and <code>worktree</code> as you encounter the situations they address. The more Git you know, the less time you spend fighting your version control and the more time you spend writing code.</p>
HTML,
            ],

            // ── Post 6 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['laravel'],
                'title' => 'Build a REST API with Laravel 11 — Step by Step',
                'slug' => 'build-rest-api-with-laravel-11-step-by-step',
                'excerpt' => 'A complete guide to building a production-ready REST API in Laravel 11. Covers routing, controllers, resources, validation, Sanctum auth, rate limiting, and versioning.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(600, 2000),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['laravel', 'api', 'rest'],
                'content' => <<<'HTML'
<p>Building a REST API is one of the most common tasks for backend developers. Laravel makes it remarkably straightforward with built-in tools for routing, validation, authentication, and response formatting. In this tutorial, we will build a complete API from scratch using Laravel 11.</p>

<h2>Step 1: Set Up the Project</h2>
<pre><code class="language-bash">composer create-project laravel/laravel api-project
cd api-project
php artisan install:api</code></pre>
<p>The <code>install:api</code> command installs Laravel Sanctum, creates the API route file, and publishes the necessary configuration. It sets up everything you need for token-based authentication.</p>

<h2>Step 2: Create the Model and Migration</h2>
<p>We will build a simple task management API. Generate the model, migration, controller, and form request in a single command.</p>
<pre><code class="language-bash">php artisan make:model Task -mcrR</code></pre>
<p>Define the migration:</p>
<pre><code class="language-php">Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
    $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
    $table->date('due_date')->nullable();
    $table->timestamps();
});</code></pre>

<h2>Step 3: Define API Routes</h2>
<p>Laravel 11 uses the <code>routes/api.php</code> file for API routes. These routes are automatically prefixed with <code>/api</code> and use the <code>api</code> middleware group.</p>
<pre><code class="language-php">use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);</code></pre>
<p>The <code>apiResource</code> method generates index, store, show, update, and destroy routes — all the standard CRUD endpoints without the form-related create and edit routes that APIs don't need.</p>

<h2>Step 4: Build the Controller</h2>
<pre><code class="language-php">class TaskController extends Controller
{
    public function index(Request $request)
    {
        return TaskResource::collection(
            $request->user()->tasks()
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->latest()
                ->paginate(15)
        );
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }
}</code></pre>

<h2>Step 5: Create API Resources</h2>
<p>API Resources transform your Eloquent models into JSON responses. They give you full control over the response shape without polluting your models.</p>
<pre><code class="language-php">class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->toDateString(),
            'is_overdue' => $this->due_date?->isPast() && $this->status !== 'completed',
            'created_at' => $this->created_at->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}</code></pre>

<h2>Step 6: Form Request Validation</h2>
<p>Form requests encapsulate validation logic outside the controller, keeping your controller methods clean.</p>
<pre><code class="language-php">class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth handled by middleware
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'priority' => ['sometimes', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}</code></pre>

<h2>Step 7: Authentication with Sanctum</h2>
<pre><code class="language-php">class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}</code></pre>
<p>Clients include the token in subsequent requests via the <code>Authorization: Bearer {token}</code> header.</p>

<h2>Step 8: Rate Limiting</h2>
<p>Protect your API from abuse by defining rate limits in <code>AppServiceProvider</code>.</p>
<pre><code class="language-php">RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});</code></pre>

<h2>Step 9: API Versioning</h2>
<p>When you need to make breaking changes, version your API by prefixing routes. Keep the old version working while clients migrate.</p>
<pre><code class="language-php">// routes/api.php
Route::prefix('v1')->group(base_path('routes/api_v1.php'));
Route::prefix('v2')->group(base_path('routes/api_v2.php'));</code></pre>

<h2>Conclusion</h2>
<p>Laravel gives you everything you need to build a robust REST API: Sanctum for authentication, Form Requests for validation, API Resources for response shaping, and built-in rate limiting. Follow these patterns and you will have a production-ready API that is secure, well-structured, and easy to maintain.</p>
HTML,
            ],

            // ── Post 7 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['css-design'],
                'title' => 'Tailwind CSS vs Bootstrap in 2026: Which Should You Choose?',
                'slug' => 'tailwind-css-vs-bootstrap-2026-which-should-you-choose',
                'excerpt' => 'An honest side-by-side comparison of Tailwind CSS and Bootstrap covering bundle size, learning curve, customization, performance, and when to use each framework.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(400, 1800),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['tailwind', 'bootstrap', 'css'],
                'content' => <<<'HTML'
<p>The CSS framework debate has evolved significantly. Bootstrap was the undisputed king for nearly a decade, but Tailwind CSS has rapidly gained ground with a fundamentally different approach. In this post, we will compare them honestly across every dimension that matters for real projects in 2026.</p>

<h2>Philosophy: Components vs Utilities</h2>
<p><strong>Bootstrap</strong> gives you pre-built components — navbars, cards, modals, carousels — that you assemble like building blocks. You add a class like <code>btn btn-primary</code> and get a fully styled button immediately.</p>
<p><strong>Tailwind</strong> gives you low-level utility classes — <code>bg-blue-600 text-white px-4 py-2 rounded-lg</code> — that you compose to build your own designs from scratch. There are no pre-built components; you create everything yourself.</p>
<p>This fundamental difference drives every other comparison point below.</p>

<h2>Bundle Size</h2>
<p><strong>Bootstrap 5:</strong> The full CSS file is approximately 230 KB (uncompressed). Even with unused component styles, the entire framework ships to the browser unless you manually configure tree-shaking with Sass.</p>
<p><strong>Tailwind CSS:</strong> The production build only includes classes you actually use, thanks to its JIT compiler. A typical site ships 15-40 KB of CSS. For this site you are reading right now, the entire CSS bundle is under 90 KB — including every page, every component, and every responsive variant.</p>
<p><strong>Winner:</strong> Tailwind, by a significant margin. Smaller CSS means faster page loads and better Core Web Vitals scores.</p>

<h2>Learning Curve</h2>
<p><strong>Bootstrap</strong> is easier to start with. If you know HTML, you can build a decent-looking page in minutes by copy-pasting component examples from the documentation. The class names are semantic and intuitive: <code>container</code>, <code>row</code>, <code>col</code>, <code>alert</code>.</p>
<p><strong>Tailwind</strong> has a steeper initial learning curve. You need to memorize utility class names, understand the spacing scale, and get comfortable with long class lists in your HTML. The first week feels verbose and unfamiliar.</p>
<p>However, after that initial hump, Tailwind developers report moving faster because they never need to context-switch to a separate CSS file or fight framework specificity. Everything is right there in the markup.</p>
<p><strong>Winner:</strong> Bootstrap for absolute beginners. Tailwind for developers who invest a week of learning.</p>

<h2>Customization</h2>
<p><strong>Bootstrap</strong> is customizable through Sass variables. You can override colors, breakpoints, spacing, and font sizes. But customizing component structure or behavior often means fighting the framework's opinions — overriding nested selectors with higher specificity.</p>
<p><strong>Tailwind</strong> is designed for customization. The <code>tailwind.config.js</code> file lets you define your entire design system — colors, fonts, spacing, breakpoints, animations — and every utility class is generated from it. You never fight the framework because there are no pre-built component opinions to override.</p>
<pre><code class="language-javascript">// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            colors: {
                brand: '#4f46e5',
                surface: '#f8fafc',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
};</code></pre>
<p><strong>Winner:</strong> Tailwind. It gives you complete control without fighting defaults.</p>

<h2>Design Consistency</h2>
<p><strong>Bootstrap</strong> sites tend to look similar. The component designs are recognizable, and heavy customization takes significant effort. This can be a pro (consistent, professional look with minimal effort) or a con (generic appearance).</p>
<p><strong>Tailwind</strong> sites look unique by default because you design every component from scratch. Two Tailwind sites built by different developers will look completely different. The tradeoff is that you need design skill or a component library like Headless UI or daisyUI.</p>
<p><strong>Winner:</strong> Tie — depends on whether you want consistency-by-default or uniqueness-by-default.</p>

<h2>Responsive Design</h2>
<p>Both frameworks handle responsive design well, but with different approaches.</p>
<p><strong>Bootstrap</strong> uses a 12-column grid with breakpoint-specific classes: <code>col-md-6 col-lg-4</code>.</p>
<p><strong>Tailwind</strong> uses responsive prefixes on any utility: <code>w-full md:w-1/2 lg:w-1/3</code>. This is more flexible because it applies to any CSS property, not just the grid.</p>
<pre><code class="language-html"><!-- Tailwind: responsive padding, text size, and layout -->
&lt;div class="p-4 md:p-8 lg:p-12 text-sm md:text-base grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"&gt;
    ...
&lt;/div&gt;</code></pre>
<p><strong>Winner:</strong> Tailwind for flexibility. Bootstrap for simplicity.</p>

<h2>Community and Ecosystem</h2>
<p><strong>Bootstrap</strong> has been around since 2011. Its ecosystem is enormous: thousands of themes, templates, admin dashboards, and third-party component libraries. Stack Overflow has answers for virtually every Bootstrap question.</p>
<p><strong>Tailwind</strong> has grown explosively since 2020. It now has a thriving ecosystem including Tailwind UI (official premium components), Headless UI, daisyUI, Flowbite, and thousands of community templates. Its documentation is often praised as the best in the CSS framework space.</p>
<p><strong>Winner:</strong> Tie — both have mature ecosystems in 2026.</p>

<h2>Performance</h2>
<p>Tailwind produces smaller CSS bundles, which means fewer bytes to download, parse, and apply. Bootstrap's larger CSS can impact First Contentful Paint, especially on slower connections.</p>
<p>However, Tailwind's approach of many utility classes in HTML can increase HTML file size. In practice, HTML compresses extremely well with gzip/brotli (repeated class names compress to almost nothing), so this is rarely a real-world concern.</p>
<p><strong>Winner:</strong> Tailwind for overall page weight.</p>

<h2>Decision Flowchart</h2>
<p>Use this text-based flowchart to decide:</p>
<ol>
<li><strong>Do you need a working prototype in hours, not days?</strong> → Bootstrap</li>
<li><strong>Are you building a custom-designed product?</strong> → Tailwind</li>
<li><strong>Is your team unfamiliar with CSS fundamentals?</strong> → Bootstrap (it abstracts more away)</li>
<li><strong>Do you care about production bundle size and Core Web Vitals?</strong> → Tailwind</li>
<li><strong>Are you using a component-based framework (React, Vue, Blade)?</strong> → Tailwind (utilities compose well with components)</li>
<li><strong>Do you need a jQuery-compatible widget library?</strong> → Bootstrap</li>
</ol>

<h2>Conclusion</h2>
<p>There is no universally correct choice. Bootstrap excels when you need speed of initial development and don't want to make design decisions. Tailwind excels when you want complete design control, smaller bundles, and a utility-first workflow. Many teams even use both — Bootstrap for internal admin tools and Tailwind for customer-facing products. Pick the one that matches your project's priorities.</p>
HTML,
            ],

            // ── Post 8 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['web-development'],
                'title' => 'How to Optimize MySQL Queries for Better Performance',
                'slug' => 'how-to-optimize-mysql-queries-for-better-performance',
                'excerpt' => 'Slow queries are the most common cause of application performance problems. Learn how to use EXPLAIN, proper indexing, query optimization, and slow query log analysis.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(250, 1600),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['mysql', 'database', 'performance'],
                'content' => <<<'HTML'
<p>Database queries are often the single biggest bottleneck in web applications. A page that runs 50 queries — or even a single unindexed query on a large table — can turn a sub-second page load into a multi-second ordeal. This guide covers the practical techniques that will have the greatest impact on your MySQL performance.</p>

<h2>1. Always Start with EXPLAIN</h2>
<p><code>EXPLAIN</code> is your diagnostic tool. It shows how MySQL plans to execute a query — which indexes it will use, how many rows it expects to scan, and what join strategies it will employ.</p>
<pre><code class="language-sql">EXPLAIN SELECT p.title, u.name
FROM posts p
JOIN users u ON p.user_id = u.id
WHERE p.status = 'published'
ORDER BY p.created_at DESC
LIMIT 20;</code></pre>
<p>Key columns to examine in the output:</p>
<ul>
<li><strong>type</strong> — The join type. <code>ALL</code> means a full table scan (bad). <code>ref</code> or <code>eq_ref</code> means an index is being used (good). <code>const</code> means a unique index lookup (best).</li>
<li><strong>key</strong> — Which index MySQL chose. If this is NULL, no index is being used.</li>
<li><strong>rows</strong> — The estimated number of rows MySQL will examine. Lower is better.</li>
<li><strong>Extra</strong> — Watch for "Using filesort" (sorting without an index) and "Using temporary" (a temp table was created), both of which are performance warnings.</li>
</ul>

<h2>2. Index the Right Columns</h2>
<p>Indexes are the single most impactful optimization. But indexing every column wastes disk space and slows down writes. Focus on:</p>
<ul>
<li>Columns used in <code>WHERE</code> clauses</li>
<li>Columns used in <code>JOIN</code> conditions</li>
<li>Columns used in <code>ORDER BY</code></li>
<li>Columns used in <code>GROUP BY</code></li>
</ul>
<pre><code class="language-sql">-- Single column index
CREATE INDEX idx_posts_status ON posts(status);

-- Composite index for queries that filter AND sort
CREATE INDEX idx_posts_status_created ON posts(status, created_at DESC);

-- The composite index above handles:
-- WHERE status = 'published' ORDER BY created_at DESC
-- This is much faster than two separate single-column indexes</code></pre>
<p><strong>Column order in composite indexes matters.</strong> MySQL uses indexes left-to-right. An index on <code>(status, created_at)</code> helps queries that filter by status, or filter by status and sort by created_at, but it does NOT help queries that only sort by created_at.</p>

<h2>3. Avoid the N+1 Query Problem</h2>
<p>The N+1 problem occurs when your application runs one query to fetch a list, then one additional query for each item in that list to fetch related data.</p>
<pre><code class="language-php">// N+1: 1 query for posts + 100 queries for users
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->user->name; // fires a query each time
}

// Fixed: 2 queries total (posts + users)
$posts = Post::with('user')->get();
foreach ($posts as $post) {
    echo $post->user->name; // no additional query
}</code></pre>
<p>In Laravel, enable the N+1 detector in development to catch these automatically:</p>
<pre><code class="language-php">// AppServiceProvider
Model::preventLazyLoading(! app()->isProduction());</code></pre>

<h2>4. Select Only the Columns You Need</h2>
<p><code>SELECT *</code> retrieves every column, including large text fields and blobs you might not need. This wastes memory, bandwidth, and prevents MySQL from using covering indexes.</p>
<pre><code class="language-sql">-- Bad: fetches all 20 columns including the full content blob
SELECT * FROM posts WHERE status = 'published';

-- Good: fetches only what the listing page needs
SELECT id, title, excerpt, created_at FROM posts WHERE status = 'published';</code></pre>

<h2>5. Use LIMIT for Pagination</h2>
<p>Never fetch all rows when you only need a page of results. Always use <code>LIMIT</code> with <code>OFFSET</code>, or better yet, cursor-based pagination for large datasets.</p>
<pre><code class="language-sql">-- Offset pagination (fine for small offsets)
SELECT * FROM posts ORDER BY id DESC LIMIT 20 OFFSET 40;

-- Cursor pagination (much faster for deep pages)
SELECT * FROM posts WHERE id < 9500 ORDER BY id DESC LIMIT 20;</code></pre>
<p>Offset pagination degrades as the offset grows because MySQL must scan and discard all the skipped rows. Cursor pagination is consistently fast because it uses an indexed WHERE clause.</p>

<h2>6. Analyze the Slow Query Log</h2>
<p>Enable MySQL's slow query log to automatically capture queries that exceed a time threshold.</p>
<pre><code class="language-sql">-- Enable in MySQL config or at runtime
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 0.5;  -- log queries over 500ms
SET GLOBAL log_queries_not_using_indexes = 'ON';</code></pre>
<p>Then use <code>mysqldumpslow</code> or <code>pt-query-digest</code> (from Percona Toolkit) to analyze the log and find your worst offenders.</p>

<h2>7. Optimize JOINs</h2>
<p>Ensure that both sides of a JOIN condition are indexed. The column types and character sets must match — a join between a VARCHAR(255) and an INT will prevent index usage and force a full scan.</p>
<pre><code class="language-sql">-- Ensure foreign keys have matching types and indexes
ALTER TABLE posts ADD INDEX idx_posts_user_id (user_id);
ALTER TABLE posts ADD INDEX idx_posts_category_id (category_id);</code></pre>

<h2>8. Use Connection Pooling</h2>
<p>Opening a new database connection for every request is expensive. Connection pooling maintains a pool of open connections that are reused across requests. In Laravel, configure this in your database config or use an external pooler like PgBouncer (for PostgreSQL) or ProxySQL (for MySQL).</p>

<h2>Conclusion</h2>
<p>Query optimization follows a clear priority order: first add missing indexes, then fix N+1 queries, then optimize slow individual queries using EXPLAIN. These three steps alone will solve 90% of database performance problems. Enable the slow query log, monitor it regularly, and you will catch performance regressions before they reach production.</p>
HTML,
            ],

            // ── Post 9 ──────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['devtools'],
                'title' => 'Top 10 VS Code Extensions for Web Developers in 2026',
                'slug' => 'top-10-vs-code-extensions-for-web-developers-2026',
                'excerpt' => 'The right VS Code extensions can dramatically boost your productivity. Here are the 10 extensions that every web developer should install in 2026, and why each one matters.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(500, 2000),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['vscode', 'tools', 'productivity'],
                'content' => <<<'HTML'
<p>Visual Studio Code's extension ecosystem is its greatest strength. With thousands of extensions available, choosing the right ones can feel overwhelming. After years of web development with VS Code, these are the 10 extensions I consider essential — each one solves a real problem and saves measurable time every day.</p>

<h2>1. Prettier — Code Formatter</h2>
<p><strong>What it does:</strong> Automatically formats your code on save according to a consistent style. It handles JavaScript, TypeScript, CSS, HTML, JSON, Markdown, and more.</p>
<p><strong>Why it matters:</strong> Code formatting debates are a waste of time. Prettier ends them permanently. Every team member's code looks identical regardless of personal preference. Configure it once in a <code>.prettierrc</code> file and never think about formatting again.</p>
<p><strong>Key setting:</strong> Enable "Format on Save" in VS Code settings and set Prettier as your default formatter. Your code will be automatically reformatted every time you hit Ctrl+S.</p>

<h2>2. ESLint</h2>
<p><strong>What it does:</strong> Highlights JavaScript and TypeScript errors, potential bugs, and style issues in real-time as you type.</p>
<p><strong>Why it matters:</strong> ESLint catches problems that would otherwise only surface at runtime — undefined variables, unused imports, unreachable code, and subtle logical errors. Combined with Prettier, it gives you both formatting and logical correctness.</p>
<p><strong>Tip:</strong> Use the <code>eslint.codeActionsOnSave</code> setting to auto-fix fixable issues on save.</p>

<h2>3. GitLens</h2>
<p><strong>What it does:</strong> Supercharges Git inside VS Code. It shows inline blame annotations, commit history for any file or line, a visual commit graph, and powerful diff comparisons.</p>
<p><strong>Why it matters:</strong> When you see confusing code and wonder "who wrote this and why?", GitLens answers instantly. The inline blame annotation shows the author, commit message, and date for every line — right in the editor, without switching to the terminal.</p>
<p><strong>Favorite feature:</strong> Hover over any line to see the full commit message and a link to the commit diff. It turns "git blame" from a terminal command into a natural part of reading code.</p>

<h2>4. Thunder Client</h2>
<p><strong>What it does:</strong> A lightweight REST API client built directly into VS Code. It replaces Postman for most day-to-day API testing.</p>
<p><strong>Why it matters:</strong> You can test API endpoints without leaving your editor. Create request collections, set environment variables, chain requests, and view responses — all in a VS Code sidebar panel. It supports GraphQL too.</p>
<p><strong>Why over Postman:</strong> It is faster, lighter, and lives where you already work. No context switching, no separate application to manage.</p>

<h2>5. Laravel Blade Snippets + Formatter</h2>
<p><strong>What it does:</strong> Provides syntax highlighting, autocompletion, formatting, and snippets for Laravel Blade templates.</p>
<p><strong>Why it matters:</strong> Blade files without this extension render as plain HTML with confusing highlights. With it, you get proper coloring for directives (<code>@if</code>, <code>@foreach</code>, <code>@extends</code>), auto-closing of Blade tags, and snippet shortcuts that generate common patterns.</p>

<h2>6. PHP Intelephense</h2>
<p><strong>What it does:</strong> A high-performance PHP language server that provides intelligent autocompletion, go-to-definition, find references, hover documentation, error diagnostics, and code formatting.</p>
<p><strong>Why it matters:</strong> It turns VS Code into a full PHP IDE. You get autocompletion that understands your entire codebase, can jump to any class or method definition with Ctrl+Click, and see type errors highlighted in real-time. The free tier covers most needs; the premium license adds advanced refactoring.</p>

<h2>7. Tailwind CSS IntelliSense</h2>
<p><strong>What it does:</strong> Provides autocomplete, syntax highlighting, and linting for Tailwind CSS classes. It reads your <code>tailwind.config.js</code> and suggests classes from your custom configuration.</p>
<p><strong>Why it matters:</strong> Tailwind has hundreds of utility classes. Without IntelliSense, you constantly reference the documentation. With it, you type <code>bg-</code> and see every background color in your palette, with a visual color swatch preview. It also warns you about conflicting classes like <code>p-4 p-8</code>.</p>

<h2>8. Error Lens</h2>
<p><strong>What it does:</strong> Displays error and warning messages inline, directly on the line where they occur, instead of only in the Problems panel at the bottom.</p>
<p><strong>Why it matters:</strong> You see errors immediately without having to hover or check the Problems panel. A red inline message saying "Undefined variable $user" is impossible to miss, while a tiny underline is easy to overlook. This catches bugs the moment you create them.</p>

<h2>9. Auto Rename Tag</h2>
<p><strong>What it does:</strong> Automatically renames the paired HTML/XML tag when you edit one side. Change an opening <code>&lt;div&gt;</code> to <code>&lt;section&gt;</code> and the closing tag updates simultaneously.</p>
<p><strong>Why it matters:</strong> It is a tiny feature that saves a surprising amount of time. Every web developer has experienced the frustration of renaming an opening tag and forgetting the closing tag 50 lines below. This eliminates that entire class of error.</p>

<h2>10. GitHub Copilot</h2>
<p><strong>What it does:</strong> AI-powered code completion that suggests entire lines or functions based on your code context, comments, and function signatures.</p>
<p><strong>Why it matters:</strong> Copilot excels at boilerplate, test generation, and implementing well-known patterns. Writing a migration? It suggests the schema. Writing a test? It generates the assertion. Writing a regular expression? It reads your comment and generates the pattern.</p>
<p><strong>Important caveat:</strong> Always review Copilot suggestions. It can generate plausible-looking code that contains subtle bugs. Treat it as a fast typist, not an infallible programmer.</p>

<h2>Bonus: Settings to Enable</h2>
<p>Beyond extensions, these VS Code settings improve the development experience:</p>
<pre><code class="language-json">{
    "editor.formatOnSave": true,
    "editor.linkedEditing": true,
    "editor.bracketPairColorization.enabled": true,
    "editor.guides.bracketPairs": "active",
    "editor.stickyScroll.enabled": true,
    "files.autoSave": "onFocusChange",
    "emmet.includeLanguages": { "blade": "html" }
}</code></pre>

<h2>Conclusion</h2>
<p>Start with Prettier, ESLint, and your language-specific IntelliSense extension. These three alone will transform your editing experience. Then add the others as you encounter the problems they solve. A well-configured VS Code with the right extensions is genuinely competitive with dedicated IDEs — at a fraction of the resource usage.</p>
HTML,
            ],

            // ── Post 10 ─────────────────────────────────────────────
            [
                'user_id' => 1,
                'category_id' => $categories['career'],
                'title' => 'Understanding Big O Notation: A Practical Guide',
                'slug' => 'understanding-big-o-notation-a-practical-guide',
                'excerpt' => 'Big O notation does not have to be intimidating. This guide explains O(1), O(log n), O(n), O(n log n), and O(n²) with real code examples and practical context for when each matters.',
                'status' => 'published',
                'is_sponsored' => false,
                'views' => rand(350, 1700),
                'featured_image' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
                'tags' => ['algorithms', 'computer-science', 'interviews'],
                'content' => <<<'HTML'
<p>Big O notation describes how the runtime of an algorithm grows as the input size increases. It is not about measuring exact execution time — it is about understanding the scaling pattern. This matters both in technical interviews and in real-world applications when you need to choose between different approaches.</p>

<h2>O(1) — Constant Time</h2>
<p>An O(1) operation takes the same amount of time regardless of input size. It does not matter if you have 10 items or 10 million — the time is constant.</p>
<pre><code class="language-javascript">// Array access by index — O(1)
const item = users[42];

// Hash map lookup — O(1) average
const user = userMap.get('john@example.com');

// Checking array length — O(1)
const count = items.length;</code></pre>
<p><strong>Real-world example:</strong> Looking up a user by their primary key in a database with a proper index is effectively O(1). The index provides direct access regardless of table size.</p>
<p><strong>Visual:</strong> Imagine a bookshelf where every book has a numbered position. Finding book number 42 takes the same time whether the shelf has 100 books or 100,000 books.</p>

<h2>O(log n) — Logarithmic Time</h2>
<p>Logarithmic algorithms cut the problem in half at each step. They are extremely efficient even for very large inputs. Doubling the input size adds only one extra step.</p>
<pre><code class="language-javascript">// Binary search — O(log n)
function binarySearch(sorted, target) {
    let low = 0;
    let high = sorted.length - 1;

    while (low <= high) {
        const mid = Math.floor((low + high) / 2);
        if (sorted[mid] === target) return mid;
        if (sorted[mid] < target) low = mid + 1;
        else high = mid - 1;
    }

    return -1;
}</code></pre>
<p><strong>How fast is this?</strong> A sorted array of one million elements requires at most 20 comparisons. A billion elements requires at most 30 comparisons. Each doubling of the data adds only one more step.</p>
<p><strong>Real-world example:</strong> B-tree index lookups in databases use logarithmic time. That is why a properly indexed query on a table with 100 million rows can still return in milliseconds.</p>

<h2>O(n) — Linear Time</h2>
<p>Linear algorithms examine each element exactly once. If you double the input, the runtime doubles. This is the baseline for any problem that requires looking at every element.</p>
<pre><code class="language-javascript">// Finding the maximum value — O(n)
function findMax(numbers) {
    let max = numbers[0];
    for (const num of numbers) {
        if (num > max) max = num;
    }
    return max;
}

// Filtering an array — O(n)
const adults = users.filter(user => user.age >= 18);</code></pre>
<p><strong>Visual:</strong> Imagine searching for a specific card in an unsorted deck. In the worst case, you must check every single card. With 52 cards, up to 52 checks. With 520 cards, up to 520 checks.</p>
<p><strong>Key insight:</strong> O(n) is not bad. For many problems, it is optimal — you cannot find the minimum of an unsorted array without looking at every element.</p>

<h2>O(n log n) — Linearithmic Time</h2>
<p>This is the sweet spot for comparison-based sorting algorithms. It is the theoretical minimum for general-purpose sorting, meaning you cannot sort arbitrary data faster than this.</p>
<pre><code class="language-javascript">// Merge Sort — O(n log n)
function mergeSort(arr) {
    if (arr.length <= 1) return arr;

    const mid = Math.floor(arr.length / 2);
    const left = mergeSort(arr.slice(0, mid));
    const right = mergeSort(arr.slice(mid));

    return merge(left, right);
}

function merge(left, right) {
    const result = [];
    let i = 0, j = 0;

    while (i < left.length && j < right.length) {
        if (left[i] <= right[j]) result.push(left[i++]);
        else result.push(right[j++]);
    }

    return [...result, ...left.slice(i), ...right.slice(j)];
}</code></pre>
<p><strong>Real-world examples:</strong> JavaScript's <code>Array.sort()</code>, Python's <code>sorted()</code>, and most standard library sort functions use algorithms with O(n log n) average performance.</p>
<p><strong>Performance at scale:</strong> Sorting 1 million items takes about 20 million operations. Sorting 1 billion items takes about 30 billion operations — roughly 30 seconds on modern hardware.</p>

<h2>O(n²) — Quadratic Time</h2>
<p>Quadratic algorithms have nested loops where each element is compared with every other element. They work fine for small inputs but become unusable as data grows.</p>
<pre><code class="language-javascript">// Finding duplicates with brute force — O(n²)
function hasDuplicate(arr) {
    for (let i = 0; i < arr.length; i++) {
        for (let j = i + 1; j < arr.length; j++) {
            if (arr[i] === arr[j]) return true;
        }
    }
    return false;
}

// Better: use a Set — O(n)
function hasDuplicateFast(arr) {
    const seen = new Set();
    for (const item of arr) {
        if (seen.has(item)) return true;
        seen.add(item);
    }
    return false;
}</code></pre>
<p><strong>The cliff:</strong> 1,000 items means 1 million operations (fast). 100,000 items means 10 billion operations (very slow). This is why recognizing and eliminating O(n²) code is one of the most impactful optimizations you can make.</p>

<h2>Comparison at Scale</h2>
<p>Here is how each complexity class performs with different input sizes, measured in operations:</p>
<ul>
<li><strong>n = 100:</strong> O(1) = 1 | O(log n) = 7 | O(n) = 100 | O(n log n) = 664 | O(n²) = 10,000</li>
<li><strong>n = 10,000:</strong> O(1) = 1 | O(log n) = 13 | O(n) = 10,000 | O(n log n) = 132,877 | O(n²) = 100,000,000</li>
<li><strong>n = 1,000,000:</strong> O(1) = 1 | O(log n) = 20 | O(n) = 1M | O(n log n) = 20M | O(n²) = 1,000,000,000,000</li>
</ul>
<p>At one million items, the difference between O(n) and O(n²) is six orders of magnitude — the difference between one second and 11 days.</p>

<h2>When Does This Matter in Practice?</h2>
<p>You do not need to analyze every function you write. Big O matters most when:</p>
<ul>
<li><strong>Processing user-generated data</strong> — list sizes you do not control can grow unpredictably</li>
<li><strong>Database queries</strong> — the difference between an indexed O(log n) lookup and a full scan O(n) is enormous on large tables</li>
<li><strong>API endpoints under load</strong> — an O(n²) operation that takes 50ms with test data might take 50 seconds with production data</li>
<li><strong>Technical interviews</strong> — interviewers expect you to identify and optimize complexity</li>
</ul>

<h2>Conclusion</h2>
<p>Big O is a thinking tool, not a math exercise. When someone asks "will this scale?" they are asking about Big O. The practical skill is pattern recognition: see a nested loop, think O(n²). See a binary search, think O(log n). See a hash map lookup, think O(1). With that intuition, you can make informed decisions about which algorithm to use — and explain those decisions clearly in interviews and code reviews.</p>
HTML,
            ],
        ];
    }
}
