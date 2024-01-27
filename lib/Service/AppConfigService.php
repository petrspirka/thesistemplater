<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Service;

use OCP\IConfig;
use OCP\ILogger;

/**
 * Service used for managing the configuration of the plugin
 */
class AppConfigService {
    private $emailFieldKey =  "emailField";
    private $firstNameFieldKey = "firstNameField";
    private $lastNameFieldKey = "lastNameField";
    private $typeFieldKey = "typeField";
    private $studentDirectoryKey = "studentDirectory";

    private $defaultEmailField = "email";
    private $defaultFirstNameField = "jmeno";
    private $defaultLastNameField = "prijmeni";
    private $defaultStudentDirectory = "/Studenti";
    private $defaultTypeField = "typSp";

    public function __construct(private string $appName, private IConfig $config, private ILogger $logger){
    }

    public function GetEmailField(): string{
        return $this->config->getAppValue($this->appName, $this->emailFieldKey, $this->defaultEmailField);
    }

    public function GetEmailFieldKey(): string{
        return $this->emailFieldKey;
    }

    public function GetFirstNameField(): string{
        return $this->config->getAppValue($this->appName, $this->firstNameFieldKey, $this->defaultFirstNameField);
    }

    public function GetFirstNameFieldKey(): string{
        return $this->firstNameFieldKey;
    }

    public function GetLastNameField(): string{
        return $this->config->getAppValue($this->appName, $this->lastNameFieldKey, $this->defaultLastNameField);
    }
    
    public function GetLastNameFieldKey(): string {
        return $this->lastNameFieldKey;
    }

    public function GetTypeField(): string{
        return $this->config->getAppValue($this->appName, $this->typeFieldKey, $this->defaultTypeField);
    }
    
    public function GetTypeFieldKey(): string{
        return $this->typeFieldKey;
    }

    public function GetStudentDirectory(): string{
        return $this->config->getAppValue($this->appName, $this->studentDirectoryKey, $this->defaultStudentDirectory);
    }

    public function GetStudentDirectoryKey(): string {
        return $this->studentDirectoryKey;
    }

    public function SetTypeField(string $specializationFieldValue){
        $this->SetConfigValue($this->typeFieldKey, $specializationFieldValue);
    }

    public function SetEmailField(string $emailFieldValue){
        $this->SetConfigValue($this->emailFieldKey, $emailFieldValue);
    }

    public function SetFirstNameField(string $firstNameFieldValue){
        $this->SetConfigValue($this->firstNameFieldKey, $firstNameFieldValue);
    }

    public function SetLastNameField(string $lastNameFieldValue){
        $this->SetConfigValue($this->lastNameFieldKey, $lastNameFieldValue);
    }

    public function SetStudentDirectory(string $studentDirectory){
        $this->SetConfigValue($this->studentDirectoryKey, $studentDirectory);
    }

    public function GetAllConfigValues(){
        return [
            $this->typeFieldKey => $this->GetTypeField(),
            $this->emailFieldKey => $this->GetEmailField(),
            $this->firstNameFieldKey => $this->GetFirstNameField(),
            $this->lastNameFieldKey => $this->GetLastNameField(),
            $this->studentDirectoryKey => $this->GetStudentDirectory()
        ];
    }

    private function SetConfigValue(string $optionKey, string $optionValue){
        $trimmedOptionValue = trim($optionValue);
        $this->config->setAppValue($this->appName, $optionKey, $trimmedOptionValue);
        $this->LogChange($optionKey, $trimmedOptionValue);
    }

    private function LogChange(string $name, string $value){
        $this->logger->info("Changing \"" . $name . "\" to \"" . $value . "\"");
    }
}
