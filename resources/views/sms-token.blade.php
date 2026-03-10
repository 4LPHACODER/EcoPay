<x-app-layout>
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">SMS Gateway Token</h1>
            <a href="{{ route('settings') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Back to Settings
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('test_success'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
                {{ session('test_success') }}
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
                <label class="text-sm font-medium text-gray-700">PUT endpoint — mark as sent</label>
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
                <p class="text-xs text-gray-500">
                    The Flutter app automatically replaces <code class="bg-gray-100 px-1 rounded">{id}</code> with each message's <code class="bg-gray-100 px-1 rounded">id</code> from the GET response.
                    Paste this URL as-is into the <strong>putUrl</strong> field in the app.
                </p>
            </div>

            <!-- Required header -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Authorization header (paste as token in the app)</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly
                           id="field-header"
                           value="{{ session('sms_token') ?: ($token?->token ?? '<your-token>') }}"
                           class="flex-1 rounded border border-gray-300 px-3 py-2 text-sm font-mono bg-gray-50 text-gray-800">
                    <button type="button" onclick="copyField('field-header')"
                            class="rounded border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Copy
                    </button>
                </div>
                <p class="text-xs text-gray-500">
                    The app sends this as <code class="bg-gray-100 px-1 rounded">Authorization: Bearer &lt;token&gt;</code>.
                    Paste only the token value into the app's <strong>token</strong> field.
                </p>
            </div>

            <!-- Response shape reference -->
            <div class="space-y-2 pt-2 border-t border-gray-100">
                <label class="text-sm font-medium text-gray-700">GET response shape</label>
                <pre class="bg-gray-50 border border-gray-200 rounded px-3 py-2 text-xs font-mono text-gray-700 overflow-x-auto">{
  "data": [
    {
      "id": 1,
      "phone_number": "+639XXXXXXXXX",
      "message": "Your OTP is 123456",
      "status": "pending",
      "created_at": "2026-03-10T12:00:00.000000Z"
    }
  ]
}</pre>
                <p class="text-xs text-gray-500">
                    Set <strong>messageField</strong> in the app to <code class="bg-gray-100 px-1 rounded">message</code>.
                </p>
            </div>

        </div>

        <!-- Send Test SMS -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm space-y-5">

            <div>
                <h2 class="text-base font-semibold text-gray-900">Send a test SMS</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Queue a message in the database. The mobile gateway will pick it up on the next poll of the GET endpoint.
                </p>
            </div>

            <form method="POST" action="{{ route('settings.sms-token.send-test') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">
                        Recipient phone number
                    </label>
                    <input
                        type="text"
                        id="recipient"
                        name="recipient"
                        value="{{ old('recipient') }}"
                        placeholder="+639XXXXXXXXX"
                        maxlength="20"
                        class="w-full rounded border @error('recipient') border-red-400 bg-red-50 @else border-gray-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('recipient')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            Message
                        </label>
                        <span id="char-count" class="text-xs text-gray-400">0 / 160</span>
                    </div>
                    <textarea
                        id="message"
                        name="message"
                        rows="4"
                        maxlength="160"
                        oninput="document.getElementById('char-count').textContent = this.value.length + ' / 160'"
                        placeholder="Type your SMS message here…"
                        class="w-full rounded border @error('message') border-red-400 bg-red-50 @else border-gray-300 @enderror px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                            @if(!$token) disabled @endif
                            class="inline-flex items-center gap-2 rounded bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed px-4 py-2 text-sm font-medium text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Queue SMS
                    </button>
                    @if(!$token)
                        <span class="text-xs text-gray-500">Generate a token first to enable sending.</span>
                    @endif
                </div>
            </form>

        </div>

        <!-- Recent messages log -->
        @if($recentMessages->isNotEmpty())
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm space-y-4">

            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Recent messages</h2>
                <span class="text-xs text-gray-400">Last {{ $recentMessages->count() }} entries</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-2 text-left text-xs font-medium text-gray-500 pr-4">Recipient</th>
                            <th class="pb-2 text-left text-xs font-medium text-gray-500 pr-4">Message</th>
                            <th class="pb-2 text-left text-xs font-medium text-gray-500 pr-4">Status</th>
                            <th class="pb-2 text-left text-xs font-medium text-gray-500">Queued</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentMessages as $sms)
                        <tr>
                            <td class="py-2 pr-4 font-mono text-gray-800">{{ $sms->recipient }}</td>
                            <td class="py-2 pr-4 text-gray-600 max-w-xs truncate">{{ $sms->message }}</td>
                            <td class="py-2 pr-4">
                                @if($sms->status === 'sent')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Sent
                                    </span>
                                @elseif($sms->status === 'failed')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 text-gray-400 text-xs whitespace-nowrap">{{ $sms->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @endif

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
