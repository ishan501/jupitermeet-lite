(function () {
    const BUY_URL = 'https://jupitersoftwares.io/products/jupitermeet-pro#pricing';
    const ADMIN_CLASS = 'jm-promo-badge-admin';
    const USER_CLASS = 'jm-promo-badge-user';

    const ADMIN_HTML = `
        <a href="${BUY_URL}" target="_blank" class="text-decoration-none d-flex align-items-center p-2 rounded ${ADMIN_CLASS}" title="Upgrade to JupiterMeet Pro" style="display: flex !important; visibility: visible !important; opacity: 1 !important; background-color: #343a40 !important; color: white !important;">
            <span class="text-white fw-medium ms-2 me-2">Get JupiterMeet Pro</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem text-primary" viewBox="0 0 16 16">
                <path d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z"/>
            </svg>
        </a>
    `;

    const USER_HTML = `
        <a href="${BUY_URL}" target="_blank" class="btn btn-dark ${USER_CLASS}" title="Upgrade to JupiterMeet Pro" style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important; background-color: #343a40 !important; color: white !important; border-color: #343a40 !important;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem me-2 text-primary" viewBox="0 0 16 16">
                <path d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z"/>
            </svg>
            Get JupiterMeet Pro
        </a>
    `;

    function getContext() {
        const path = window.location.pathname;
        if (path.includes('/admin')) return 'admin';
        if (path.includes('/profile')) return 'profile';
        if (document.querySelector('#meetingDashboard') && document.querySelector('.jm-dashboard')) return 'dashboard';
        if (document.querySelector('.jm-homepage')) return 'homepage';
        if (document.querySelector('.jm-meeting.meeting-details')) return 'meeting_preview';
        return null;
    }

    function enforcePromo() {
        const context = getContext();
        if (!context) return;

        let badge = null;
        let targetSelector = '';
        let injectMethod = '';
        let htmlToInject = '';
        let expectedClass = '';

        if (context === 'admin') {
            targetSelector = '#sidebar-menu > .navbar-nav';
            expectedClass = ADMIN_CLASS;
            htmlToInject = `<div class="mt-auto p-3 w-100 d-none d-lg-block jm-promo-container">${ADMIN_HTML}</div>`;
            injectMethod = 'after'; // Insert after the ul.navbar-nav
            badge = document.querySelector('.' + ADMIN_CLASS);
        } else if (context === 'dashboard') {
            targetSelector = '#meetingDashboard';
            expectedClass = USER_CLASS;
            htmlToInject = USER_HTML;
            injectMethod = 'before';
            badge = document.querySelector('.' + USER_CLASS);
        } else if (context === 'profile') {
            targetSelector = '.jm-header-right';
            expectedClass = USER_CLASS;
            htmlToInject = USER_HTML;
            injectMethod = 'append';
            badge = document.querySelector('.' + USER_CLASS);
        } else if (context === 'homepage') {
            targetSelector = '.jm-promo-container-home';
            expectedClass = USER_CLASS;
            htmlToInject = USER_HTML;
            injectMethod = 'append';
            badge = document.querySelector('.jm-promo-container-home .' + USER_CLASS);
        } else if (context === 'meeting_preview') {
            targetSelector = '.jm-meeting-start-action';
            expectedClass = USER_CLASS;
            htmlToInject = `<div class="d-flex justify-content-start w-100 jm-promo-container-meeting">${USER_HTML}</div>`;
            injectMethod = 'after';
            badge = document.querySelector('.jm-promo-container-meeting .' + USER_CLASS);
        }

        // If badge doesn't exist, inject it
        if (!badge) {
            const target = document.querySelector(targetSelector);
            if (target) {
                if (injectMethod === 'after') {
                    // Check if container already exists
                    if (!document.querySelector('.jm-promo-container')) {
                        target.insertAdjacentHTML('afterend', htmlToInject);
                    } else if (!document.querySelector('.jm-promo-container .' + expectedClass)) {
                        document.querySelector('.jm-promo-container').innerHTML = ADMIN_HTML;
                    }
                } else if (injectMethod === 'before') {
                    target.insertAdjacentHTML('beforebegin', htmlToInject);
                } else if (injectMethod === 'append') {
                    target.insertAdjacentHTML('beforeend', htmlToInject);
                }
            }
        } else {
            // Enforce visibility
            const style = window.getComputedStyle(badge);
            if (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0') {
                badge.style.setProperty('display', context === 'admin' ? 'flex' : 'inline-flex', 'important');
                badge.style.setProperty('visibility', 'visible', 'important');
                badge.style.setProperty('opacity', '1', 'important');
            }
        }
    }

    window.JM_SYSTEM_READY = true;

    function initAntiTamper() {
        enforcePromo();

        const observer = new MutationObserver((mutations) => {
            let needsEnforcement = false;
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    needsEnforcement = true;
                } else if (mutation.type === 'attributes') {
                    const targetClass = mutation.target.className || '';
                    if (typeof targetClass === 'string' && (targetClass.includes(ADMIN_CLASS) || targetClass.includes(USER_CLASS))) {
                        needsEnforcement = true;
                    }
                }
            });

            if (needsEnforcement) {
                observer.disconnect();
                enforcePromo();
                observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });
            }
        });

        observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAntiTamper);
    } else {
        initAntiTamper();
    }
})();
