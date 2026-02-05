<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\LibresignLanding\Controller;

use OCA\LibresignLanding\AppInfo\Application;
use OC\Core\Controller\LoginController;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\AnonRateLimit;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\PublicPage;
use OCP\AppFramework\Http\FileDisplayResponse;
use OCP\AppFramework\Http\NotFoundResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Files\IAppData;
use OCP\Files\NotFoundException;
use OCP\IAppConfig;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\Util;
use OCA\LibresignLanding\Service\ConfigDefaults;
use Psr\Container\ContainerInterface;

class PageController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private IUserSession $userSession,
		private IURLGenerator $urlGenerator,
		private IAppConfig $appConfig,
		private IInitialState $initialState,
		private IAppData $appData,
		private ContainerInterface $serverContainer,
	) {
		parent::__construct($appName, $request);
	}

	#[PublicPage]
	#[NoCSRFRequired]
	#[AnonRateLimit(limit: 120, period: 60)]
	public function landing(): Response {
		if ($this->userSession->isLoggedIn()) {
			return new RedirectResponse($this->urlGenerator->linkToDefaultPageUrl());
		}

		$config = $this->getConfig();
		$fragment = $this->request->getParam('fragment') === '1';

		$forceLogin = !$fragment && ($this->request->getParam('login') === '1'
			|| $this->request->getParam('landing') === '0'
			|| $this->request->getParam('redirect') === 'true'
			|| $config['enabled'] !== true);

		if ($forceLogin) {
			return $this->coreLogin();
		}

		$logoUrl = $config['logoFilename'] !== ''
			? $this->urlGenerator->linkToRouteAbsolute('libresign_landing.page.logo')
			: '';

		$signupUrl = $config['signupUrl'];
		if ($signupUrl === '') {
			$signupUrl = '/index.php/apps/registration/';
		}

		$loginUrl = $this->urlGenerator->linkToRouteAbsolute('core.login.showLoginForm', ['login' => '1']);

		$this->initialState->provideInitialState('libresignLanding', [
			'plans' => $config['plans'],
			'signupUrl' => $signupUrl,
		]);

		Util::addStyle(Application::APP_ID, 'landing');
		Util::addScript(Application::APP_ID, 'landing');

		$renderAs = $fragment ? TemplateResponse::RENDER_AS_BLANK : TemplateResponse::RENDER_AS_GUEST;

		return new TemplateResponse(
			Application::APP_ID,
			'landing',
			[
				'config' => $config,
				'logoUrl' => $logoUrl,
				'loginUrl' => $loginUrl,
				'signupUrl' => $signupUrl,
			],
			$renderAs,
		);
	}

	#[PublicPage]
	#[NoCSRFRequired]
	#[AnonRateLimit(limit: 120, period: 60)]
	public function logo(): Response {
		$filename = $this->appConfig->getValueString(Application::APP_ID, 'logo_filename', '');
		if ($filename === '') {
			return new NotFoundResponse();
		}

		try {
			$folder = $this->appData->getFolder('branding');
			$file = $folder->getFile($filename);
		} catch (NotFoundException) {
			return new NotFoundResponse();
		}

		$mime = $this->appConfig->getValueString(Application::APP_ID, 'logo_mime', 'image/png');
		return new FileDisplayResponse($file, 200, ['Content-Type' => $mime]);
	}

	private function coreLogin(): Response {
		/** @var LoginController $loginController */
		$loginController = $this->serverContainer->get(LoginController::class);
		return $loginController->showLoginForm(
			$this->request->getParam('user'),
			$this->request->getParam('redirect_url')
		);
	}

	private function getConfig(): array {
		$defaults = ConfigDefaults::defaults();

		$primaryColor = $this->sanitizeColor(
			$this->appConfig->getValueString(Application::APP_ID, 'primary_color', $defaults['primaryColor']),
			$defaults['primaryColor']
		);
		$accentColor = $this->sanitizeColor(
			$this->appConfig->getValueString(Application::APP_ID, 'accent_color', $defaults['accentColor']),
			$defaults['accentColor']
		);

		return [
			'enabled' => $this->appConfig->getValueBool(Application::APP_ID, 'enabled', true),
			'heroTitle' => $this->appConfig->getValueString(Application::APP_ID, 'hero_title', $defaults['heroTitle']),
			'heroSubtitle' => $this->appConfig->getValueString(Application::APP_ID, 'hero_subtitle', $defaults['heroSubtitle']),
			'ctaPrimary' => $this->appConfig->getValueString(Application::APP_ID, 'cta_primary', $defaults['ctaPrimary']),
			'ctaSecondary' => $this->appConfig->getValueString(Application::APP_ID, 'cta_secondary', $defaults['ctaSecondary']),
			'primaryColor' => $primaryColor,
			'accentColor' => $accentColor,
			'backgroundTop' => $this->sanitizeColor(
				$this->appConfig->getValueString(Application::APP_ID, 'background_top', $defaults['backgroundTop']),
				$defaults['backgroundTop']
			),
			'backgroundBottom' => $this->sanitizeColor(
				$this->appConfig->getValueString(Application::APP_ID, 'background_bottom', $defaults['backgroundBottom']),
				$defaults['backgroundBottom']
			),
			'companyName' => $this->appConfig->getValueString(Application::APP_ID, 'company_name', $defaults['companyName']),
			'companyInfo' => $this->appConfig->getValueString(Application::APP_ID, 'company_info', $defaults['companyInfo']),
			'contactEmail' => $this->appConfig->getValueString(Application::APP_ID, 'contact_email', $defaults['contactEmail']),
			'termsUrl' => $this->appConfig->getValueString(Application::APP_ID, 'terms_url', $defaults['termsUrl']),
			'privacyUrl' => $this->appConfig->getValueString(Application::APP_ID, 'privacy_url', $defaults['privacyUrl']),
			'docsUrl' => $this->appConfig->getValueString(Application::APP_ID, 'docs_url', $defaults['docsUrl']),
			'signupUrl' => $this->appConfig->getValueString(Application::APP_ID, 'signup_url', $defaults['signupUrl']),
			'logoFilename' => $this->appConfig->getValueString(Application::APP_ID, 'logo_filename', ''),
			'plans' => $this->appConfig->getValueArray(Application::APP_ID, 'plans', $defaults['plans']),
			'benefits' => $this->appConfig->getValueArray(Application::APP_ID, 'benefits', $defaults['benefits']),
		];
	}

	private function sanitizeColor(string $color, string $fallback): string {
		if (preg_match('/^#[0-9a-fA-F]{6}$/', $color) === 1) {
			return strtolower($color);
		}
		return $fallback;
	}

	private function getDefaultConfig(): array {
		return ConfigDefaults::defaults();
	}
}
