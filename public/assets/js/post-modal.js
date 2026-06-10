(function () {
    const modal = document.querySelector('[data-post-modal]');

    if (! modal) {
        return;
    }

    const currentUserId = Number(modal.dataset.currentUserId || 0);
    const imageEl = modal.querySelector('[data-post-modal-image]');
    const userEl = modal.querySelector('[data-post-modal-user]');
    const bioEl = modal.querySelector('[data-post-modal-bio]');
    const captionEl = modal.querySelector('[data-post-modal-caption]');
    const likesEl = modal.querySelector('[data-post-modal-likes]');
    const statusEl = modal.querySelector('[data-post-modal-status]');
    const profileLinkEl = modal.querySelector('[data-post-modal-profile]');
    const avatarEl = modal.querySelector('[data-post-modal-avatar]');
    const likeButton = modal.querySelector('[data-post-modal-like]');
    const favoriteButton = modal.querySelector('[data-post-modal-favorite]');
    const deleteButton = modal.querySelector('[data-post-modal-delete]');
    const deleteConfirm = modal.querySelector('[data-post-delete-confirm]');
    const deleteCancelButton = modal.querySelector('[data-post-delete-cancel]');
    const deleteConfirmButton = modal.querySelector('[data-post-delete-confirm-button]');
    const closeTargets = modal.querySelectorAll('[data-post-modal-close]');

    let activeCard = null;
    let closeTimer = null;

    function pulseButton(button) {
        if (! button) {
            return;
        }

        button.classList.remove('is-pulsing');
        void button.offsetWidth;
        button.classList.add('is-pulsing');

        window.setTimeout(() => {
            button.classList.remove('is-pulsing');
        }, 240);
    }

    function openModal(card) {
        if (closeTimer) {
            window.clearTimeout(closeTimer);
            closeTimer = null;
        }

        activeCard = card;
        modal.dataset.activePostId = card.dataset.postId || '';
        const ownerId = Number(card.dataset.postOwnerId || 0);
        const liked = card.dataset.postLiked === '1';
        const favorited = card.dataset.postFavorited === '1';
        const likesCount = Number(card.dataset.postLikes || 0);

        imageEl.src = card.dataset.postImage || '';
        imageEl.alt = card.dataset.postImageAlt || 'Postingan interior';
        userEl.textContent = card.dataset.postOwnerName || card.dataset.postUserName || 'Interiology User';
        bioEl.textContent = card.dataset.postOwnerBio || 'Belum ada bio';
        captionEl.textContent = card.dataset.postCaption || 'Belum ada catatan untuk postingan ini.';
        likesEl.textContent = likesCount + ' suka';
        statusEl.textContent = '';
        avatarEl.src = card.dataset.postUserAvatar || '';
        profileLinkEl.href = card.dataset.postProfileUrl || '#';

        likeButton.classList.toggle('is-active', liked);
        favoriteButton.classList.toggle('is-active', favorited);
        deleteButton.hidden = card.dataset.postCanDelete !== '1';
        deleteButton.style.display = card.dataset.postCanDelete === '1' ? 'inline-flex' : 'none';
        deleteButton.disabled = card.dataset.postCanDelete !== '1';
        deleteConfirm.hidden = true;
        deleteConfirmButton.disabled = false;

        modal.hidden = false;
        window.requestAnimationFrame(() => {
            modal.classList.add('is-open');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
        deleteConfirm.hidden = true;
        activeCard = null;
        modal.dataset.activePostId = '';

        closeTimer = window.setTimeout(() => {
            modal.hidden = true;
            closeTimer = null;
        }, 220);
    }

    function updateDashboardTotals(ownerId, liked) {
        if (Number(ownerId || 0) !== currentUserId) {
            return;
        }

        const delta = liked ? 1 : -1;

        document.querySelectorAll('[data-profile-total-likes], [data-dashboard-total-likes]').forEach((totalLikesEl) => {
            const currentTotal = Number.parseInt(totalLikesEl.textContent.replace(/[^\d-]/g, ''), 10) || 0;
            totalLikesEl.textContent = String(Math.max(0, currentTotal + delta));
        });
    }

    function syncPostLikeState(payload, options = {}) {
        const postId = Number(payload?.post_id || payload?.postId || 0);

        if (! postId) {
            return;
        }

        const likesCount = Number(payload?.likes_count ?? payload?.likesCount ?? 0);
        const liked = Boolean(payload?.liked);
        const actorId = Number(payload?.actor_id ?? payload?.actorId ?? 0);
        const ownerId = Number(payload?.post_owner_id ?? payload?.postOwnerId ?? 0);
        const source = options.source || 'broadcast';
        const shouldSyncLikeState = source === 'local' || actorId === currentUserId;
        const matchingCards = Array.from(document.querySelectorAll(`[data-post-card][data-post-id="${postId}"]`));
        const alreadySynced = matchingCards.length > 0 && matchingCards.every((card) => {
            const currentLikes = Number(card.dataset.postLikes || 0);
            const likesMatch = currentLikes === likesCount;
            const likedMatch = ! shouldSyncLikeState || (card.dataset.postLiked === (liked ? '1' : '0'));

            return likesMatch && likedMatch;
        });

        if (source === 'broadcast' && alreadySynced) {
            return;
        }

        matchingCards.forEach((card) => {
            card.dataset.postLikes = String(likesCount);

            if (shouldSyncLikeState) {
                card.dataset.postLiked = liked ? '1' : '0';
            }

            const likeCountEl = card.querySelector('[data-post-like-count]');

            if (likeCountEl) {
                likeCountEl.textContent = likesCount + ' suka';
            }
        });

        if (Number.parseInt(modal.dataset.activePostId || '0', 10) === postId) {
            likesEl.textContent = likesCount + ' suka';

            if (shouldSyncLikeState) {
                likeButton.classList.toggle('is-active', liked);
            }
        }

        updateDashboardTotals(ownerId, liked);
    }

    window.InteriologyPostLikeSync = syncPostLikeState;

    async function toggleLike() {
        if (! activeCard || likeButton.disabled) {
            return;
        }

        const likeUrl = activeCard.dataset.postLikeUrl;

        likeButton.disabled = true;
        statusEl.textContent = 'Menyimpan...';

        try {
            const response = await fetch(likeUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Socket-ID': window.Echo?.socketId?.() || window.InteriologySocketId?.() || '',
                },
            });

            const data = await response.json().catch(() => ({}));

            if (! response.ok) {
                throw new Error(data.message || 'Gagal memperbarui suka.');
            }

            const liked = Boolean(data.liked);
            const likesCount = Number(data.likes_count || 0);
            const ownerId = Number(activeCard.dataset.postOwnerId || 0);

            syncPostLikeState({
                post_id: Number(activeCard.dataset.postId || 0),
                post_owner_id: ownerId,
                likes_count: likesCount,
                liked,
                actor_id: currentUserId,
            }, {
                source: 'local',
            });

            pulseButton(likeButton);

            statusEl.textContent = liked ? 'Disukai' : 'Suka dibatalkan';
        } catch (error) {
            statusEl.textContent = error.message || 'Gagal memperbarui suka.';
        } finally {
            likeButton.disabled = false;
        }
    }

    async function toggleFavorite() {
        if (! activeCard) {
            return;
        }

        const favoriteUrl = activeCard.dataset.postFavoriteUrl;
        favoriteButton.disabled = true;
        statusEl.textContent = 'Menyimpan...';

        try {
            const response = await fetch(favoriteUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });

            const data = await response.json().catch(() => ({}));

            if (! response.ok) {
                throw new Error(data.message || 'Gagal memperbarui favorit.');
            }

            const favorited = Boolean(data.favorited);
            activeCard.dataset.postFavorited = favorited ? '1' : '0';
            favoriteButton.classList.toggle('is-active', favorited);
            pulseButton(favoriteButton);
            statusEl.textContent = favorited ? 'Disimpan ke favorit' : 'Dihapus dari favorit';
        } catch (error) {
            statusEl.textContent = error.message || 'Gagal memperbarui favorit.';
        } finally {
            favoriteButton.disabled = false;
        }
    }

    function requestDeletePost() {
        if (! activeCard) {
            return;
        }

        const ownerId = Number(activeCard.dataset.postOwnerId || 0);

        if (ownerId !== currentUserId) {
            return;
        }

        deleteConfirm.hidden = false;
    }

    async function deletePost() {
        if (! activeCard) {
            return;
        }

        const deleteUrl = activeCard.dataset.postDeleteUrl;
        const ownerId = Number(activeCard.dataset.postOwnerId || 0);

        if (ownerId !== currentUserId) {
            deleteConfirm.hidden = true;
            return;
        }

        deleteButton.disabled = true;
        deleteConfirmButton.disabled = true;
        statusEl.textContent = 'Menghapus...';

        try {
            const response = await fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });

            const data = await response.json().catch(() => ({}));

            if (! response.ok) {
                throw new Error(data.message || 'Gagal menghapus postingan.');
            }

            closeModal();
            window.location.reload();
        } catch (error) {
            deleteButton.disabled = false;
            deleteConfirmButton.disabled = false;
            statusEl.textContent = error.message || 'Gagal menghapus postingan.';
            deleteConfirm.hidden = true;
        }
    }

    document.querySelectorAll('[data-post-card]').forEach((card) => {
        card.addEventListener('click', () => openModal(card));
        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openModal(card);
            }
        });
    });

    likeButton.addEventListener('click', toggleLike);
    favoriteButton.addEventListener('click', toggleFavorite);
    deleteButton.addEventListener('click', requestDeletePost);
    deleteCancelButton.addEventListener('click', () => {
        deleteConfirm.hidden = true;
    });
    deleteConfirmButton.addEventListener('click', deletePost);
    closeTargets.forEach((target) => target.addEventListener('click', closeModal));

    document.addEventListener('keydown', (event) => {
        if (! modal.hidden && event.key === 'Escape') {
            closeModal();
        }
    });
}());
