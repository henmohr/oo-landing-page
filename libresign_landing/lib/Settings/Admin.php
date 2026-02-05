<?php

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\LibresignLanding\Settings;

use OCA\LibresignLanding\AppInfo\Application;
use OCA\LibresignLanding\Service\ConfigDefaults;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IAppConfig;
use OCP\IURLGenerator;
use OCP\Settings\ISettings;
use OCP\Util;

class Admin implements ISettings {
	public function __construct(
		private IAppConfig $appConfig,
		private IURLGenerator $urlGenerator,
	) {
	}

	public function getForm() {
		$defaults = ConfigDefaults::defaults();

		$plans = $this->appConfig->getValueArray(Application::APP_ID, 'plans', $defaults['plans']);
		$benefits = $this->appConfig->getValueArray(Application::APP_ID, 'benefits', $defaults['benefits']);
		$logoFilename = $this->appConfig->getValueString(Application::APP_ID, 'logo_filename', '');
		$logoUrl = $logoFilename !== '' ? $this->urlGenerator->linkToRouteAbsolute('libresign_landing.page.logo') : '';

		Util::addStyle(Application::APP_ID, 'admin');

		return new TemplateResponse(Application::APP_ID, 'admin', [
			'enabled' => $this->appConfig->getValueBool(Application::APP_ID, 'enabled', true),
			'heroTitle' => $this->appConfig->getValueString(Application::APP_ID, 'hero_title', $defaults['heroTitle']),
			'heroSubtitle' => $this->appConfig->getValueString(Application::APP_ID, 'hero_subtitle', $defaults['heroSubtitle']),
			'ctaPrimary' => $this->appConfig->getValueString(Application::APP_ID, 'cta_primary', $defaults['ctaPrimary']),
			'ctaSecondary' => $this->appConfig->getValueString(Application::APP_ID, 'cta_secondary', $defaults['ctaSecondary']),
			'primaryColor' => $this->appConfig->getValueString(Application::APP_ID, 'primary_color', $defaults['primaryColor']),
			'accentColor' => $this->appConfig->getValueString(Application::APP_ID, 'accent_color', $defaults['accentColor']),
			'backgroundTop' => $this->appConfig->getValueString(Application::APP_ID, 'background_top', $defaults['backgroundTop']),
			'backgroundBottom' => $this->appConfig->getValueString(Application::APP_ID, 'background_bottom', $defaults['backgroundBottom']),
			'signupUrl' => $this->appConfig->getValueString(Application::APP_ID, 'signup_url', $defaults['signupUrl']),
			'companyName' => $this->appConfig->getValueString(Application::APP_ID, 'company_name', $defaults['companyName']),
			'companyInfo' => $this->appConfig->getValueString(Application::APP_ID, 'company_info', $defaults['companyInfo']),
			'contactEmail' => $this->appConfig->getValueString(Application::APP_ID, 'contact_email', $defaults['contactEmail']),
			'termsUrl' => $this->appConfig->getValueString(Application::APP_ID, 'terms_url', $defaults['termsUrl']),
			'privacyUrl' => $this->appConfig->getValueString(Application::APP_ID, 'privacy_url', $defaults['privacyUrl']),
			'docsUrl' => $this->appConfig->getValueString(Application::APP_ID, 'docs_url', $defaults['docsUrl']),
			'plans' => $plans,
			'benefits' => $benefits,
			'logoUrl' => $logoUrl,
		], '');
	}

	public function getSection() {
		return 'server';
	}

	public function getPriority() {
		return 80;
	}
}
