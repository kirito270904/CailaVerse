/**
 * CAILA CONNECT - Frontend Controller & Smooth Micro-Interactions
 */
(function () {
    'use strict';

    /* ----- Toast Notification Engine ----- */
    var area = document.getElementById('toast-area');
    var toastIcons = {
        success: 'bi-check-circle-fill',
        danger: 'bi-exclamation-triangle-fill',
        warning: 'bi-exclamation-circle-fill',
        info: 'bi-info-circle-fill'
    };

    function showToast(message, type) {
        if (!area || !message) { return; }
        type = toastIcons[type] ? type : 'info';

        var el = document.createElement('div');
        el.className = 'caila-toast caila-toast-' + type;
        el.setAttribute('role', 'status');

        var icon = document.createElement('i');
        icon.className = 'bi ' + toastIcons[type];

        var text = document.createElement('span');
        text.textContent = message;

        var bar = document.createElement('div');
        bar.className = 'toast-bar';

        var duration = 3600;
        bar.style.animationDuration = duration + 'ms';

        el.appendChild(icon);
        el.appendChild(text);
        el.appendChild(bar);
        area.appendChild(el);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () { el.classList.add('show'); });
        });

        var timer = setTimeout(closeToast, duration);
        function closeToast() {
            clearTimeout(timer);
            el.classList.remove('show');
            el.classList.add('hide');
            setTimeout(function () {
                if (el.parentNode) { el.parentNode.removeChild(el); }
            }, 380);
        }

        el.addEventListener('click', closeToast);
    }

    window.cailaToast = showToast;
    window.smccToast = showToast; // Backwards compatibility

    var initialToasts = window.CAILA_TOASTS || window.SMCC_TOASTS || [];
    initialToasts.forEach(function (t, i) {
        setTimeout(function () { showToast(t.message, t.type); }, 300 + i * 350);
    });

    /* ----- Dark / Light Mode Switcher ----- */
    var root = document.documentElement;
    var themeBtn = document.getElementById('themeToggle');

    function syncThemeIcon() {
        if (!themeBtn) { return; }
        var isDark = root.getAttribute('data-bs-theme') === 'dark';
        themeBtn.innerHTML = isDark ? '<i class="bi bi-sun-fill text-warning"></i>' : '<i class="bi bi-moon-stars-fill"></i>';
    }
    syncThemeIcon();

    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            var current = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-bs-theme', current);
            try {
                localStorage.setItem('caila-theme', current);
                localStorage.setItem('smcc-theme', current);
            } catch (err) {}
            syncThemeIcon();
        });
    }

    /* ----- Password Visibility Toggle ----- */
    document.addEventListener('click', function (ev) {
        var btn = ev.target.closest('[data-toggle-password]');
        if (!btn) { return; }
        var input = document.querySelector(btn.getAttribute('data-toggle-password'));
        if (!input) { return; }
        var isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });

    /* ----- Image Preview & Validation ----- */
    function clearPreview(input) {
        var box = document.querySelector(input.getAttribute('data-preview'));
        if (box) {
            box.innerHTML = '';
            box.classList.add('d-none');
        }
    }

    document.addEventListener('change', function (ev) {
        var input = ev.target;
        if (input.type !== 'file' || !input.hasAttribute('data-preview')) { return; }
        var file = input.files[0];
        if (!file) {
            clearPreview(input);
            return;
        }

        if (!/^image\/(jpeg|png|gif|webp)$/i.test(file.type)) {
            showToast('Only JPG, PNG, GIF, or WEBP images are supported.', 'danger');
            input.value = '';
            clearPreview(input);
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            showToast('Image file size must be 2MB or smaller.', 'danger');
            input.value = '';
            clearPreview(input);
            return;
        }

        var box = document.querySelector(input.getAttribute('data-preview'));
        if (!box) { return; }

        box.innerHTML = '';
        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = 'Preview';
        box.appendChild(img);

        // Add clear button
        var clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'preview-clear-btn';
        clearBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
        clearBtn.title = 'Remove photo';
        clearBtn.addEventListener('click', function () {
            input.value = '';
            clearPreview(input);
        });
        box.appendChild(clearBtn);

        box.classList.remove('d-none');
    });

    /* ----- Live Character Counters ----- */
    function updateCounter(field) {
        var box = document.querySelector(field.getAttribute('data-counter'));
        if (box) {
            box.textContent = field.value.length + '/' + field.getAttribute('maxlength');
        }
    }
    document.querySelectorAll('[data-counter]').forEach(updateCounter);
    document.addEventListener('input', function (ev) {
        if (ev.target && ev.target.hasAttribute && ev.target.hasAttribute('data-counter')) {
            updateCounter(ev.target);
        }
    });

    /* ----- Keyboard Shortcuts (Ctrl + Enter to submit) ----- */
    document.addEventListener('keydown', function (ev) {
        if ((ev.ctrlKey || ev.metaKey) && ev.key === 'Enter') {
            var activeEl = document.activeElement;
            if (activeEl && (activeEl.tagName === 'TEXTAREA' || (activeEl.tagName === 'INPUT' && activeEl.type === 'text'))) {
                var form = activeEl.closest('form');
                if (form) {
                    ev.preventDefault();
                    var submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.click();
                    } else {
                        form.requestSubmit();
                    }
                }
            }
        }
    });

    /* ----- Helper Functions ----- */
    function htmlToEl(html) {
        var wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        return wrap.firstElementChild;
    }

    function fadeRemove(el, done) {
        el.classList.add('removing');
        setTimeout(function () {
            if (el.parentNode) { el.parentNode.removeChild(el); }
            if (done) { done(); }
        }, 360);
    }

    function updateCommentCount(card) {
        var count = card.querySelectorAll('.comment-item').length;
        var out = card.querySelector('.comment-count');
        if (out) { out.textContent = count; }
    }

    function checkEmptyFeed() {
        var feed = document.getElementById('feed');
        if (feed && !feed.querySelector('.post-card') && !document.getElementById('empty-feed')) {
            var empty = document.createElement('div');
            empty.className = 'empty-state fade-up';
            empty.id = 'empty-feed';
            empty.innerHTML = '<i class="bi bi-chat-heart"></i><p>No posts yet. Be the first to share something!</p>';
            feed.appendChild(empty);
        }
    }

    /* ----- AJAX Action Handlers ----- */
    var emojiMap = {
        like: '👍', love: '❤️', care: '🥰', haha: '😆',
        wow: '😮', sad: '😢', angry: '😡'
    };
    var labelMap = {
        like: 'Like', love: 'Love', care: 'Care', haha: 'Haha',
        wow: 'Wow', sad: 'Sad', angry: 'Angry'
    };

    var handlers = {
        react: function (form, res) {
            var card = form.closest('.post-card');
            var btn = form.querySelector('.react-btn');
            var iconHolder = btn.querySelector('.react-icon-holder');
            var labelHolder = btn.querySelector('.react-label');

            btn.className = btn.className.replace(/reacted-\w+/g, '').trim();

            if (res.liked && res.reaction_type) {
                btn.classList.add('reacted-' + res.reaction_type);
                if (iconHolder) iconHolder.textContent = emojiMap[res.reaction_type] || '👍';
                if (labelHolder) labelHolder.textContent = labelMap[res.reaction_type] || 'Like';
                form.querySelector('.reaction-input').value = res.reaction_type;
            } else {
                if (iconHolder) iconHolder.textContent = '👍';
                if (labelHolder) labelHolder.textContent = 'Like';
                form.querySelector('.reaction-input').value = 'like';
            }

            btn.classList.remove('pop');
            void btn.offsetWidth;
            btn.classList.add('pop');

            var countEl = card.querySelector('.like-count');
            if (countEl) {
                countEl.textContent = res.count;
            }
        },

        like: function (form, res) {
            // Forward to react handler for compatibility
            handlers.react(form, res);
        },

        follow: function (form, res) {
            var btn = form.querySelector('button');
            var icon = btn.querySelector('i');
            var text = btn.querySelector('.connect-btn-text') || btn.querySelector('.follow-text');

            btn.classList.toggle('following', res.following);
            if (btn.classList.contains('profile-follow-btn')) {
                btn.classList.toggle('btn-soft', res.following);
                btn.classList.toggle('btn-caila', !res.following);
            }
            if (icon) {
                icon.className = 'bi ' + (res.following ? 'bi-person-check-fill text-success' : 'bi-person-plus-fill');
            }
            if (text) {
                text.textContent = res.following ? 'Friends' : 'Add Friend';
            }
            var fCount = document.querySelector('.followers-count');
            if (fCount && typeof res.count !== 'undefined') {
                fCount.textContent = res.count;
            }
        },

        'share-post': function (form, res) {
            var modalEl = document.getElementById('shareModal');
            if (modalEl && window.bootstrap) {
                var m = bootstrap.Modal.getInstance(modalEl);
                if (m) m.hide();
            }
            var feed = document.getElementById('feed');
            var empty = document.getElementById('empty-feed');
            if (empty && empty.parentNode) {
                empty.parentNode.removeChild(empty);
            }
            if (feed && res.html) {
                var newCard = htmlToEl(res.html);
                feed.insertBefore(newCard, feed.firstChild);
            }
            form.reset();

            var origId = form.querySelector('#sharePostId').value;
            var origCard = document.querySelector('#post-' + origId);
            if (origCard) {
                var sc = origCard.querySelector('.share-count');
                if (sc) sc.textContent = (parseInt(sc.textContent, 10) || 0) + 1;
            }
        },

        'delete-post': function (form) {
            fadeRemove(form.closest('.post-card'), checkEmptyFeed);
        },

        'delete-comment': function (form) {
            var card = form.closest('.post-card');
            fadeRemove(form.closest('.comment-item'), function () {
                updateCommentCount(card);
            });
        },

        comment: function (form, res) {
            var card = form.closest('.post-card');
            var list = card.querySelector('.comment-list');
            var newComment = htmlToEl(res.html);
            list.appendChild(newComment);
            form.querySelector('[name="content"]').value = '';
            updateCommentCount(card);
        },

        post: function (form, res) {
            var feed = document.getElementById('feed');
            var empty = document.getElementById('empty-feed');
            if (empty && empty.parentNode) {
                empty.parentNode.removeChild(empty);
            }
            var newCard = htmlToEl(res.html);
            feed.insertBefore(newCard, feed.firstChild);
            form.reset();
            form.querySelectorAll('[data-preview]').forEach(clearPreview);
            form.querySelectorAll('[data-counter]').forEach(updateCounter);
        }
    };

    function runAjax(form) {
        var kind = form.getAttribute('data-ajax');
        var btn = form.querySelector('button[type="submit"]');
        if (btn) { btn.disabled = true; }

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (!res.ok) {
                    showToast(res.message || 'Something went wrong.', 'danger');
                    return;
                }
                if (handlers[kind]) {
                    handlers[kind](form, res);
                }
                if (res.message) {
                    showToast(res.message, 'success');
                }
            })
            .catch(function () {
                showToast('Network error. Please check your connection.', 'danger');
            })
            .then(function () {
                if (btn) { btn.disabled = false; }
                form.__confirmed = false;
            });
    }

    /* ----- Confirm Modal Setup ----- */
    var modalEl = document.getElementById('confirmModal');
    var modal = (modalEl && window.bootstrap) ? new bootstrap.Modal(modalEl) : null;
    var pendingForm = null;

    var yesBtn = document.getElementById('confirmYes');
    if (yesBtn) {
        yesBtn.addEventListener('click', function () {
            var form = pendingForm;
            pendingForm = null;
            if (modal) { modal.hide(); }
            if (!form) { return; }
            form.__confirmed = true;
            if (form.hasAttribute('data-ajax')) {
                runAjax(form);
            } else {
                form.submit();
            }
        });
    }

    /* ----- Form Submit Interceptor ----- */
    document.addEventListener('submit', function (ev) {
        var form = ev.target;
        if (!(form instanceof HTMLFormElement)) { return; }

        if (form.classList.contains('needs-validation')) {
            var pw = form.querySelector('[name="password"]');
            var cf = form.querySelector('[name="confirm"]');
            if (pw && cf) {
                cf.setCustomValidity(pw.value !== cf.value ? 'Passwords do not match' : '');
            }
            if (!form.checkValidity()) {
                ev.preventDefault();
                ev.stopPropagation();
                form.classList.add('was-validated');
                showToast('Please check the required fields.', 'warning');
            } else {
                var submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                }
            }
            return;
        }

        if (form.hasAttribute('data-confirm') && !form.__confirmed) {
            ev.preventDefault();
            if (modal) {
                pendingForm = form;
                var textEl = document.getElementById('confirmText');
                if (textEl) {
                    textEl.textContent = form.getAttribute('data-confirm') + ' This action cannot be undone.';
                }
                modal.show();
                return;
            }
            if (!window.confirm(form.getAttribute('data-confirm'))) { return; }
            form.__confirmed = true;
        }

        if (form.hasAttribute('data-ajax')) {
            ev.preventDefault();
            runAjax(form);
        } else if (form.__confirmed) {
            form.submit();
        }
    });

    /* ----- Tag Chip Inserter & Share Link Copier ----- */
    document.addEventListener('click', function (ev) {
        var tagBtn = ev.target.closest('[data-insert-tag]');
        if (tagBtn) {
            var textarea = document.querySelector('.composer-text');
            if (textarea) {
                var tag = tagBtn.getAttribute('data-insert-tag');
                var val = textarea.value.trim();
                textarea.value = (val ? (val + ' ') : '') + tag + ' ';
                textarea.focus();
                var counter = document.getElementById('post-counter');
                if (counter) {
                    counter.textContent = textarea.value.length + '/1000';
                }
            }
            return;
        }

        var shareBtn = ev.target.closest('.share-post-btn');
        if (shareBtn) {
            var path = shareBtn.getAttribute('data-copy-link') || window.location.href;
            var fullUrl = window.location.origin + window.location.pathname.replace(/[^\/]*$/, '') + path;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(fullUrl)
                    .then(function () { showToast('Post link copied to clipboard!', 'success'); })
                    .catch(function () { showToast('Link ready: ' + fullUrl, 'info'); });
            } else {
                showToast('Link ready: ' + fullUrl, 'info');
            }
            return;
        }

        /* Facebook Reaction Dock Item Click */
        var reactionBtn = ev.target.closest('.reaction-item');
        if (reactionBtn) {
            ev.preventDefault();
            ev.stopPropagation();
            var dock = reactionBtn.closest('.fb-reactions-dock');
            var wrap = dock ? dock.closest('.reaction-wrapper') : null;
            if (wrap) {
                wrap.classList.remove('dock-visible', 'dock-open');
                var form = wrap.querySelector('.reaction-form');
                var reaction = reactionBtn.getAttribute('data-reaction');
                if (form && reaction) {
                    form.querySelector('.reaction-input').value = reaction;
                    runAjax(form);
                }
            }
            return;
        }

        /* Open Facebook Share Modal */
        var openShareBtn = ev.target.closest('.open-share-modal-btn');
        if (openShareBtn) {
            var postId = openShareBtn.getAttribute('data-post-id');
            var author = openShareBtn.getAttribute('data-post-author');
            var snippet = openShareBtn.getAttribute('data-post-content');

            var idInput = document.getElementById('sharePostId');
            if (idInput) idInput.value = postId;
            var authEl = document.getElementById('sharePreviewAuthor');
            if (authEl) authEl.textContent = 'Original post by ' + author;
            var snipEl = document.getElementById('sharePreviewSnippet');
            if (snipEl) snipEl.textContent = snippet || 'Shared media/post';

            var shareModalEl = document.getElementById('shareModal');
            if (shareModalEl && window.bootstrap) {
                var modalInstance = bootstrap.Modal.getOrCreateInstance(shareModalEl);
                modalInstance.show();
            }
            return;
        }

        /* Share Modal Copy Link */
        var shareCopyBtn = ev.target.closest('.share-copy-btn');
        if (shareCopyBtn) {
            var idInput = document.getElementById('sharePostId');
            var postId = idInput ? idInput.value : '';
            var fullUrl = window.location.origin + window.location.pathname + '?c=post&a=feed#post-' + postId;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(fullUrl).then(function () {
                    showToast('Link copied to clipboard!', 'success');
                });
            } else {
                showToast('Link ready: ' + fullUrl, 'info');
            }
            return;
        }

        /* Clicking outside reaction wrapper closes open docks */
        if (!ev.target.closest('.reaction-wrapper')) {
            document.querySelectorAll('.reaction-wrapper.dock-visible, .reaction-wrapper.dock-open').forEach(function (w) {
                w.classList.remove('dock-visible', 'dock-open');
            });
        }
    });

    /* ----- Smooth Reaction Dock Hover Grace Period & Touch Support ----- */
    var dockHideTimers = new WeakMap();

    document.addEventListener('mouseover', function (ev) {
        var wrap = ev.target.closest('.reaction-wrapper');
        if (!wrap) { return; }
        var timer = dockHideTimers.get(wrap);
        if (timer) {
            clearTimeout(timer);
            dockHideTimers.delete(wrap);
        }
        wrap.classList.add('dock-visible');
    });

    document.addEventListener('mouseout', function (ev) {
        var wrap = ev.target.closest('.reaction-wrapper');
        if (!wrap) { return; }
        if (wrap.contains(ev.relatedTarget)) { return; }
        var timer = setTimeout(function () {
            wrap.classList.remove('dock-visible', 'dock-open');
            dockHideTimers.delete(wrap);
        }, 450); // 450ms grace period so user can move freely to emojis
        dockHideTimers.set(wrap, timer);
    });

    /* Touch support: Tapping or holding reaction button toggles dock on mobile */
    var touchHoldTimer = null;
    document.addEventListener('touchstart', function (ev) {
        var btn = ev.target.closest('.react-btn');
        if (!btn) { return; }
        var wrap = btn.closest('.reaction-wrapper');
        if (!wrap) { return; }
        touchHoldTimer = setTimeout(function () {
            wrap.classList.toggle('dock-visible');
        }, 220);
    }, { passive: true });

    document.addEventListener('touchend', function () {
        if (touchHoldTimer) {
            clearTimeout(touchHoldTimer);
            touchHoldTimer = null;
        }
    });
})();
