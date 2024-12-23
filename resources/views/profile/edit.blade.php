@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-200">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">Edit Profile</h2>

            @if (session('status'))
                <div class="mb-4 text-green-600 bg-green-100 border border-green-300 rounded-md p-2">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <!-- Name Input -->
                <div class="mb-6">
                    <label for="name" class="block text-lg font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>

                <!-- Email Display -->
                <div class="mb-6">
                    <label for="email" class="block text-lg font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" readonly
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed">
                </div>

                <!-- Update Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
