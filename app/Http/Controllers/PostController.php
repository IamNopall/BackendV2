<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;

class PostController extends Controller
{

    public function index(Request $request)
    {
        // Filter berdasarkan tag jika parameter "tag" diberikan
        if ($request->has('tag')) {
            $tag = Tag::where('id', $request->tag)->firstOrFail();
            $posts = $tag->posts()->with('category', 'user', 'tags')->latest()->paginate(10);
        } else {
            $posts = Post::with('category', 'user', 'tags')->latest()->paginate(10);
        }

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();  // Mengambil semua kategori
        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
          'title' => 'required|string|max:255',
        'content' => 'required|string',
        'status' => 'required|string|in:published,archived',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'nullable|string',  // Validasi tag
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4048'
        ]);

        // Mengupload gambar jika ada
        if ($request->hasFile('image')) {
            // Simpan gambar di folder public/images
            $imagePath = $request->file('image')->store('images', 'public');

            // Ambil nama file dari path
            $imageName = basename($imagePath);

            // Menyimpan nama gambar di database
            $validatedData['image'] = $imageName;
        }


        // Membuat post baru
        $post = Post::create([
           'title' => $request->title,
           'image' => $imagePath,
        'content' => $request->content,
        'status' => $request->status,
        'user_id' => Auth::id(),
        'category_id' => $request->category_id,
        ]);

        // Memproses tag
    if ($request->tags) {
        $tags = explode(',', $request->tags);  // Memisahkan tag berdasarkan koma
        $tagIds = [];

        foreach ($tags as $tag) {
            // Trim whitespace dan pastikan tag unik
            $tag = trim($tag);

            // Menyimpan tag jika belum ada
            $existingTag = Tag::firstOrCreate(['name' => $tag]);

            // Menambahkan id tag ke array
            $tagIds[] = $existingTag->id;
        }

        // Menyambungkan post dengan tag menggunakan relasi many-to-many
        $post->tags()->sync($tagIds);
    }

    return redirect()->route('posts.index')->with('status', 'Post created successfully');
}

    public function show(Post $post)
    {
        // Mendapatkan post sebelumnya (berdasarkan ID)
    $previousPost = Post::where('id', '<', $post->id)
    ->orderBy('id', 'desc')
    ->first();

// Mendapatkan post berikutnya (berdasarkan ID)
$nextPost = Post::where('id', '>', $post->id)
->orderBy('id', 'asc')
->first();

// Kirim data ke view
return view('posts.show', compact('post', 'previousPost', 'nextPost'));
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'category_id' => 'required',
        'status' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048',
        'tags' => 'nullable|string',  // Validasi tag jika perlu
    ]);

    // Update post
    $post->update([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'category_id' => $validated['category_id'],
        'status' => $validated['status'],
    ]);

    // Handle tags
    $tags = explode(',', $request->tags);
    $tags = array_map('trim', $tags);  // Trim any spaces
    $tagIds = [];

    foreach ($tags as $tagName) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $tagIds[] = $tag->id;
    }

    $post->tags()->sync($tagIds);  // Sync the tags

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('public/images');
        $post->update(['image' => basename($imagePath)]);
    }

    return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
}

    public function destroy(Post $post)
    {
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
    public function filterByTag(Tag $tag)
    {
        $posts = $tag->posts()->with('category', 'user', 'tags')->latest()->paginate(10);

        return view('posts.index', compact('posts'));}
}
