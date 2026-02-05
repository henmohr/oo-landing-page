<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\LibresignLanding\AppInfo;

use OCA\LibresignLanding\Listener\BeforeLoginTemplateRenderedListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\Events\BeforeLoginTemplateRenderedEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'libresign_landing';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(BeforeLoginTemplateRenderedEvent::class, BeforeLoginTemplateRenderedListener::class);
	}

	public function boot(IBootContext $context): void {
	}
}
