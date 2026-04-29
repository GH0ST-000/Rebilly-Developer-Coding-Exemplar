/**
 * Refactor the following old TypeScript code. Don't go too deep, estimate up to 15 minutes of work.
 *
 * Do not implement any libraries or frameworks.
 *
 * The code shouldn't be ideal, rather adequate for the first step of the refactoring. Feel free to leave comments in places which can be improved in the future if you see a possibility of that.
 */

const STORAGE_KEY = 'items';
const DEFAULT_TEXT = '(untitled)';
const root = document.getElementById('app');

if (!root) {
    throw new Error('Required #app element not found.');
}

let state = { items: loadItems() };

function isValidItem(item) {
    return typeof item === 'object' && item !== null && typeof item.text === 'string' && item.text.trim().length > 0;
}

function loadItems() {
    try {
        const storedData = localStorage.getItem(STORAGE_KEY);
        if (!storedData) return [];

        const itemsArray = JSON.parse(storedData);
        if (!Array.isArray(itemsArray)) return [];

        return itemsArray
            .map((item) => ({
                id: typeof item.id === 'string' ? item.id : String(item.id ?? crypto.randomUUID()),
                text: String(item.text ?? item.title ?? '').trim(),
            }))
            .filter(isValidItem);
    } catch {
        return [];
    }
}

function setState(patch) {
    const updatedState = typeof patch === 'function' ? patch(state) : patch;
    state = { ...state, ...updatedState };

    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state.items));
    } catch {}
    render();
}

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function render() {
    root.innerHTML = '';

    const main = document.createElement('main');
    const listItemsHtml = state.items
        .map(
            (item) => `
            <li data-id="${escapeHtml(item.id)}">
                ${escapeHtml(item.text) || DEFAULT_TEXT}
                <button type="button" data-remove aria-label="Remove">✘</button>
            </li>`
        )
        .join('');

    main.innerHTML = `
        <form>
            <input name="todo" placeholder="Todo…" autocomplete="off" />
            <button type="submit">Add</button>
        </form>
        <ul>${listItemsHtml}</ul>
    `;

    const form = main.querySelector('form');
    const input = main.querySelector('input[name="todo"]');

    if (form && input) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            setState((currentState) => ({
                items: [...currentState.items, { id: crypto.randomUUID(), text }],
            }));
        });
    }

    main.addEventListener('click', (e) => {
        const target = e.target;
        if (!(target instanceof Element) || !target.matches('[data-remove]')) return;

        const id = target.closest('li')?.getAttribute('data-id');
        if (!id) return;

        setState((currentState) => ({
            items: currentState.items.filter((item) => item.id !== id),
        }));
    });

    root.appendChild(main);
}

render();