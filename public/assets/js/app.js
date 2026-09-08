document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Toggle Sidebar
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    // Close on overlay click
    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Close on route change (link click) for mobile
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    });

    initSearchableSelects();
});

/**
 * Turns any <select class="searchable-select"> into a type-to-search dropdown.
 * The original <select> stays in the DOM (visually hidden) so form submission
 * and any existing name/required attributes keep working unchanged.
 */
function initSearchableSelects() {
    const formsWithValidation = new WeakSet();

    function attachFormValidation(form) {
        if (!form || formsWithValidation.has(form)) return;
        formsWithValidation.add(form);
        form.addEventListener('submit', function (e) {
            let firstInvalid = null;
            form.querySelectorAll('select.ss-native-select[data-required="true"]').forEach(function (sel) {
                const input = sel.parentNode.querySelector('.search-select-input');
                if (!sel.value) {
                    if (input) input.classList.add('is-invalid');
                    if (!firstInvalid) firstInvalid = input || sel;
                } else if (input) {
                    input.classList.remove('is-invalid');
                }
            });
            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
            }
        });
    }

    document.querySelectorAll('select.searchable-select:not([data-ss-init])').forEach(function (select) {
        select.setAttribute('data-ss-init', '1');

        const options = Array.from(select.options).map(function (opt) {
            return { value: opt.value, label: opt.textContent.trim() };
        });
        const placeholderOpt = options.find(o => o.value === '');
        const placeholder = placeholderOpt ? placeholderOpt.label : 'Search...';
        const realOptions = options.filter(o => o.value !== '');

        const wrap = document.createElement('div');
        wrap.className = 'search-select-wrap';

        const input = document.createElement('input');
        input.type = 'text';
        input.className = (select.className + ' search-select-input').replace('searchable-select', '').trim();
        input.placeholder = placeholder;
        input.autocomplete = 'off';

        const menu = document.createElement('div');
        menu.className = 'search-select-menu';

        // Native constraint validation can't show a bubble on a display:none control
        // (and logs a console error), so enforce "required" ourselves instead.
        if (select.required) {
            select.required = false;
            select.dataset.required = 'true';
        }

        select.classList.add('ss-native-select');
        select.parentNode.insertBefore(wrap, select);
        wrap.appendChild(input);
        wrap.appendChild(menu);
        wrap.appendChild(select);

        // Preselect label if the select already has a value (e.g. server re-render)
        const selectedOpt = realOptions.find(o => o.value === select.value);
        if (selectedOpt) input.value = selectedOpt.label;

        let activeIndex = -1;
        let filtered = realOptions.slice();

        function filterOptions(term) {
            term = term.trim().toLowerCase();
            if (!term) return realOptions.slice();
            return realOptions.filter(o => o.label.toLowerCase().includes(term));
        }

        function renderMenu() {
            menu.innerHTML = '';
            if (!filtered.length) {
                const empty = document.createElement('div');
                empty.className = 'search-select-empty';
                empty.textContent = 'No matches found';
                menu.appendChild(empty);
                return;
            }
            filtered.forEach(function (opt, idx) {
                const item = document.createElement('div');
                item.className = 'search-select-option' + (idx === activeIndex ? ' active' : '');
                item.textContent = opt.label;
                item.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    choose(opt);
                });
                menu.appendChild(item);
            });
        }

        function openMenu() {
            filtered = filterOptions(input.value);
            activeIndex = filtered.findIndex(o => o.value === select.value);
            renderMenu();
            menu.classList.add('show');
        }

        function closeMenu() {
            menu.classList.remove('show');
        }

        function choose(opt) {
            select.value = opt.value;
            input.value = opt.label;
            input.classList.remove('is-invalid');
            closeMenu();
            select.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function scrollActiveIntoView() {
            const activeEl = menu.querySelector('.search-select-option.active');
            if (activeEl) activeEl.scrollIntoView({ block: 'nearest' });
        }

        input.addEventListener('focus', openMenu);
        input.addEventListener('click', openMenu);

        input.addEventListener('input', function () {
            select.value = '';
            openMenu();
        });

        input.addEventListener('keydown', function (e) {
            if (!menu.classList.contains('show')) {
                if (e.key === 'ArrowDown' || e.key === 'Enter') openMenu();
                return;
            }
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = Math.min(activeIndex + 1, filtered.length - 1);
                renderMenu();
                scrollActiveIntoView();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                renderMenu();
                scrollActiveIntoView();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeIndex >= 0 && filtered[activeIndex]) choose(filtered[activeIndex]);
            } else if (e.key === 'Escape') {
                closeMenu();
            }
        });

        input.addEventListener('blur', function () {
            setTimeout(function () {
                if (!select.value) input.value = '';
            }, 150);
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) closeMenu();
        });

        attachFormValidation(select.closest('form'));
    });
}
