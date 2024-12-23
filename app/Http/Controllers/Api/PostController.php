<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::with('category', 'tags')->get();

        // Tambahkan properti image_url untuk setiap post
        $posts->each(function ($post) {
            $post->image_url = $post->image ? asset('storage/' . $post->image) : null;
        });

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Ditemukan',
            'data' => $posts
        ], 200);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,archived',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Menyimpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Menyimpan post
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'user_id' => 1,
            'image' => $imagePath ? $imagePath : null,
        ]);

        // Tambahkan properti image_url
        $post->image_url = $post->image ? asset('storage/' . $post->image) : null;

        // Menyimpan tags jika ada
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $tags = array_map('trim', $tags);
            $tagIds = [];

            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            $post->tags()->sync($tagIds);
        }

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    /**
     * Display the specified post.
     */
    public function show(string $id)
    {
        $post = Post::with('category', 'tags')->find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
                'data' => null
            ], 404);
        }

        // Tambahkan URL gambar
        $post->image_url = $post->image ? asset('storage/' . $post->image) : null;

        return response()->json([
            'status' => true,
            'message' => 'Post found',
            'data' => $post
        ], 200);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:published,archived',
            'category_id' => 'sometimes|exists:categories,id',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048',
        ]);

        $post->update($validated);

        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $tags = array_map('trim', $tags);
            $tagIds = [];

            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            $post->tags()->sync($tagIds);
        }

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $post->update(['image' => $imagePath]);
        }

        // Tambahkan properti image_url
        $post->image_url = $post->image ? asset('storage/' . $post->image) : null;

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
                'data' => null
            ], 404);
        }

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully',
            'data' => null
        ], 200);
    }


    public function getCategories()
    {
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'message' => 'Categories loaded successfully',
            'data' => $categories,
        ]);
    }
}
