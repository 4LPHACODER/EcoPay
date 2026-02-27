<x-app-layout>
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="font-medium text-gray-800 ml-3">Profile</h2>
                </div>
                <p class="mt-3 text-sm text-gray-600">Name: <span class="font-medium text-gray-900">{{ $user->name }}</span></p>
                <p class="mt-1 text-sm text-gray-600">Email: <span class="font-medium text-gray-900">{{ $user->email }}</span></p>
            </div>

            <!-- EcoPay Account Card -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-medium text-gray-800 ml-3">EcoPay Account</h2>
                </div>
                <div class="mt-3 text-sm text-gray-600 space-y-2">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span>Overall:</span>
                        <span class="font-semibold text-gray-900">{{ $account->overall_bottles ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span>Plastic:</span>
                        <span class="font-semibold text-purple-600">{{ $account->plastic_bottles ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span>Metal:</span>
                        <span class="font-semibold text-gray-600">{{ $account->metal_bottles ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span>Coins:</span>
                        <span class="font-semibold text-green-600">{{ $account->coins_available ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adjust EcoPay Counters -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-800 ml-3">Adjust EcoPay Counters</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Decrease Plastic -->
                <form method="POST" action="{{ route('settings.ecopay.decrementPlastic') }}" class="flex flex-col space-y-2">
                    @csrf
                    <label class="text-sm font-medium text-gray-700">Decrease Plastic</label>
                    <div class="flex space-x-2">
                        <input type="number" name="amount" min="1" max="100" value="1" 
                               class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Decrease
                        </button>
                    </div>
                </form>

                <!-- Decrease Metal -->
                <form method="POST" action="{{ route('settings.ecopay.decrementMetal') }}" class="flex flex-col space-y-2">
                    @csrf
                    <label class="text-sm font-medium text-gray-700">Decrease Metal</label>
                    <div class="flex space-x-2">
                        <input type="number" name="amount" min="1" max="100" value="1" 
                               class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                               required>
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Decrease
                        </button>
                    </div>
                </form>

                <!-- Add Coins -->
                <form method="POST" action="{{ route('settings.ecopay.addCoins') }}" class="flex flex-col space-y-2">
                    @csrf
                    <label class="text-sm font-medium text-gray-700">Add Coins</label>
                    <div class="flex space-x-2">
                        <input type="number" name="amount" min="1" max="1000" value="10" 
                               class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               required>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Add Coins
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display Preferences Form -->
        <form method="POST" action="{{ route('settings.update') }}" class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm max-w-lg">
            @csrf
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-800 ml-3">Display preferences</h3>
            </div>

            <div class="space-y-3">
                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ ($preference ?? '') === 'light' ? 'border-green-500 bg-green-50' : '' }}">
                    <input type="radio" name="display_preference" value="light" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" {{ ($preference ?? 'light') === 'light' ? 'checked' : '' }}>
                    <div class="ml-3 flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Light</span>
                    </div>
                </label>
                
                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ ($preference ?? '') === 'dark' ? 'border-green-500 bg-green-50' : '' }}">
                    <input type="radio" name="display_preference" value="dark" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" {{ ($preference ?? '') === 'dark' ? 'checked' : '' }}>
                    <div class="ml-3 flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                        <span class="text-sm text-gray-700">Dark</span>
                    </div>
                </label>
                
                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ ($preference ?? '') === 'system' ? 'border-green-500 bg-green-50' : '' }}">
                    <input type="radio" name="display_preference" value="system" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" {{ ($preference ?? '') === 'system' ? 'checked' : '' }}>
                    <div class="ml-3 flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700">System</span>
                    </div>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">Save preferences</button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="bg-white p-6 rounded-lg border border-red-100 shadow-sm max-w-lg">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-800 ml-3">Danger Zone</h3>
            </div>
            <p class="text-sm text-gray-600 mt-2">Reset EcoPay counters to zero. This action is destructive.</p>

            <form method="POST" action="{{ route('settings.reset') }}" class="mt-4" onsubmit="return confirm('Reset all EcoPay counters to zero? This cannot be undone.');">
                @csrf
                <label class="inline-flex items-center">
                    <input name="confirm" value="yes" type="checkbox" required class="h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700">I understand and want to reset counters</span>
                </label>

                <div class="mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">Reset counters</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
