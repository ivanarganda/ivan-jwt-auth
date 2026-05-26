<script>
    const BASE_URL = <?php echo json_encode($baseUrl); ?>;
    const TOKEN_STORAGE_KEY = 'ivan_jwt_auth_token';
    const HISTORY_STORAGE_KEY = 'ivan_jwt_auth_history';

    const endpointMap = {
        show: { method: 'GET', path: '/show?limit=50&offset=0' },
        register: { method: 'POST', path: '/register' },
        login: { method: 'POST', path: '/login' }
    };

    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const sections = document.querySelectorAll('main .section[id]');

    function initScrollSpy() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const currentId = entry.target.id;
                    sidebarLinks.forEach(link => {
                        link.classList.toggle('active', link.getAttribute('href') === '#' + currentId);
                    });
                }
            });
        }, {
            threshold: 0.35
        });

        sections.forEach(section => observer.observe(section));
    }

    function initTabs() {
        document.querySelectorAll('.endpoint-shell').forEach(shell => {
            const buttons = shell.querySelectorAll('.tab-btn');
            const contents = shell.querySelectorAll('.endpoint-tab-content');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.target;

                    buttons.forEach(btn => btn.classList.remove('active'));
                    contents.forEach(content => content.classList.remove('active'));

                    button.classList.add('active');
                    shell.querySelector('#' + targetId).classList.add('active');
                });
            });
        });
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'toast ' + type;
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3200);
    }

    function copyText(text, button = null, temporaryText = 'Copied') {
        navigator.clipboard.writeText(text).then(() => {
            showToast(temporaryText === 'Copied' ? 'Copied to clipboard' : temporaryText);
            if (button) {
                const original = button.textContent;
                button.textContent = temporaryText;
                setTimeout(() => {
                    button.textContent = original;
                }, 1400);
            }
        }).catch(err => {
            console.error('Copy failed:', err);
            showToast('Could not copy', 'error');
        });
    }

    function initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('visible');
            backdrop.hidden = true;
        }

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.classList.add('visible');
            backdrop.hidden = false;
        }

        toggle.addEventListener('click', () => {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        backdrop.addEventListener('click', closeSidebar);

        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1150) {
                    closeSidebar();
                }
            });
        });
    }

    document.getElementById('copyBaseUrlBtn').addEventListener('click', function () {
        copyText(BASE_URL, this, 'Copied');
    });

    document.querySelectorAll('.copy-endpoint-btn').forEach(button => {
        button.addEventListener('click', function () {
            copyText(this.dataset.endpoint, this, 'Copied');
        });
    });

    function getStoredToken() {
        return localStorage.getItem(TOKEN_STORAGE_KEY) || '';
    }

    function setStoredToken(token) {
        localStorage.setItem(TOKEN_STORAGE_KEY, token);
        updateStoredTokenUI();
    }

    function clearStoredToken() {
        localStorage.removeItem(TOKEN_STORAGE_KEY);
        updateStoredTokenUI();
        document.querySelectorAll('.try-token').forEach(input => {
            input.value = '';
        });
    }

    function maskToken(token) {
        if (!token) return 'No token stored yet.';
        if (token.length <= 30) return token;
        return token.substring(0, 20) + ' … ' + token.substring(token.length - 12);
    }

    function updateStoredTokenUI() {
        const token = getStoredToken();
        const sidebarState = document.getElementById('sidebarTokenState');
        const storedTokenBox = document.getElementById('storedTokenBox');

        sidebarState.textContent = token
            ? 'JWT token saved in localStorage.'
            : 'No JWT token saved yet.';
        storedTokenBox.textContent = maskToken(token);
        storedTokenBox.title = token || '';
    }

    document.getElementById('clearStoredTokenBtn').addEventListener('click', clearStoredToken);

    document.getElementById('copyStoredTokenBtn').addEventListener('click', function () {
        const token = getStoredToken();
        if (!token) return;
        copyText(token, this, 'Copied');
    });

    document.getElementById('useStoredTokenBtn').addEventListener('click', function () {
        const token = getStoredToken();
        if (!token) {
            showToast('No token stored', 'error');
            return;
        }

        document.querySelectorAll('.try-token').forEach(input => {
            input.value = token;
        });

        showToast('Token loaded into fields');
        const original = this.textContent;
        this.textContent = 'Loaded';
        setTimeout(() => this.textContent = original, 1400);
    });

    function getHistory() {
        try {
            return JSON.parse(localStorage.getItem(HISTORY_STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveHistory(history) {
        localStorage.setItem(HISTORY_STORAGE_KEY, JSON.stringify(history));
        renderHistory();
    }

    function addHistoryItem(item) {
        const history = getHistory();
        history.unshift(item);
        if (history.length > 10) history.pop();
        saveHistory(history);
    }

    function renderHistory() {
        const historyList = document.getElementById('historyList');
        const history = getHistory();

        if (!history.length) {
            historyList.innerHTML = '<p class="muted">No requests stored yet.</p>';
            return;
        }

        historyList.innerHTML = history.map((item, index) => `
            <div class="history-item" data-history-index="${index}" title="Click to copy response">
                <div class="history-item-head">
                    <div>
                        <strong>${item.method}</strong> <span class="muted">${item.status}</span>
                    </div>
                    <div class="muted">${item.time}</div>
                </div>
                <div class="history-item-url">${item.url}</div>
            </div>
        `).join('');

        historyList.querySelectorAll('.history-item').forEach(el => {
            el.addEventListener('click', () => {
                const item = history[Number(el.dataset.historyIndex)];
                if (!item || !item.responsePreview) return;
                showToast('Last response copied to clipboard');
                copyText(item.responsePreview);
            });
        });
    }

    document.getElementById('clearHistoryBtn').addEventListener('click', () => {
        localStorage.removeItem(HISTORY_STORAGE_KEY);
        renderHistory();
    });

    function setResponseMeta(container, { status = '-', ok = null, warn = false, time = '-', method = '-' }) {
        const statusEl = container.querySelector('.try-status');
        const methodEl = container.querySelector('.try-method');
        const timeEl = container.querySelector('.try-time');

        statusEl.className = 'pill try-status';
        if (ok === true) statusEl.classList.add('ok');
        else if (ok === false) statusEl.classList.add('error');
        else if (warn) statusEl.classList.add('warn');

        statusEl.textContent = `Status: ${status}`;
        methodEl.textContent = `Method: ${method}`;
        timeEl.textContent = `Time: ${time}`;
    }

    function classifyResponse(statusCode) {
        if (statusCode >= 200 && statusCode < 300) return { ok: true, warn: false };
        if (statusCode === 409 || statusCode === 422) return { ok: false, warn: true };
        return { ok: false, warn: false };
    }

    function prettyPrint(value) {
        if (typeof value === 'string') return value;
        return JSON.stringify(value, null, 2);
    }

    function getEndpointContainer(button) {
        return button.closest('.endpoint-tab-content');
    }

    async function sendEndpointRequest(endpointKey, uiContainer) {
        const endpoint = endpointMap[endpointKey];
        const url = BASE_URL + endpoint.path;
        const startedAt = performance.now();

        const responseBox = uiContainer.querySelector('.try-response');
        const tokenInput = uiContainer.querySelector('.try-token');
        const bodyInput = uiContainer.querySelector('.try-body');

        const headers = {
            'Content-Type': 'application/json'
        };

        const tokenValue = tokenInput ? tokenInput.value.trim() : '';
        if (tokenValue) {
            headers['Authorization'] = `Bearer ${tokenValue}`;
        }

        const options = {
            method: endpoint.method,
            headers
        };

        if (endpoint.method !== 'GET') {
            try {
                const parsed = JSON.parse(bodyInput.value || '{}');
                options.body = JSON.stringify(parsed);
            } catch (error) {
                responseBox.textContent = 'Invalid JSON in request body: ' + error.message;
                setResponseMeta(uiContainer, {
                    status: 'Invalid JSON',
                    ok: false,
                    time: '-',
                    method: endpoint.method
                });
                showToast('Invalid JSON in request body', 'error');
                return;
            }
        }

        responseBox.textContent = 'Sending request...';

        try {
            const res = await fetch(url, options);
            const elapsed = `${Math.round(performance.now() - startedAt)} ms`;
            const { ok, warn } = classifyResponse(res.status);

            let data;
            const contentType = res.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                data = await res.json();
            } else {
                data = await res.text();
            }

            const printed = prettyPrint(data);
            responseBox.textContent = printed;

            setResponseMeta(uiContainer, {
                status: `${res.status} ${res.statusText}`,
                ok,
                warn,
                time: elapsed,
                method: endpoint.method
            });

            if (res.ok) {
                showToast(`${endpoint.method} ${endpoint.path} · ${res.status}`, 'success');
            } else if (warn) {
                showToast(`${res.status} · ${data?.message || 'Validation or conflict'}`, 'error');
            } else {
                showToast(`${res.status} · Request error`, 'error');
            }

            if (endpointKey === 'login' && data && typeof data === 'object' && data.token) {
                setStoredToken(data.token);
                showToast('JWT token saved');

                document.querySelectorAll('.try-token').forEach(input => {
                    if (!input.value.trim()) input.value = data.token;
                });
            }

            addHistoryItem({
                method: endpoint.method,
                status: `${res.status} ${res.statusText}`,
                url,
                time: new Date().toLocaleString(),
                responsePreview: printed
            });
        } catch (error) {
            const elapsed = `${Math.round(performance.now() - startedAt)} ms`;
            responseBox.textContent = `Network error: ${error.message}`;

            setResponseMeta(uiContainer, {
                status: 'Failed',
                ok: false,
                time: elapsed,
                method: endpoint.method
            });

            showToast('Could not reach the API', 'error');

            addHistoryItem({
                method: endpoint.method,
                status: 'Network error',
                url,
                time: new Date().toLocaleString(),
                responsePreview: error.message
            });
        }
    }

    document.querySelectorAll('.try-btn').forEach(button => {
        button.addEventListener('click', () => {
            const endpointKey = button.dataset.endpointKey;
            const container = getEndpointContainer(button);
            sendEndpointRequest(endpointKey, container);
        });
    });

    document.querySelectorAll('.format-btn').forEach(button => {
        button.addEventListener('click', () => {
            const endpointKey = button.dataset.endpointKey;
            const bodyInput = document.querySelector(`.try-body[data-endpoint-key="${endpointKey}"]`);
            if (!bodyInput || !bodyInput.value.trim()) return;

            try {
                const parsed = JSON.parse(bodyInput.value);
                bodyInput.value = JSON.stringify(parsed, null, 2);

                const original = button.textContent;
                button.textContent = 'Formatted';
                showToast('JSON formatted');
                setTimeout(() => button.textContent = original, 1200);
            } catch (error) {
                const container = getEndpointContainer(button);
                const responseBox = container.querySelector('.try-response');
                responseBox.textContent = 'Invalid JSON: ' + error.message;
                setResponseMeta(container, {
                    status: 'Invalid JSON',
                    ok: false,
                    time: '-',
                    method: endpointMap[endpointKey].method
                });
                showToast('Invalid JSON in request body', 'error');
            }
        });
    });

    window.addEventListener('load', () => {
        initScrollSpy();
        initTabs();
        initSidebar();
        updateStoredTokenUI();
        renderHistory();

        const token = getStoredToken();
        if (token) {
            document.querySelectorAll('.try-token').forEach(input => {
                input.value = token;
            });
        }

        document.querySelectorAll('.endpoint-tab-content').forEach(content => {
            const methodText = content.querySelector('.try-method');
            if (methodText) {
                const endpointId = content.id.split('-')[0];
                if (endpointMap[endpointId]) {
                    setResponseMeta(content, {
                        status: '-',
                        ok: null,
                        time: '-',
                        method: endpointMap[endpointId].method
                    });
                }
            }
        });
    });
</script>