<div class="post-detail-modal" data-post-modal hidden data-current-user-id="{{ auth()->id() }}">
    <div class="post-detail-modal__backdrop" data-post-modal-close></div>
    <section class="post-detail-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="post-detail-title">
        <button type="button" class="post-detail-modal__close" data-post-modal-close aria-label="Tutup">×</button>

        <div class="post-detail-modal__media">
            <img data-post-modal-image src="" alt="">
        </div>

        <div class="post-detail-modal__content">
            <div class="post-detail-modal__author">
                <img data-post-modal-avatar src="" alt="">
                <div class="post-detail-modal__author-copy">
                    <h3 id="post-detail-title" data-post-modal-user></h3>
                    <p data-post-modal-bio></p>
                </div>
            </div>

            <p class="post-detail-modal__caption" data-post-modal-caption></p>

            <div class="post-detail-modal__meta">
                <span data-post-modal-likes></span>
                <span data-post-modal-status></span>
            </div>

            <div class="post-detail-modal__icon-actions">
                <button type="button" class="post-detail-modal__icon-btn" data-post-modal-like title="Suka" aria-label="Suka">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"></path>
                    </svg>
                </button>
                <button type="button" class="post-detail-modal__icon-btn" data-post-modal-favorite title="Favorit" aria-label="Favorit">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16Z"></path>
                    </svg>
                </button>
                <button type="button" class="post-detail-modal__icon-btn post-detail-modal__icon-btn--danger" data-post-modal-delete title="Hapus" aria-label="Hapus">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <path d="M19 6 18 20a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg>
                </button>
            </div>

            <div class="post-detail-modal__actions">
                <a href="#" data-post-modal-profile class="post-detail-modal__link">Lihat profil</a>
            </div>
        </div>
    </section>

    <section class="post-delete-confirm" data-post-delete-confirm hidden role="dialog" aria-modal="true" aria-labelledby="post-delete-title">
        <div class="post-delete-confirm__panel">
            <h3 id="post-delete-title">Hapus postingan?</h3>
            <p>Postingan akan hilang dari profil dan Interiorgram. File gambarnya juga ikut dihapus.</p>
            <div class="post-delete-confirm__actions">
                <button type="button" data-post-delete-cancel>Batal</button>
                <button type="button" data-post-delete-confirm-button>Hapus</button>
            </div>
        </div>
    </section>
</div>
