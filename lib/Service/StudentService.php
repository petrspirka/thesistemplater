<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Service;

use OCP\Constants;
use OCP\Files\Folder;
use OCP\Files\InvalidPathException;
use OCP\Files\IRootFolder;
use OCP\IL10N;
use OCP\IUserSession;
use OCP\Share\IManager;
use OCP\Share\IShare;
use Sabre\DAV\Exception\NotAuthenticated;

class StudentService {
	public function __construct(private AppConfigService $configService, private IUserSession $userSession, private IRootFolder $rootFolder, private IManager $manager, private IL10N $l10n) {}

    /**
     * Creates student directories based on an array of students, whether they should be shared and the expiration date
     */
	public function createStudentDirectories(array $students, bool $shouldShare, \DateTime $dateTime) {
		$studentsDirectory = $this->configService->GetStudentDirectory();
        if($studentsDirectory === null || trim($studentsDirectory) === ""){
            throw new InvalidPathException("Student directory is not set");
        }
        
        $user = $this->userSession->getUser();
        if ($user === null) {
            //TODO: potentially make own exception
            throw new NotAuthenticated("User is not authenticated");
        }

        $userFolder = $this->rootFolder->getUserFolder($user->getUID());
        $formattedDate = $this->getExpireDate($dateTime);

        if(!$userFolder->nodeExists($studentsDirectory)){
            try {
                $folder = $userFolder->newFolder($studentsDirectory);
            }
            catch(InvalidPathException $ex){
                throw new InvalidPathException("Could not create student directory");
            }
        }
        else {
            $folder = $userFolder->get($studentsDirectory);
        }

        foreach ($students as $student) {
            $type = $student->GetType();
            $studentPath = $student->GetPath();
            
            $typeFolder = $this->createOrGet($folder, $type);
            $dateFolder = $this->createOrGet($typeFolder, $formattedDate);
            $studentFolder = $this->createOrGet($dateFolder, $studentPath);

            if($shouldShare){
                $shares = $this->manager->getSharesBy($user->getUID(), IShare::TYPE_EMAIL, $studentFolder, false, -1, 0);
                $exists = false;
                foreach ($shares as $key => $share) {
                    if($share->getSharedWith() === $student->GetEmail()){
                        $share->setExpirationDate($dateTime);
                        $this->manager->updateShare($share);
                        $exists = true;
                        break;
                    }
                }

                if(!$exists){
                    $share = $this->manager->newShare()
                        ->setNode($studentFolder)
                        ->setShareType(IShare::TYPE_EMAIL)
                        ->setSharedBy($user->getDisplayName())
                        ->setSharedWith($student->GetEmail())
                        ->setPermissions(Constants::PERMISSION_CREATE | Constants::PERMISSION_READ | Constants::PERMISSION_UPDATE | Constants::PERMISSION_DELETE)
                        ->setExpirationDate($dateTime);
                    $this->manager->createShare($share);
                }
            }
        }
	}

    private function createOrGet(Folder $folder, string $name) {
        if(!$folder->nodeExists($name)){
            return $folder->newFolder($name);
        }
        else{
            return $folder->get($name);
        }
    }

    private function getExpireDate(\DateTime $dateTime) {
        return $dateTime->format("m-Y");
    }
}
