<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Service;

use Exception;

use InvalidArgumentException;
use OCA\ThesisTemplater\Model\StudentModel;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IL10N;

/**
 * Service for processing files
 */
class FileProcessingService {
	public function __construct(private AppConfigService $configService, private IL10N $l10n) {}

	/**
	 * Parses a CSV file from IS/STAG
	 */
	public function parseCsv($handle){
		if(!is_resource($handle)){
			throw new InvalidArgumentException("Handle is not a resource");
		}
		$header = fgetcsv($handle, null, ';');
		if(!$header){
			throw new InvalidArgumentException("Could not parse the specified file as csv");
		}

		//Find the index of the fields
		$typeKey = array_search($this->configService->GetTypeField(), $header);
		$firstNameKey = array_search($this->configService->GetFirstNameField(), $header);
		$lastNameKey = array_search($this->configService->GetLastNameField(), $header);
		$emailKey = array_search($this->configService->GetEmailField(), $header);

		if(!$typeKey || !$firstNameKey || !$lastNameKey || !$emailKey){
			throw new InvalidArgumentException("Could not find the csv fields set in the app configuration");
		}

		$parsedData = [];

		while($data = fgetcsv($handle, null, ';')){
			$parsedData[] = new StudentModel(
				iconv("Windows-1250", "utf8", $data[$firstNameKey]),
				iconv("Windows-1250", "utf8", $data[$lastNameKey]),
				iconv("Windows-1250", "utf8", $data[$emailKey]),
				$this->ConvertFromEncodedType(iconv("Windows-1250", "utf8", $data[$typeKey]))
			);
		}
		return $parsedData;
	}
	
	/**
	 * Gets student from a JSON file. It should be first converted using json_decode with associative array mode. Uses the StudentModel::FromArray() method to decode the students.
	 */
	public function getStudentsFromJSON(array $students): DataResponse|array {
		$studentsArray = [];
		foreach($students as $student){
			try {
				$studentsArray[] = StudentModel::FromArray($student);
			}
			catch(Exception){
				return new DataResponse([
					'status' => 'error',
					'message' => 'Could not parse one or more records in the specified file',
				], Http::STATUS_BAD_REQUEST);
			}
		}
		return $studentsArray;
	}

    private function ConvertFromEncodedType(string $specializationKey){
        switch($specializationKey){
            case "B":
                return $this->l10n->t("Bc.");
            case "M":
                return $this->l10n->t("Mgr.");
            case "D":
                return $this->l10n->t("Ph.D.");
            default:
                return $this->l10n->t("Unknown");
        }
    }
}
