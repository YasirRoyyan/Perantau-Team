(function () {
    const dashboardRoot = document.querySelector('[data-dashboard-realtime]');

    if (! dashboardRoot) {
        return;
    }

    if (typeof window.Pusher === 'undefined' || typeof window.InteriologyPostLikeSync !== 'function') {
        console.warn('[Interiology realtime] Listener tidak aktif.', {
            hasPusher: typeof window.Pusher !== 'undefined',
            hasSync: typeof window.InteriologyPostLikeSync === 'function',
        });
        return;
    }

    const config = window.InteriologyReverb || {};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const client = new window.Pusher(config.key, {
        cluster: config.cluster || 'mt1',
        wsHost: config.host,
        wsPort: Number(config.port || 8080),
        wssPort: Number(config.port || 8080),
        forceTLS: Boolean(config.forceTLS),
        enabledTransports: ['ws', 'wss'],
        disableStats: true,
        authEndpoint: config.authEndpoint || '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
        },
    });

    window.InteriologySocketId = function () {
        return client.connection.socket_id || '';
    };

    client.connection.bind('connected', function () {
        console.info('[Interiology realtime] WebSocket connected.', client.connection.socket_id);
    });

    client.connection.bind('error', function (error) {
        console.error('[Interiology realtime] WebSocket error.', error);
    });

    const channel = client.subscribe('interiology.dashboard');

    channel.bind('pusher:subscription_succeeded', function () {
        console.info('[Interiology realtime] Channel subscribed.');
    });

    channel.bind('pusher:subscription_error', function (status) {
        console.error('[Interiology realtime] Channel subscription error.', status);
    });

    function handleLikeUpdated(payload) {
        window.InteriologyPostLikeSync(payload, {
            source: 'broadcast',
        });
    }

    channel.bind('post.like.updated', handleLikeUpdated);
}());
