<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Controller;

use Exception;
use InvalidArgumentException;
use OCA\ThesisTemplater\Service\FileProcessingService;
use OCA\ThesisTemplater\Service\StudentService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

class StudentFileController extends Controller {
	public function __construct(string $appName, IRequest $request, private FileProcessingService $fileProcessingService, private StudentService $studentService) {
		parent::__construct($appName, $request);
	}

	/**
	 * Parses a CSV file and returns either an error or a list of students as a DataResponse
	 */
	public function parseCsv(): DataResponse {
		$students = $this->getStudentsFromCSVFile('file');
		if ($students instanceof DataResponse) {
			return $students;
		}

		$studentsResponse = [];

		foreach ($students as $student) {
			$studentsResponse[] = [
				'student' => $student->ToArray(),
				'valid' => $student->IsValid()
			];
		}

		return new DataResponse([
			'status' => 'success',
			'data' => $studentsResponse
		]);
	}

	/**
	 * Adds students from a JSON file
	 * @param bool $share Whether the students should be shared
	 * @param string $expiry The expiry date of the share
	 */
	public function addFromJson(bool $share, string $expiry){
		$date = new \DateTime($expiry);
		$rawFile = $this->getFile('file');
		if($rawFile instanceof DataResponse){
			return $rawFile;
		}
		try {
			$json = json_decode(file_get_contents($rawFile['tmp_name']), true, 512, JSON_THROW_ON_ERROR);
		}
		catch(Exception){
			return new DataResponse([
				'status' => 'error',
				'message' => 'Invalid JSON',
			], Http::STATUS_BAD_REQUEST);
		}
		$students = $this->fileProcessingService->getStudentsFromJSON($json);
		if($students instanceof DataResponse){
			return $students;
		}
		try {
			$this->studentService->createStudentDirectories($students, $share, $date);
		}
		catch(Exception $ex){
			return new DataResponse([
				'status' => 'error',
				'message' => $ex->getMessage(),
			], Http::STATUS_BAD_REQUEST);
		}
	}

	private function getFile(string $fileName): DataResponse|array {
		$file = $this->request->getUploadedFile($fileName);
		if($file === null) {
			return new DataResponse([
				'status' => 'error',
				'message' => 'No file uploaded',
			], Http::STATUS_BAD_REQUEST);
		}
		return $file;
	}

	private function getFileHandle(string $fileName): mixed {
		$file = $this->getFile($fileName);
		if($file instanceof DataResponse) {
			return $file;
		}
		return fopen($file['tmp_name'], 'r');
	}

	private function getStudentsFromCSVFile(string $fileName): DataResponse|array {
		$handle = $this->getFileHandle($fileName);
		try{
			return $this->fileProcessingService->parseCsv($handle);
		}
		catch(InvalidArgumentException $ex){
			return new DataResponse([
				'status' => 'error',
				'message' => $ex->getMessage(),
			], Http::STATUS_BAD_REQUEST);
		}
	}
}
