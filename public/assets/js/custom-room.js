(function () {
    const root = document.querySelector('[data-custom-room]');

    if (!root) {
        return;
    }

    const configElement = root.querySelector('[data-custom-room-config]');
    const roomConfig = configElement ? JSON.parse(configElement.textContent || '{}') : {};
    const builderScreen = root.querySelector('[data-builder-screen]');
    const resultScreen = root.querySelector('[data-result-screen]');
    const dropZone = root.querySelector('[data-drop-zone]');
    const roomLayer = root.querySelector('[data-room-layer]');
    const resultLayer = root.querySelector('[data-result-layer]');
    const palette = root.querySelector('[data-item-palette]');
    const finishButton = root.querySelector('[data-finish-room]');
    const resetButton = root.querySelector('[data-reset-room]');
    const postButton = root.querySelector('[data-post-room]');
    const postStatus = root.querySelector('[data-post-status]');
    const dropHint = root.querySelector('[data-drop-hint]');
    const styleButtons = root.querySelectorAll('[data-room-style]');
    const categoryButtons = root.querySelectorAll('[data-item-category]');
    const builderPreview = root.querySelector('[data-room-preview]');
    const resultPreview = root.querySelector('[data-result-preview] > img');
    const itemToolbar = root.querySelector('[data-item-toolbar]');
    const itemToolbarLabel = root.querySelector('[data-item-toolbar-label]');
    const sizeDecreaseButton = root.querySelector('[data-item-size="decrease"]');
    const sizeIncreaseButton = root.querySelector('[data-item-size="increase"]');
    const flipHorizontalButton = root.querySelector('[data-item-flip="horizontal"]');
    const flipVerticalButton = root.querySelector('[data-item-flip="vertical"]');
    const resetItemButton = root.querySelector('[data-item-reset]');
    const deleteItemButton = root.querySelector('[data-item-delete]');

    const styleOrder = Object.keys(roomConfig.styles || {});
    const itemIndex = new Map();
    const categoryOrder = ['chairs', 'tables', 'walls'];

    styleOrder.forEach((styleKey) => {
        Object.values(roomConfig.items?.[styleKey] || {}).flat().forEach((item) => {
            itemIndex.set(item.id, item);
        });
    });

    let activeStyle = roomConfig.activeStyle || root.dataset.defaultStyle || styleOrder[0] || 'scandinavian';
    let activeCategory = roomConfig.activeCategory || 'chairs';
    let placedCount = 0;
    let movingItem = null;
    let selectedItem = null;

    const SIZE_STEP = 16;
    const MIN_ITEM_WIDTH = 42;
    const MAX_ITEM_WIDTH = 420;

    function setBackground(styleKey) {
        const background = roomConfig.styles?.[styleKey]?.background;

        if (background && builderPreview) {
            builderPreview.src = background;
        }

        if (background && resultPreview) {
            resultPreview.src = background;
        }
    }

    function updateStyleButtons() {
        styleButtons.forEach((button) => {
            const isActive = button.dataset.roomStyle === activeStyle;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', String(isActive));
        });
    }

    function updateCategoryButtons() {
        categoryButtons.forEach((button) => {
            const isActive = button.dataset.itemCategory === activeCategory;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', String(isActive));
        });
    }

    function currentItems() {
        return roomConfig.items?.[activeStyle]?.[activeCategory] || [];
    }

    function getItem(id) {
        return itemIndex.get(id) || null;
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

    function setFinishState() {
        const hasItems = roomLayer.children.length > 0;
        finishButton.disabled = !hasItems;

        if (dropHint) {
            dropHint.classList.toggle('is-hidden', hasItems);
        }
    }

    function clearSelection() {
        if (selectedItem) {
            selectedItem.classList.remove('is-selected');
            selectedItem = null;
        }

        updateItemToolbar();
    }

    function selectItem(item) {
        clearSelection();
        selectedItem = item;
        item.classList.add('is-selected');
        updateItemToolbar();
    }

    function updateItemToolbar() {
        if (!itemToolbar) {
            return;
        }

        itemToolbar.hidden = !selectedItem;

        if (selectedItem && itemToolbarLabel) {
            const item = getItem(selectedItem.dataset.itemId);
            const width = Math.round(getItemWidth(selectedItem));
            itemToolbarLabel.textContent = (item?.name || 'Item terpilih') + ' - ' + width + 'px';
        }
    }

    function getItemWidth(item) {
        return Number.parseFloat(item.style.getPropertyValue('--item-width')) || Number.parseFloat(item.dataset.defaultWidth) || 120;
    }

    function setItemWidth(item, width) {
        const nextWidth = clamp(width, MIN_ITEM_WIDTH, MAX_ITEM_WIDTH);
        item.style.setProperty('--item-width', nextWidth + 'px');
        updateItemToolbar();
    }

    function updateItemTransform(item) {
        item.style.setProperty('--flip-x', item.dataset.flipX || '1');
        item.style.setProperty('--flip-y', item.dataset.flipY || '1');
    }

    function resizeSelectedItem(delta) {
        if (!selectedItem) {
            return;
        }

        setItemWidth(selectedItem, getItemWidth(selectedItem) + delta);
    }

    function flipSelectedItem(axis) {
        if (!selectedItem) {
            return;
        }

        if (axis === 'horizontal') {
            selectedItem.dataset.flipX = selectedItem.dataset.flipX === '-1' ? '1' : '-1';
        }

        if (axis === 'vertical') {
            selectedItem.dataset.flipY = selectedItem.dataset.flipY === '-1' ? '1' : '-1';
        }

        updateItemTransform(selectedItem);
    }

    function resetSelectedItem() {
        if (!selectedItem) {
            return;
        }

        setItemWidth(selectedItem, Number.parseFloat(selectedItem.dataset.defaultWidth) || getItemWidth(selectedItem));
        selectedItem.dataset.flipX = '1';
        selectedItem.dataset.flipY = '1';
        updateItemTransform(selectedItem);
    }

    function deleteSelectedItem() {
        if (!selectedItem) {
            return;
        }

        selectedItem.remove();
        selectedItem = null;
        updateItemToolbar();
        setFinishState();
    }

    function renderPalette() {
        palette.innerHTML = '';

        currentItems().forEach((item) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'custom-room-item';
            button.draggable = true;
            button.dataset.itemId = item.id;
            button.setAttribute('aria-label', 'Tarik ' + item.name + ' ke ruangan');
            button.innerHTML = '<img src="' + item.image + '" alt=""><span>' + item.name + '</span>';

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
        updateCategoryButtons();
        renderPalette();
    }

    function setActiveStyle(styleKey, resetRoom = true) {
        if (!roomConfig.styles?.[styleKey]) {
            return;
        }

        activeStyle = styleKey;
        updateStyleButtons();
        setBackground(styleKey);

        if (resetRoom) {
            roomLayer.innerHTML = '';
            resultLayer.innerHTML = '';
            builderScreen.hidden = false;
            resultScreen.hidden = true;
            placedCount = 0;
        }

        setActiveCategory(activeCategory);
        setFinishState();
    }

    function addItem(item, x, y) {
        placedCount += 1;

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'placed-room-item';
        button.dataset.type = item.type;
        button.dataset.itemId = item.id;
        button.dataset.placedId = String(placedCount);
        button.dataset.defaultWidth = String(item.width);
        button.dataset.flipX = '1';
        button.dataset.flipY = '1';
        button.setAttribute('aria-label', item.name + ' di ruangan');
        button.style.setProperty('--x', x + '%');
        button.style.setProperty('--y', y + '%');
        button.style.setProperty('--z', String(Math.round(y * 10)));
        button.style.setProperty('--item-width', item.width + 'px');
        updateItemTransform(button);
        button.innerHTML = '<img src="' + item.image + '" alt="">';

        button.addEventListener('pointerdown', startMovingItem);
        roomLayer.appendChild(button);
        setFinishState();
    }

    function startMovingItem(event) {
        const item = event.currentTarget;
        movingItem = item;
        item.setPointerCapture(event.pointerId);
        movePlacedItem(event, item);

        selectItem(item);

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
        item.style.setProperty('--x', position.x + '%');
        item.style.setProperty('--y', position.y + '%');
        item.style.setProperty('--z', String(Math.round(position.y * 10)));
    }

    function resetRoom() {
        roomLayer.innerHTML = '';
        resultLayer.innerHTML = '';
        builderScreen.hidden = false;
        resultScreen.hidden = true;
        placedCount = 0;
        clearSelection();
        setFinishState();
    }

    function showResult() {
        clearSelection();
        resultLayer.innerHTML = '';

        Array.from(roomLayer.children).forEach((item) => {
            const clone = item.cloneNode(true);
            clone.classList.remove('is-selected');
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
            const flipX = item.dataset.flipX === '-1' ? -1 : 1;
            const flipY = item.dataset.flipY === '-1' ? -1 : 1;
            const x = itemRect.left - previewRect.left;
            const y = itemRect.top - previewRect.top;

            context.save();
            context.translate(x + (flipX === -1 ? itemRect.width : 0), y + (flipY === -1 ? itemRect.height : 0));
            context.scale(flipX, flipY);

            context.drawImage(
                renderedImage,
                0,
                0,
                itemRect.width,
                itemRect.height
            );
            context.restore();
        }

        return canvas.toDataURL('image/png');
    }

    async function postRoomDesign() {
        if (!postButton) {
            return;
        }

        postButton.disabled = true;
        postButton.textContent = 'Memproses...';

        if (postStatus) {
            postStatus.textContent = '';
        }

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const image = await captureResultPreview();
            const response = await fetch(root.dataset.draftUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || '',
                },
                body: JSON.stringify({
                    image,
                    style: activeStyle,
                }),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(data.message || 'Gagal menyiapkan halaman upload.');
            }

            window.location.href = data.redirect || root.dataset.uploadUrl;
        } catch (error) {
            postButton.disabled = false;
            postButton.textContent = 'Posting Sekarang';

            if (postStatus) {
                postStatus.textContent = error.message || 'Gagal menyiapkan halaman upload.';
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

        if (!item) {
            return;
        }

        const position = getDropPosition(event);
        addItem(item, position.x, position.y);
    });

    styleButtons.forEach((button) => {
        button.addEventListener('click', () => setActiveStyle(button.dataset.roomStyle));
    });

    categoryButtons.forEach((button) => {
        button.addEventListener('click', () => setActiveCategory(button.dataset.itemCategory));
    });

    resetButton?.addEventListener('click', resetRoom);
    postButton?.addEventListener('click', postRoomDesign);
    sizeDecreaseButton?.addEventListener('click', () => resizeSelectedItem(-SIZE_STEP));
    sizeIncreaseButton?.addEventListener('click', () => resizeSelectedItem(SIZE_STEP));
    flipHorizontalButton?.addEventListener('click', () => flipSelectedItem('horizontal'));
    flipVerticalButton?.addEventListener('click', () => flipSelectedItem('vertical'));
    resetItemButton?.addEventListener('click', resetSelectedItem);
    deleteItemButton?.addEventListener('click', deleteSelectedItem);
    itemToolbar?.addEventListener('pointerdown', (event) => event.stopPropagation());

    finishButton.addEventListener('click', () => {
        if (!finishButton.disabled) {
            showResult();
        }
    });

    document.addEventListener('keydown', (event) => {
        const activeElement = document.activeElement;
        const isTypingField = ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeElement?.tagName) || activeElement?.isContentEditable;

        if (isTypingField) {
            return;
        }

        if (event.key === 'Delete' || event.key === 'Backspace') {
            deleteSelectedItem();
        }
    });

    document.addEventListener('pointerdown', (event) => {
        if (resultScreen.hidden) {
            const clickedItem = event.target.closest?.('.placed-room-item');
            const clickedToolbar = event.target.closest?.('[data-item-toolbar]');

            if (!clickedItem && !clickedToolbar) {
                clearSelection();
            }
        }
    });

    setActiveStyle(activeStyle, false);
    updateCategoryButtons();
    renderPalette();
    setFinishState();
    updateItemToolbar();
}());
