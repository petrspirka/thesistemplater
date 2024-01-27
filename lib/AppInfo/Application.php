<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
	public const APP_ID = 'thesistemplater';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}
}
