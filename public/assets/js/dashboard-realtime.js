(function () {
    const dashboardRoot = document.querySelector('[data-dashboard-realtime]');

    if (! dashboardRoot || typeof window.Echo === 'undefined' || typeof window.InteriologyPostLikeSync !== 'function') {
        return;
    }

    window.Echo.private('interiology.dashboard').listen('.post.like.updated', (payload) => {
        window.InteriologyPostLikeSync(payload, {
            source: 'broadcast',
        });
    });
}());
