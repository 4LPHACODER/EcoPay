<x-auth-layout>
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('EcoPay.png') }}" alt="EcoPay" class="h-24 w-auto">
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h1>
    <p class="text-gray-600 text-sm mb-6">Join EcoPay and start recycling today</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Full Name
            </label>
            <input id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition @error('name') border-red-500 @enderror"
                   type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <input id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition @error('email') border-red-500 @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
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
                   type="password" name="password" required autocomplete="new-password">
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Confirm Password
            </label>
            <input id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition @error('password_confirmation') border-red-500 @enderror"
                   type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Terms -->
        <div class="flex items-center">
            <input id="terms" type="checkbox" name="terms" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
            <label for="terms" class="ml-2 block text-sm text-gray-700">
                I agree to the
                <a href="#" class="text-green-600 hover:text-green-700 font-medium">Terms & Conditions</a>
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
            Create Account
        </button>
    </form>
</x-auth-layout>
