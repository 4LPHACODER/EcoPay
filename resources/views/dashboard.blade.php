<x-app-layout>
    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Overall Bottles Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Overall Bottles</p>
                        <p id="overall-bottles" class="text-4xl font-bold text-gray-900 mt-2">{{ $account->overall_bottles ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Plastic Bottles Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Plastic Bottles</p>
                        <p id="plastic-bottles" class="text-4xl font-bold text-gray-900 mt-2">{{ $account->plastic_bottles ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0 0c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Metal Bottles Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Metal Bottles</p>
                        <p id="metal-bottles" class="text-4xl font-bold text-gray-900 mt-2">{{ $account->metal_bottles ?? 0 }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0 0c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Coins Available Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Coins Available</p>
                        <p id="coins-available" class="text-4xl font-bold text-gray-900 mt-2">{{ $account->coins_available ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Logs Section -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Activity Logs
                </h2>
            </div>

            <div id="activity-logs" class="divide-y divide-gray-200">
                @forelse($logs as $log)
                    <div class="p-6 hover:bg-gray-50 transition-colors" data-log-id="{{ $log->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="mt-1">
                                    @if($log->bottle_type === 'plastic')
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0 0c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0 0c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">
                                        @if($log->bottle_type === 'plastic')
                                            Plastic Detected
                                        @else
                                            Metal Detected
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $log->description }}</p>
                                    @if($log->coins_earned > 0)
                                        <p class="text-xs text-green-600 font-medium mt-2">+{{ $log->coins_earned }} coins earned</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center space-x-4 ml-4">
                                <span class="text-sm text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('M. d, Y') }}
                                </span>
                                <form action="{{ route('activity-log.delete', $log->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <p>No activity logs yet. Start depositing bottles!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Auth data for JavaScript -->
    <script>
        window.AUTH_EMAIL = @json(auth()->user()->email);
        window.ECOPAY_ACCOUNT_ID = @json($account->id ?? null);
    </script>

    @vite(['resources/js/dashboard-realtime.js'])
</x-app-layout>
