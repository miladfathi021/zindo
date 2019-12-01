@extends('layouts.edit-profile')


@section('content')
    <div class="p-12">
        <div class="w-full bg-white p-8 rounded mb-12">
            <h2 class="pb-4 text-gray-600 font-bold tracking-wider uppercase text-xl">Change Your Name</h2>

            <hr class="mb-8">

            <form action="{{ '/settings/profiles/' . $user->username . '/update' }}" method="POST">
                @CSRF
                @method("PATCH")
                <div class="mb-4 -mx-2">
                    <div class="update-box-responsive mb-4 w-2/3 px-2">
                        <label class="block text-gray-600 mb-1" for="name">Name</label>
                        <input class="w-full bg-gray-200 px-4 py-2 rounded text-gray-700 focus:outline-none" type="text" name="name" value="{{
                old('name') ?: $user->name }}">
                        @error('name')
                            <p class="feedback feedback-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="update-box-responsive mb-4 w-2/3 px-2">
                        <label class="block text-gray-600 mb-1" for="bio">Biography</label>
                        <textarea name="bio" class="w-full h-24 bg-gray-200 px-4 py-2 rounded text-gray-700 text-sm focus:outline-none" style="resize: none;" id="bio">{{ old('bio') ?: $user->profile->bio ?: '' }}</textarea>
                        @error('bio')
                        <p class="feedback feedback-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <button class="rounded bg-indigo-500 text-white px-4 py-2 hover:bg-indigo-600" type="submit">Save
                        Updates</button>
                </div>
            </form>

            @if(session('updated'))
                <div class="update-message-edit-profile fixed px-6 py-6 bg-green-400 rounded-bl-lg rounded-tl-lg text-white text-lg" style="right: -100%; top: 30%; animation: right-to-left-success 4s; animation-fill-mode: forwards;">{{ session('updated') }}</div>
            @endif
        </div>

        <update-username :user="{{ $user }}">
            @CSRF
            @method("PATCH")
        </update-username>
    </div>
@endsection
