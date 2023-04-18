<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserData
{
    /**
     * @Assert\NotBlank(message="Please enter a name")
     * @Assert\Length(min=2, max=50, minMessage="Your name must be at least {{ limit }} characters long", maxMessage="Your name cannot be longer than {{ limit }} characters")
     */
    private string $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100, minMessage="Your name must be at least {{ limit }} characters long", maxMessage="Your name cannot be longer than {{ limit }} characters")
     */
    private string $lastName;

    /**
     * @Assert\NotBlank(message="Please enter an email address")
     * @Assert\Email(message="The email '{{ value }}' is not a valid email.")
     */
    private string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=250, minMessage="Your password must be at least {{ limit }} characters long", maxMessage="Your password cannot be longer than {{ limit }} characters")
     * @Assert\EqualTo(propertyPath="repeatPassword", message="The password fields must be the same.")
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/", message="Your password must contain at least one lowercase letter, one uppercase letter, one number and one special character.")
     */
    private string $password;

    /**
     * @Assert\NotBlank(message="Please repeat your password")
     */
    private string $repeatPassword;


    public function setFirstName(string $firstName): RegisterUserData
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(string $lastName): RegisterUserData
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setEmail(string $email): RegisterUserData
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): RegisterUserData
    {
        $this->password = $password;
        return $this;
    }

    public function setRepeatPassword(string $repeatPassword): RegisterUserData
    {
        $this->repeatPassword = $repeatPassword;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRepeatPassword(): string
    {
        return $this->repeatPassword;
    }
}
