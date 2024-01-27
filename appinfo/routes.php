<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\ThesisTemplater\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'student_file#parseCsv', 'url' => '/api/0.1/student_file/parse', 'verb' => 'POST'],
		['name' => 'student_file#addFromJson', 'url' => '/api/0.1/student_file/add', 'verb' => 'POST'],
		['name' => 'settings#getConfiguration', 'url' => '/settings', 'verb' => 'GET'],
		['name' => 'settings#setConfiguration', 'url' => '/settings', 'verb' => 'POST']
	]
];
