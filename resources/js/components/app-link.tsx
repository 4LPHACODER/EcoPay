import { Link } from '@inertiajs/react';
import * as React from 'react';

/**
 * AppLink - A wrapper around Inertia's Link component that properly forwards refs.
 * This is needed when using Radix UI components with asChild prop.
 */
const AppLink = React.forwardRef<
    HTMLAnchorElement,
    React.ComponentProps<typeof Link> & { prefetch?: boolean }
>(function AppLink({ prefetch = false, ...props }, ref) {
    return <Link {...props} ref={ref} prefetch={prefetch} />;
});

export default AppLink;
