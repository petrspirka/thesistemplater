<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ThesisTemplater\Model;

/**
 * Represents a single student read from the IS/STAG CSV file
 * 
 * Only contains relevant information
 */
class StudentModel {
    /**
     * Creates a new instance of this model
     * @param string $firstName First name of the student
     * @param string $lastName Last name of the student
     * @param string $email Email of this student
     * @param string $type Type of this student (Bachelor/Master/Doctor)
     */
    public function __construct(private string $firstName, private string $lastName, private string $email, private string $type) {
    }

    /**
     * Returns the student's first name
     */
    public function GetFirstName(){
        return $this->firstName;
    }

    /**
     * Returns the student's last name
     */
    public function GetLastName(){
        return $this->lastName;
    }

    /**
     * Returns the student's email
     */
    public function GetEmail(){
        return $this->email;
    }

    /**
     * Returns the student's specialization
     */
    public function GetType(){
        return $this->type;
    }

    /** 
     * Returns the path to the student's directory
     */
    public function GetPath(){
        return $this->lastName . " " . $this->firstName;
    }

    /**
     * Returns whether or not this student instance is valid
     * 
     * This means that all the fields have values in them and the email is a valid email
     */
    public function IsValid(){
        return !(
            empty($this->firstName) ||
            empty($this->lastName) ||
            empty($this->type) ||
            empty($this->email) ||
            !filter_var($this->email, FILTER_VALIDATE_EMAIL));
    }

    public static function FromArray(array $data): StudentModel {
        return new StudentModel(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['type']
        );
    }

    public function ToArray(): array {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'type' => $this->type
        ];
    }
}
