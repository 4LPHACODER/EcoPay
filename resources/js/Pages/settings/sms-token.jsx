import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function SmsTokenSettings({ auth, token, getEndpoint, putEndpoint, flash }) {
    const visibleToken = flash?.sms_token || token?.token || '';

    const copyToClipboard = (value) => {
        if (!navigator.clipboard || !value) {
            return;
        }

        navigator.clipboard.writeText(value).catch(() => {});
    };

    const samplePut = `${putEndpoint.replace('{id}', '123')}  (replace 123 with actual sms_messages.id)`;

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    SMS Token Settings
                </h2>
            }
        >
            <Head title="SMS Token Settings" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="space-y-6 p-6 text-gray-900">
                            <div>
                                <h3 className="text-lg font-medium">
                                    SMS gateway integration
                                </h3>
                                <p className="mt-1 text-sm text-gray-500">
                                    Use the details below to configure the mobile app that sends SMS.
                                </p>
                            </div>

                            <div className="space-y-2">
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-medium">
                                        Current token
                                    </span>
                                    <form
                                        method="post"
                                        action="/settings/sms-token/rotate"
                                    >
                                        <input
                                            type="hidden"
                                            name="_token"
                                            value={document
                                                .querySelector('meta[name=csrf-token]')
                                                ?.getAttribute('content') || ''}
                                        />
                                        <button
                                            type="submit"
                                            className="rounded border px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                        >
                                            Generate / Rotate token
                                        </button>
                                    </form>
                                </div>

                                <div className="flex items-center gap-2">
                                    <input
                                        readOnly
                                        className="flex-1 rounded border px-3 py-2 text-sm"
                                        value={
                                            visibleToken
                                                ? visibleToken
                                                : token
                                                ? `********${token.token.slice(-8)}`
                                                : 'No token generated yet'
                                        }
                                    />
                                    <button
                                        type="button"
                                        onClick={() =>
                                            copyToClipboard(visibleToken || token?.token || '')
                                        }
                                        className="rounded border px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Copy
                                    </button>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <span className="text-sm font-medium">
                                    GET endpoint
                                </span>
                                <div className="flex items-center gap-2">
                                    <input
                                        readOnly
                                        className="flex-1 rounded border px-3 py-2 text-sm"
                                        value={getEndpoint}
                                    />
                                    <button
                                        type="button"
                                        onClick={() => copyToClipboard(getEndpoint)}
                                        className="rounded border px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Copy
                                    </button>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <span className="text-sm font-medium">
                                    PUT endpoint example
                                </span>
                                <div className="flex items-center gap-2">
                                    <input
                                        readOnly
                                        className="flex-1 rounded border px-3 py-2 text-sm"
                                        value={samplePut}
                                    />
                                    <button
                                        type="button"
                                        onClick={() => copyToClipboard(samplePut)}
                                        className="rounded border px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Copy
                                    </button>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <span className="text-sm font-medium">
                                    Required header for all requests
                                </span>
                                <div className="flex items-center gap-2">
                                    <input
                                        readOnly
                                        className="flex-1 rounded border px-3 py-2 text-sm"
                                        value="X-Api-Token: &lt;your-generated-token-here&gt;"
                                    />
                                    <button
                                        type="button"
                                        onClick={() =>
                                            copyToClipboard(
                                                `X-Api-Token: ${visibleToken || token?.token || ''}`,
                                            )
                                        }
                                        className="rounded border px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

