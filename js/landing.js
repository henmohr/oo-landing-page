(() => {
	const init = () => {
		const revealElements = document.querySelectorAll('[data-reveal]');
		if ('IntersectionObserver' in window) {
			const observer = new IntersectionObserver((entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			}, { threshold: 0.12 });

			revealElements.forEach((el) => observer.observe(el));
		} else {
			revealElements.forEach((el) => el.classList.add('is-visible'));
		}

		const signupInput = document.getElementById('libresign-signup');
		const signupBase = signupInput ? signupInput.value : '';
		const ctaButtons = document.querySelectorAll('.plan-cta');

		const buildSignupUrl = (plan) => {
			if (!signupBase) {
				return '#';
			}
			const separator = signupBase.includes('?') ? '&' : '?';
			return `${signupBase}${separator}plan=${encodeURIComponent(plan)}`;
		};

		ctaButtons.forEach((button) => {
			button.addEventListener('click', (event) => {
				event.preventDefault();
				const plan = button.getAttribute('data-plan') || 'professional';
				button.classList.add('loading');
				window.location.href = buildSignupUrl(plan);
			});
		});
	};

	window.LibresignLandingInit = init;
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
