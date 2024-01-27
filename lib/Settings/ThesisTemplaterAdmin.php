<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Settings;
use OCA\ThesisTemplater\Service\AppConfigService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;
use Psr\Container\ContainerInterface;

class ThesisTemplaterAdmin implements ISettings {

    public function __construct(private string $appName, private ContainerInterface $container, private AppConfigService $appConfig) {
    }

    public function getForm() {
        return new TemplateResponse($this->appName, "settings", $this->appConfig->GetAllConfigValues());
    }

    public function getSection(){
        return $this->appName . "-admin";
    }

    public function getPriority(){
        return 50;
    }
}