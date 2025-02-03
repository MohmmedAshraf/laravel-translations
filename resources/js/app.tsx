import './bootstrap';
import '../css/app.css';

import { ConfigProvider } from 'antd';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// @ts-ignore
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const modalStyles = {
    mask: {
        backdropFilter: 'blur(10px)',
    },
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({el, App, props})
    {
        const root = createRoot(el);

        root.render(
            <ConfigProvider
                wave={{ disabled: true }}
                modal={{ styles: modalStyles }}
            >
                <App {...props} />
            </ConfigProvider>
        );
    },
    progress: {
        color: '#ffffff',
    },
}).then(r  => {
    // @ts-ignore
});
