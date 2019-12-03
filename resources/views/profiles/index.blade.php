@extends('layouts.master')

@section('title')
    Zindo | {{ $user->name }}
@endsection

@section('content')
    <div class="profile-nav">
        <div class="container">
            <div class="flex items-center justify-between profile-nav-responsive">
                <div class="bio w-1/3 flex items-center relative">
                    <div class="avatar">
                        <img
                            src="{{ $user->profile->avatar ? '/storage/' . $user->profile->avatar : '/images/avatar.svg' }}"
                            alt="Avatar">
                    </div>
                    <div class="biography">

                        <h4 class="text-gray-800 text-xl ml-1">{{ $user->name }}</h4>

                        <p class="text-gray-600 text-sm ml-1">{{ '@' . $user->username }}</p>

                        <p class="ml-2 text-gray-500 text-sm mt-1">{{ $user->profile->bio }}</p>

                    </div>
                </div>
                <div class="follow-features w-2/3 flex items-center justify-between">
                    <user-info
                        class="{{ strlen($user->profile->bio) > 48 ? 'ml-24' : 'ml-8' }}"
                        :add_to_followings="addFollowings"
                        :sub_of_followers="subFollowers"
                        :add_to_posts="addPosts"
                        :follow_count="{{ collect($user->followCount) }}">
                    </user-info>

                    @auth
                        <follow-feature
                        has_requested_following="{{ auth()->user()->hasRequestedFollowing($user) ? 'true' : 'false' }}"
                        has_requested_follower="{{ auth()->user()->hasRequestedFollower($user) ? 'true' : 'false' }}"
                        is_following="{{ auth()->user()->isFollowing($user) ? 'true' : 'false' }}"
                        is_follower="{{ auth()->user()->isFollower($user) ? 'true' : 'false' }}"
                        :auth="{{ auth()->user() }}"
                        :user="{{ $user }}"
                        @add_to_following="attachToFollowings"
                        @sub_of_followers="detachOfFollowers"
                        >
                        </follow-feature>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @if(auth()->user() != null ? !$user->isFollower(auth()->user()) && auth()->id() != $user->id && $user->setting->isPrivate() : $user->setting->isPrivate() && auth()->guest())
                <div class="w-full h-full text-center pt-32">
                    <img src="/images/access-denied.png" class="mx-auto">
                    <h2 class="text-2xl text-gray-700 mt-8">This Account is Private</h2>
                    <p class="text-lg text-gray-500">Follow this account to see their photos and videos</p>
                </div>
            @else
                <posts @add_to_posts="attachToPosts" :auth="{{ auth()->id() != null ? auth()->id() : 0 }}" :user="{{ $user }}"></posts>
        @endif
    </div>
@endsection
{{--auth()->guest() ? false : $user->isFollower(auth()->user())--}}
