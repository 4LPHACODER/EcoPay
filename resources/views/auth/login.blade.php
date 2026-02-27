<x-auth-layout>
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('EcoPay.png') }}" alt="EcoPay" class="h-24 w-auto">
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
    <p class="text-gray-600 text-sm mb-6">Sign in to your EcoPay account</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <input id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition @error('email') border-red-500 @enderror" 
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <input id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition @error('password') border-red-500 @enderror"
                   type="password" name="password" required autocomplete="current-password">
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700">
                Remember me
            </label>
        </div>

        <!-- Links -->
        <div class="text-sm">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-green-600 hover:text-green-700 font-medium">
                    Forgot your password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
            Sign In
        </button>
    </form>
</x-auth-layout>
