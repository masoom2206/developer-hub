<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create an account</h2>
        <p class="text-sm text-gray-500 mt-1">Join the DevHub developer community</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            {{ __('Create account') }}
        </button>

        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Sign in</a>
        </p>
    </form>
</x-guest-layout>
