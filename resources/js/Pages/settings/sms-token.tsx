import { router, Head, usePage } from '@inertiajs/react';
import { useState } from 'react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import type { BreadcrumbItem } from '@/types';

interface SmsTokenData {
    id: number;
    name: string | null;
    token: string;
    active: boolean;
    last_used_at: string | null;
    created_at: string;
}

interface Props {
    token: SmsTokenData | null;
    getEndpoint: string;
    putEndpoint: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'SMS Token', href: '/settings/sms-token' },
];

function CopyField({ label, value }: { label: string; value: string }) {
    const [copied, setCopied] = useState(false);

    const copy = () => {
        if (!value) return;
        navigator.clipboard.writeText(value).then(() => {
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        });
    };

    return (
        <div className="space-y-1.5">
            <Label>{label}</Label>
            <div className="flex items-center gap-2">
                <Input readOnly value={value} className="flex-1 font-mono text-sm" />
                <Button type="button" variant="outline" size="sm" onClick={copy} className="shrink-0">
                    {copied ? 'Copied!' : 'Copy'}
                </Button>
            </div>
        </div>
    );
}

export default function SmsToken({ token, getEndpoint, putEndpoint }: Props) {
    const { props } = usePage<{ flash?: { sms_token?: string; success?: string } }>();
    const newToken = props.flash?.sms_token ?? null;
    const success = props.flash?.success ?? null;

    const visibleToken = newToken ?? token?.token ?? '';
    const maskedToken = token ? `${'•'.repeat(40)}${token.token.slice(-8)}` : 'No token generated yet';
    const displayToken = newToken ? newToken : (token ? maskedToken : 'No token generated yet');

    const samplePut = putEndpoint.replace('{id}', '123') + '  — replace 123 with actual sms_messages.id';

    const rotate = () => {
        router.post('/settings/sms-token/rotate');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="SMS Token" />

            <h1 className="sr-only">SMS Token Settings</h1>

            <SettingsLayout>
                <div className="space-y-6">
                    <Heading
                        variant="small"
                        title="SMS gateway integration"
                        description="Use the token and endpoints below to configure the mobile app that sends SMS messages."
                    />

                    {success && (
                        <div className="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {success}
                        </div>
                    )}

                    {newToken && (
                        <div className="rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            <strong>Save this token now.</strong> It won't be shown in full again.
                        </div>
                    )}

                    <div className="space-y-1.5">
                        <div className="flex items-center justify-between">
                            <Label>Current token</Label>
                            <Button type="button" variant="outline" size="sm" onClick={rotate}>
                                Generate / Rotate token
                            </Button>
                        </div>
                        <div className="flex items-center gap-2">
                            <Input
                                readOnly
                                value={displayToken}
                                className="flex-1 font-mono text-sm"
                            />
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                className="shrink-0"
                                onClick={() => {
                                    if (visibleToken) {
                                        navigator.clipboard.writeText(visibleToken);
                                    }
                                }}
                                disabled={!visibleToken}
                            >
                                Copy
                            </Button>
                        </div>
                    </div>

                    <CopyField label="GET endpoint — pending SMS messages" value={getEndpoint} />
                    <CopyField label="PUT endpoint example — mark as sent" value={samplePut} />
                    <CopyField
                        label="Required header for all requests"
                        value={visibleToken ? `X-Api-Token: ${visibleToken}` : 'X-Api-Token: <your-token>'}
                    />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
