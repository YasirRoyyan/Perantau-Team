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

    finishButton.addEventListener('click', () => {
        if (! finishButton.disabled) {
            showResult();
        }
    });

    renderPalette();
    setFinishState();
}());
