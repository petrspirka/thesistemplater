<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Controller;
use OCA\ThesisTemplater\Service\AppConfigService;
use OCP\AppFramework\Controller;
use OCP\IRequest;

class SettingsController extends Controller {
    public function __construct(string $appName, IRequest $request, private AppConfigService $appConfig) {
        parent::__construct($appName, $request);
    }

    /**
     * @AdminRequired
     */
    public function setConfiguration(array $data){
        foreach($data as $key => $value){
            switch($key){
                case $this->appConfig->GetEmailFieldKey():
                    $this->appConfig->SetEmailField($value);
                    break;
                case $this->appConfig->GetFirstNameFieldKey():
                    $this->appConfig->SetFirstNameField($value);
                    break;
                case $this->appConfig->GetLastNameFieldKey():
                    $this->appConfig->SetLastNameField($value);
                    break;
                case $this->appConfig->GetTypeFieldKey():
                    $this->appConfig->SetTypeField($value);
                    break;
                case $this->appConfig->GetStudentDirectoryKey():
                    $this->appConfig->SetStudentDirectory($value);
                    break;
            }
        }
    }

    /**
     * @AdminRequired
     */
    public function getConfiguration(){
        return $this->appConfig->GetAllConfigValues();
    }
}
