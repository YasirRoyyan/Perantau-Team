(function () {
    const root = document.querySelector('[data-custom-room]');

    if (! root) {
        return;
    }

    const builderScreen = root.querySelector('[data-builder-screen]');
    const resultScreen = root.querySelector('[data-result-screen]');
    const dropZone = root.querySelector('[data-drop-zone]');
    const roomLayer = root.querySelector('[data-room-layer]');
    const resultLayer = root.querySelector('[data-result-layer]');
    const palette = root.querySelector('[data-item-palette]');
    const finishButton = root.querySelector('[data-finish-room]');
    const resetButton = root.querySelector('[data-reset-room]');
    const editButton = root.querySelector('[data-edit-room]');
    const postButton = root.querySelector('[data-post-room]');
    const postStatus = root.querySelector('[data-post-status]');
    const captionInput = root.querySelector('[data-room-caption]');
    const dropHint = root.querySelector('[data-drop-hint]');

    const assetPath = (fileName) => new URL('../images/'+fileName, document.currentScript.src).href;

    const items = [
        { id: 'chair-sofa', type: 'chairs', name: 'Sofa Kayu', image: assetPath('custom-item-sofa.png'), width: 270, x: 46, y: 73 },
        { id: 'chair-lounge', type: 'chairs', name: 'Kursi Santai', image: assetPath('custom-item-chair.png'), width: 128, x: 63, y: 62 },
        { id: 'table-cabinet', type: 'tables', name: 'Meja Kabinet', image: assetPath('custom-item-cabinet.png'), width: 210, x: 50, y: 73 },
        { id: 'wall-frame', type: 'walls', name: 'Hiasan Dinding', image: assetPath('custom-item-frame.png'), width: 86, x: 50, y: 32 },
        { id: 'wall-lamp', type: 'walls', name: 'Lampu Berdiri', image: assetPath('custom-item-lamp.png'), width: 72, x: 70, y: 62 },
        { id: 'wall-plant', type: 'walls', name: 'Tanaman', image: assetPath('custom-item-plant.png'), width: 58, x: 74, y: 66 },
        { id: 'wall-rug', type: 'walls', name: 'Karpet', image: assetPath('custom-item-rug.png'), width: 230, x: 50, y: 84 },
        { id: 'wall-vases', type: 'walls', name: 'Vas Dekor', image: assetPath('custom-item-vases.png'), width: 74, x: 34, y: 78 },
        { id: 'wall-books', type: 'walls', name: 'Buku Dekor', image: assetPath('custom-item-books.png'), width: 86, x: 36, y: 77 },
    ];

    let activeCategory = 'chairs';
    let placedCount = 0;
    let movingItem = null;

    function getItem(id) {
        return items.find((item) => item.id === id);
    }

    function setFinishState() {
        const hasItems = roomLayer.children.length > 0;
        finishButton.disabled = ! hasItems;
        dropHint.classList.toggle('is-hidden', hasItems);
    }

    function renderPalette() {
        palette.innerHTML = '';

        items.filter((item) => item.type === activeCategory).forEach((item) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'custom-room-item';
            button.draggable = true;
            button.dataset.itemId = item.id;
            button.setAttribute('aria-label', 'Tarik '+item.name+' ke ruangan');
            button.innerHTML = '<img src="'+item.image+'" alt=""><span>'+item.name+'</span>';

            button.addEventListener('dragstart', (event) => {
                event.dataTransfer.setData('text/plain', item.id);
                event.dataTransfer.effectAllowed = 'copy';
            });

            button.addEventListener('click', () => {
                addItem(item, item.x, item.y);
            });

            palette.appendChild(button);
        });
    }

    function setActiveCategory(category) {
        activeCategory = category;

        root.querySelectorAll('[data-item-category]').forEach((button) => {
            const isActive = button.dataset.itemCategory === category;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', String(isActive));
        });

        renderPalette();
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function getDropPosition(event) {
        const rect = dropZone.getBoundingClientRect();
        const x = clamp(((event.clientX - rect.left) / rect.width) * 100, 5, 95);
        const y = clamp(((event.clientY - rect.top) / rect.height) * 100, 8, 94);

        return { x, y };
    }

    function addItem(item, x, y) {
        placedCount += 1;

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'placed-room-item';
        button.dataset.type = item.type;
        button.dataset.itemId = item.id;
        button.dataset.placedId = String(placedCount);
        button.setAttribute('aria-label', item.name+' di ruangan');
        button.style.setProperty('--x', x+'%');
        button.style.setProperty('--y', y+'%');
        button.style.setProperty('--z', String(Math.round(y * 10)));
        button.style.setProperty('--item-width', item.width+'px');
        button.innerHTML = '<img src="'+item.image+'" alt="">';

        button.addEventListener('pointerdown', startMovingItem);
        roomLayer.appendChild(button);
        setFinishState();
    }

    function startMovingItem(event) {
        const item = event.currentTarget;
        movingItem = item;
        item.setPointerCapture(event.pointerId);
        movePlacedItem(event, item);

        root.querySelectorAll('.placed-room-item').forEach(el => el.classList.remove('is-selected'));
        item.classList.add('is-selected');

        item.addEventListener('pointermove', handleMovingItem);
        item.addEventListener('pointerup', stopMovingItem, { once: true });
        item.addEventListener('pointercancel', stopMovingItem, { once: true });
    }

    function handleMovingItem(event) {
        if (movingItem) {
            movePlacedItem(event, movingItem);
        }
    }

    function stopMovingItem(event) {
        const item = event.currentTarget;
        item.removeEventListener('pointermove', handleMovingItem);
        movingItem = null;
    }

    function movePlacedItem(event, item) {
        const position = getDropPosition(event);
        item.style.setProperty('--x', position.x+'%');
        item.style.setProperty('--y', position.y+'%');
        item.style.setProperty('--z', String(Math.round(position.y * 10)));
    }

    function resetRoom() {
        roomLayer.innerHTML = '';
        resultLayer.innerHTML = '';
        builderScreen.hidden = false;
        resultScreen.hidden = true;
        setFinishState();
    }

    function showResult() {
        resultLayer.innerHTML = '';

        Array.from(roomLayer.children).forEach((item) => {
            const clone = item.cloneNode(true);
            clone.removeAttribute('aria-label');
            resultLayer.appendChild(clone);
        });

        builderScreen.hidden = true;
        resultScreen.hidden = false;
    }

    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const image = new Image();
            image.onload = () => resolve(image);
            image.onerror = reject;
            image.src = src;
        });
    }

    async function captureResultPreview() {
        const preview = root.querySelector('[data-result-preview]');
        const previewRect = preview.getBoundingClientRect();
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        canvas.width = Math.round(previewRect.width);
        canvas.height = Math.round(previewRect.height);

        const baseImage = await loadImage(preview.querySelector('img').src);
        context.drawImage(baseImage, 0, 0, canvas.width, canvas.height);

        for (const item of Array.from(resultLayer.querySelectorAll('.placed-room-item'))) {
            const itemImage = item.querySelector('img');
            const itemRect = item.getBoundingClientRect();
            const renderedImage = await loadImage(itemImage.src);

            context.drawImage(
                renderedImage,
                itemRect.left - previewRect.left,
                itemRect.top - previewRect.top,
                itemRect.width,
                itemRect.height
            );
        }

        return canvas.toDataURL('image/png');
    }

    async function postRoomDesign() {
        if (! postButton) {
            return;
        }

        postButton.disabled = true;
        postButton.textContent = 'Memposting...';

        if (postStatus) {
            postStatus.textContent = '';
        }

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const image = await captureResultPreview();
            const response = await fetch('/api/posts/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify({
                    image,
                    caption: captionInput ? captionInput.value.trim() : ''
                })
            });

            if (! response.ok) {
                const data = await response.json().catch(() => ({}));
                throw new Error(data.message || 'Desain gagal diposting.');
            }

            window.location.href = '/dashboard';
        } catch (error) {
            postButton.disabled = false;
            postButton.textContent = 'Posting Sekarang';

            if (postStatus) {
                postStatus.textContent = error.message || 'Desain gagal diposting.';
            }
        }
    }

    dropZone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropZone.classList.add('is-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('is-over');
    });

    dropZone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropZone.classList.remove('is-over');

        const item = getItem(event.dataTransfer.getData('text/plain'));

        if (! item) {
            return;
        }

        const position = getDropPosition(event);
        addItem(item, position.x, position.y);
    });

    root.querySelectorAll('[data-item-category]').forEach((button) => {
        button.addEventListener('click', () => setActiveCategory(button.dataset.itemCategory));
    });

    resetButton.addEventListener('click', resetRoom);
    editButton.addEventListener('click', resetRoom);
    postButton?.addEventListener('click', postRoomDesign);

    finishButton.addEventListener('click', () => {
        if (! finishButton.disabled) {
            showResult();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Delete' || event.key === 'Backspace') {
            // Cari barang di dalam ruangan yang sedang diseleksi
            const selectedItem = root.querySelector('.placed-room-item.is-selected');
            
            if (selectedItem) {
                selectedItem.remove(); // Hapus dari layar
                setFinishState(); // Perbarui status tombol finish (apakah masih ada barang/tidak)
                console.log('Furnitur berhasil dihapus.');
            }
        }
    });

    renderPalette();
    setFinishState();
}());
