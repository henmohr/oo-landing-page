(() => {
	const metaEnabled = document.querySelector('meta[name="libresign-landing-enabled"]');
	const metaFragment = document.querySelector('meta[name="libresign-landing-fragment"]');
	const enabled = metaEnabled?.getAttribute('content') === '1';
	const fragmentUrl = metaFragment?.getAttribute('content') || '';

	const params = new URLSearchParams(window.location.search);
	const forceLogin = params.get('login') === '1' || params.get('redirect') === 'true' || params.get('landing') === '0';

	if (!enabled || forceLogin || !fragmentUrl) {
		return;
	}

	document.body.classList.add('libresign-landing-boot');
	document.body.classList.add('libresign-landing-loading');

	const waitForElement = (selector, timeoutMs = 5000) => new Promise((resolve) => {
		const existing = document.querySelector(selector);
		if (existing) {
			resolve(existing);
			return;
		}
		const observer = new MutationObserver(() => {
			const found = document.querySelector(selector);
			if (found) {
				observer.disconnect();
				resolve(found);
			}
		});
		observer.observe(document.documentElement, { childList: true, subtree: true });
		setTimeout(() => {
			observer.disconnect();
			resolve(null);
		}, timeoutMs);
	});

	const replaceLogin = async () => {
		try {
			const response = await fetch(fragmentUrl, { credentials: 'same-origin' });
			if (!response.ok) {
				console.warn('[libresign_landing] fragment fetch failed', response.status, fragmentUrl);
				document.body.classList.add('libresign-landing-failed');
				return;
			}
			const html = await response.text();
			if (!html || !html.trim()) {
				console.warn('[libresign_landing] fragment empty');
				document.body.classList.add('libresign-landing-failed');
				return;
			}

			const loginRoot = await waitForElement('#login', 5000);
			const host = loginRoot || document.querySelector('.wrapper') || document.body;
			if (!host) {
				console.warn('[libresign_landing] no host container found');
				document.body.classList.add('libresign-landing-failed');
				return;
			}
			const landingRoot = document.createElement('div');
			landingRoot.id = 'libresign-landing-root';
			landingRoot.innerHTML = html;
			document.body.prepend(landingRoot);
			document.body.classList.add('libresign-login-hidden');
			document.body.classList.add('libresign-landing-active');
			document.body.classList.remove('libresign-landing-loading');
			if (typeof window.LibresignLandingInit === 'function') {
				window.LibresignLandingInit();
			}
		} catch (err) {
			// ignore and keep default login
			document.body.classList.add('libresign-landing-failed');
			document.body.classList.remove('libresign-landing-loading');
		}
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', replaceLogin);
	} else {
		replaceLogin();
	}
})();
