<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Sections;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class ThesisTemplaterAdmin implements IIconSection {
    public function __construct(private string $appName, private IL10N $l10n, private IURLGenerator $urlGenerator) {
    }

    public function getID(): string {
        return $this->appName . "-admin";
    }

    public function getIcon(): string {
        return $this->urlGenerator->imagePath("core", "actions/settings-dark.svg");
    }

    public function getName(): string {
        return $this->l10n->t("ThesisTemplater settings");
    }

    public function getPriority(): int {
        return 60;
    }
}