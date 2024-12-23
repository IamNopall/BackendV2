@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-10 font-sans">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Create Post</h1>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            @csrf

            <!-- Title Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Title</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Content Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Content</label>
                <textarea name="content" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5"></textarea>
            </div>

            <!-- Category Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Category</label>
                <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Tag Field -->
            <div class="mb-4">
                <label for="tags" class="block text-gray-700 font-semibold mb-2">Tags (separate by commas)</label>
                <input type="text" name="tags" id="tags" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter tags, separated by commas" required>
            </div>

            <!-- Status Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <!-- Image Upload Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Image</label>
                <input type="file" name="image" class="w-full text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-500 file:text-white hover:file:bg-blue-600">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 rounded hover:bg-blue-600 transition-colors duration-200">
                Create
            </button>
        </form>
    </div>
@endsection
