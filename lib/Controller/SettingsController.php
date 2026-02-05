<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\LibresignLanding\Controller;

use OCA\LibresignLanding\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\AdminRequired;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\Files\IAppData;
use OCP\Files\IMimeTypeDetector;
use OCP\Files\NotFoundException;
use OCP\IAppConfig;
use OCP\IRequest;

class SettingsController extends Controller {
	public function __construct(
		string $appName,
		IRequest $request,
		private IAppConfig $appConfig,
		private IAppData $appData,
		private IMimeTypeDetector $mimeTypeDetector,
	) {
		parent::__construct($appName, $request);
	}

	#[AdminRequired]
	public function save(): RedirectResponse {
		$enabled = $this->request->getParam('enabled') === '1';
		$this->appConfig->setValueBool(Application::APP_ID, 'enabled', $enabled);

		$this->appConfig->setValueString(Application::APP_ID, 'hero_title', $this->limitString($this->request->getParam('hero_title'), 140));
		$this->appConfig->setValueString(Application::APP_ID, 'hero_subtitle', $this->limitString($this->request->getParam('hero_subtitle'), 220));
		$this->appConfig->setValueString(Application::APP_ID, 'cta_primary', $this->limitString($this->request->getParam('cta_primary'), 40));
		$this->appConfig->setValueString(Application::APP_ID, 'cta_secondary', $this->limitString($this->request->getParam('cta_secondary'), 40));

		$this->appConfig->setValueString(Application::APP_ID, 'primary_color', $this->sanitizeColor($this->request->getParam('primary_color')));
		$this->appConfig->setValueString(Application::APP_ID, 'accent_color', $this->sanitizeColor($this->request->getParam('accent_color')));
		$this->appConfig->setValueString(Application::APP_ID, 'background_top', $this->sanitizeColor($this->request->getParam('background_top')));
		$this->appConfig->setValueString(Application::APP_ID, 'background_bottom', $this->sanitizeColor($this->request->getParam('background_bottom')));

		$this->appConfig->setValueString(Application::APP_ID, 'signup_url', $this->sanitizeUrl($this->request->getParam('signup_url')));
		$this->appConfig->setValueString(Application::APP_ID, 'company_name', $this->limitString($this->request->getParam('company_name'), 120));
		$this->appConfig->setValueString(Application::APP_ID, 'company_info', $this->limitString($this->request->getParam('company_info'), 220));
		$this->appConfig->setValueString(Application::APP_ID, 'contact_email', $this->limitString($this->request->getParam('contact_email'), 120));
		$this->appConfig->setValueString(Application::APP_ID, 'terms_url', $this->sanitizeUrl($this->request->getParam('terms_url')));
		$this->appConfig->setValueString(Application::APP_ID, 'privacy_url', $this->sanitizeUrl($this->request->getParam('privacy_url')));
		$this->appConfig->setValueString(Application::APP_ID, 'docs_url', $this->sanitizeUrl($this->request->getParam('docs_url')));

		$plans = [
			'basic' => [
				'name' => $this->limitString($this->request->getParam('plan_basic_name'), 40),
				'price' => $this->limitString($this->request->getParam('plan_basic_price'), 40),
				'badge' => '',
				'highlight' => false,
				'cta' => $this->limitString($this->request->getParam('plan_basic_cta'), 50),
				'features' => $this->parseLines($this->request->getParam('plan_basic_features')),
			],
			'professional' => [
				'name' => $this->limitString($this->request->getParam('plan_professional_name'), 40),
				'price' => $this->limitString($this->request->getParam('plan_professional_price'), 40),
				'badge' => $this->limitString($this->request->getParam('plan_professional_badge'), 40),
				'highlight' => true,
				'cta' => $this->limitString($this->request->getParam('plan_professional_cta'), 50),
				'features' => $this->parseLines($this->request->getParam('plan_professional_features')),
			],
			'enterprise' => [
				'name' => $this->limitString($this->request->getParam('plan_enterprise_name'), 40),
				'price' => $this->limitString($this->request->getParam('plan_enterprise_price'), 40),
				'badge' => '',
				'highlight' => false,
				'cta' => $this->limitString($this->request->getParam('plan_enterprise_cta'), 50),
				'features' => $this->parseLines($this->request->getParam('plan_enterprise_features')),
			],
		];

		$this->appConfig->setValueArray(Application::APP_ID, 'plans', $plans);

		$benefits = [];
		for ($i = 1; $i <= 4; $i++) {
			$title = $this->limitString($this->request->getParam('benefit_title_' . $i), 60);
			$description = $this->limitString($this->request->getParam('benefit_description_' . $i), 140);
			if ($title !== '' && $description !== '') {
				$benefits[] = [
					'title' => $title,
					'description' => $description,
				];
			}
		}
		if ($benefits !== []) {
			$this->appConfig->setValueArray(Application::APP_ID, 'benefits', $benefits);
		}

		if ($this->request->getParam('clear_logo') === '1') {
			$this->deleteLogo();
		}

		$this->handleLogoUpload();

		$referer = $this->request->getHeader('Referer');
		return new RedirectResponse($referer ?: '/index.php/settings/admin/server');
	}

	private function handleLogoUpload(): void {
		$logo = $this->request->getUploadedFile('logo');
		if (!is_array($logo) || ($logo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
			return;
		}

		$mime = $this->mimeTypeDetector->detectContent($logo['tmp_name']);
		$allowed = [
			'image/png' => 'png',
			'image/jpeg' => 'jpg',
			'image/webp' => 'webp',
		];
		if (!isset($allowed[$mime])) {
			return;
		}

		$filename = 'logo.' . $allowed[$mime];
		try {
			$folder = $this->appData->getFolder('branding');
		} catch (NotFoundException) {
			$folder = $this->appData->newFolder('branding');
		}

		try {
			$file = $folder->getFile($filename);
			$file->putContent(file_get_contents($logo['tmp_name']));
		} catch (NotFoundException) {
			$file = $folder->newFile($filename);
			$file->putContent(file_get_contents($logo['tmp_name']));
		}

		$this->appConfig->setValueString(Application::APP_ID, 'logo_filename', $filename);
		$this->appConfig->setValueString(Application::APP_ID, 'logo_mime', $mime);
	}

	private function deleteLogo(): void {
		$filename = $this->appConfig->getValueString(Application::APP_ID, 'logo_filename', '');
		if ($filename === '') {
			return;
		}

		try {
			$folder = $this->appData->getFolder('branding');
			$file = $folder->getFile($filename);
			$file->delete();
		} catch (NotFoundException) {
			// ignore
		}

		$this->appConfig->setValueString(Application::APP_ID, 'logo_filename', '');
		$this->appConfig->setValueString(Application::APP_ID, 'logo_mime', '');
	}

	private function sanitizeColor(?string $value): string {
		$value = trim((string)$value);
		if (preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1) {
			return strtolower($value);
		}
		return '';
	}

	private function sanitizeUrl(?string $value): string {
		$value = trim((string)$value);
		if ($value === '') {
			return '';
		}
		if (str_contains($value, '"') || str_contains($value, "'")) {
			return '';
		}
		if (str_starts_with($value, '/')) {
			return $value;
		}
		if (filter_var($value, FILTER_VALIDATE_URL)) {
			return $value;
		}
		return '';
	}

	private function parseLines(?string $value): array {
		$lines = preg_split('/\r\n|\n|\r/', (string)$value);
		$result = [];
		foreach ($lines as $line) {
			$line = trim($line);
			if ($line !== '') {
				$result[] = $this->limitString($line, 120);
			}
		}
		return $result;
	}

	private function limitString(?string $value, int $limit): string {
		$value = trim((string)$value);
		if ($value === '') {
			return '';
		}
		return mb_substr($value, 0, $limit);
	}
}
