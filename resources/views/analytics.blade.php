<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Analytics</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm text-gray-600">Overall Bottles</p>
                <p class="text-3xl font-bold mt-2">{{ $account->overall_bottles ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm text-gray-600">Plastic Bottles</p>
                <p class="text-3xl font-bold mt-2">{{ $account->plastic_bottles ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm text-gray-600">Metal Bottles</p>
                <p class="text-3xl font-bold mt-2">{{ $account->metal_bottles ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm lg:col-span-2">
                <h2 class="font-medium text-gray-800 mb-4">Coins over time</h2>
                <canvas id="coinsLine" width="400" height="160"></canvas>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="font-medium text-gray-800 mb-3">Plastic vs Metal</h3>
                    <canvas id="barChart" width="400" height="220"></canvas>
                </div>

                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="font-medium text-gray-800 mb-3">Bottle distribution</h3>
                    <canvas id="doughnutChart" width="220" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Inline JSON data for analytics.js — use @json to ensure valid PHP -> JS output --}}
    <script>
        window.ecoAnalytics = {
            barData: @json($barData),
            doughnutData: @json($doughnutData),
            lineData: @json($lineData)
        };
    </script>

    {{-- Load Chart rendering module only on this page --}}
    @vite(['resources/js/analytics.js'])
</x-app-layout>