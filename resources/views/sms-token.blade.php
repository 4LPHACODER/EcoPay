<x-app-layout>
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">SMS Gateway Token</h1>
            <a href="{{ route('settings') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Back to Settings
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('sms_token'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg">
                <p class="font-semibold text-sm">Save this token now — it won't be shown in full again.</p>
            </div>
        @endif

        <!-- Token card -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm space-y-6">

            <div>
                <h2 class="text-base font-semibold text-gray-900">SMS gateway integration</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Use the token and endpoints below to configure the mobile app that sends SMS messages.
                </p>
            </div>

            <!-- Current token -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-gray-700">Current token</label>
                    <form method="POST" action="{{ route('settings.sms-token.rotate') }}">
                        @csrf
                        <button type="submit"
                                class="rounded border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Generate / Rotate token
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" readonly
                           id="field-token"
                           value="{{ session('sms_token') ?: ($token ? str_repeat('•', 40) . substr($token->token, -8) : 'No token generated yet') }}"
                           class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm font-mono bg-gray-50 text-gray-800">
                    <button type="button" onclick="copyField('field-token', '{{ session('sms_token') ?: $token?->token }}')"
                            class="rounded border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Copy
                    </button>
                </div>
            </div>

            <!-- GET endpoint -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">GET endpoint — pending SMS messages</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly
                           id="field-get"
                           value="{{ $getEndpoint }}"
                           class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm font-mono bg-gray-50 text-gray-800">
                    <button type="button" onclick="copyField('field-get')"
                            class="rounded border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Copy
                    </button>
                </div>
            </div>

            <!-- PUT endpoint -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">PUT endpoint example — mark as sent</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly
                           id="field-put"
                           value="{{ $putEndpoint }}"
                           class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm font-mono bg-gray-50 text-gray-800">
                    <button type="button" onclick="copyField('field-put')"
                            class="rounded border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Copy
                    </button>
                </div>
                <p class="text-xs text-gray-500">Replace <code class="bg-gray-100 px-1 rounded">123</code> with the actual <code class="bg-gray-100 px-1 rounded">sms_messages.id</code>.</p>
            </div>

            <!-- Required header -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Required header for all requests</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly
                           id="field-header"
                           value="X-Api-Token: {{ session('sms_token') ?: ($token?->token ?? '<your-token>') }}"
                           class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm font-mono bg-gray-50 text-gray-800">
                    <button type="button" onclick="copyField('field-header')"
                            class="rounded border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Copy
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyField(id, override) {
            const input = document.getElementById(id);
            const value = override || input.value;
            if (!value || value === 'No token generated yet') return;
            navigator.clipboard.writeText(value).then(() => {
                const btn = input.nextElementSibling;
                const original = btn.textContent;
                btn.textContent = 'Copied!';
                btn.classList.add('text-green-600', 'border-green-300');
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.remove('text-green-600', 'border-green-300');
                }, 2000);
            });
        }
    </script>
</x-app-layout>
