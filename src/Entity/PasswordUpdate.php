<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{
    /**
     * Undocumented variable
     *
     * @Assert\Length(min=8, minMessage="8 caracter minimum..")
     */
    private $oldPassword;


    private $newPassword;

   /**
    * Comparaison en back fonctionne comme le preg match en php
    *
    * @Assert\EqualTo(propertyPath="newpassword", message="vous n'avez pas confirmé votre nouveau mot de passe")
    */
    private $confirmPassword;


    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
