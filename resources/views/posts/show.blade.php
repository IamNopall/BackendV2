@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-lg border border-gray-200 font-sans">
    <!-- Judul Postingan -->
    <h1 class="text-5xl font-extrabold text-gray-900 mb-6">{{ $post->title }}</h1>

    <!-- Kategori dan Status -->
    <div class="flex items-center space-x-4 text-gray-600 text-sm mb-6">
        <span class="bg-blue-100 text-blue-800 font-semibold px-3 py-1 rounded-lg">
            Category: {{ $post->category->name ?? 'Uncategorized' }}
        </span>
        <span class="bg-{{ $post->status === 'published' ? 'green' : 'red' }}-100 text-{{ $post->status === 'published' ? 'green' : 'red' }}-800 font-semibold px-3 py-1 rounded-lg">
            Status: {{ ucfirst($post->status) }}
        </span>
    </div>

    <!-- Konten Postingan -->
    <p class="text-gray-700 text-lg leading-relaxed mb-8">{{ $post->content }}</p>

    <!-- Tampilkan Gambar jika ada -->
    @if($post->image)
        <div class="mb-8">
            <img src="{{ asset('storage/' . $post->image) }}" alt="Image" class="w-full h-auto rounded-lg shadow-md">
        </div>
    @endif

    <!-- Tags -->
    <div class="mt-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Tags</h3>
        <ul class="flex space-x-2">
            @foreach ($post->tags as $tag)
                <li>
                    <a
                        href="{{ route('posts.filterByTag', $tag->id) }}"
                        class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200 hover:text-gray-800 transition">
                        {{ $tag->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Tombol Navigasi Postingan -->
    <div class="flex justify-between mb-8">
        @if ($previousPost)
            <a href="{{ route('posts.show', $previousPost->id) }}" class="text-blue-500 hover:underline">
                &larr; Previous: {{ $previousPost->title }}
            </a>
        @endif
        @if ($nextPost)
            <a href="{{ route('posts.show', $nextPost->id) }}" class="text-blue-500 hover:underline">
                Next: {{ $nextPost->title }} &rarr;
            </a>
        @endif
    </div>

    <!-- Tombol Edit dan Delete -->
    @if ($post->user_id == auth()->id())
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('posts.edit', $post->id) }}" class="flex items-center px-5 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition duration-300">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>

            <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center px-5 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition duration-300">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    @endif

    <!-- Komentar -->
    <div class="mt-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Comments</h3>
        @forelse ($post->comments as $comment)
            <div class="mb-6 p-4 bg-gray-100 border border-gray-300 rounded-lg shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $comment->user->avatar ?? asset('default-avatar.png') }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full">
                        <div>
                            <span class="text-gray-800 font-semibold">{{ $comment->user->name }}</span>
                            <span class="text-gray-500 text-sm"> • {{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if ($comment->user_id == auth()->id())
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition duration-200">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-gray-700">{{ $comment->content }}</p>
            </div>
        @empty
            <p class="text-gray-500">No comments yet.</p>
        @endforelse
    </div>

    <!-- Form Tambah Komentar -->
    <div class="mt-8">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Add a Comment</h4>
        <form action="{{ route('comments.store', $post->id) }}" method="POST">
            @csrf
            <textarea name="content" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Write your comment..." required></textarea>
            <button type="submit" class="mt-4 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                Submit
            </button>
        </form>
    </div>
</div>
@endsection
