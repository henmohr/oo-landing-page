<?php

/**
 * SPDX-FileCopyrightText: 2026 LibreCode
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
return [
	'routes' => [
		['name' => 'page#landing', 'url' => '/landing', 'verb' => 'GET'],
		['name' => 'page#logo', 'url' => '/logo', 'verb' => 'GET'],
		['name' => 'settings#save', 'url' => '/settings', 'verb' => 'POST'],
	],
];
