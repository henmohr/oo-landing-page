<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\LibresignLanding\Listener;

use OCA\LibresignLanding\AppInfo\Application;
use OCP\AppFramework\Http\Events\BeforeLoginTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IAppConfig;
use OCP\IURLGenerator;
use OCP\Util;

/** @template-implements IEventListener<BeforeLoginTemplateRenderedEvent> */
class BeforeLoginTemplateRenderedListener implements IEventListener {
	public function __construct(
		private IAppConfig $appConfig,
		private IURLGenerator $urlGenerator,
	) {
	}

	public function handle(Event $event): void {
		if (!($event instanceof BeforeLoginTemplateRenderedEvent)) {
			return;
		}

		$enabled = $this->appConfig->getValueBool(Application::APP_ID, 'enabled', true);
		$fragmentUrl = $this->urlGenerator->linkToRoute('libresign_landing.page.landing', ['fragment' => '1']);

		Util::addHeader('meta', ['name' => 'libresign-landing-enabled', 'content' => $enabled ? '1' : '0']);
		Util::addHeader('meta', ['name' => 'libresign-landing-fragment', 'content' => $fragmentUrl]);

		Util::addStyle(Application::APP_ID, 'landing');
		Util::addScript(Application::APP_ID, 'landing');
		Util::addScript(Application::APP_ID, 'login-override', 'core');
	}
}
