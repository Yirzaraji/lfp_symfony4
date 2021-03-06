<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"email"},
 * message="Email already taken"
 * )
 * @
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Required field")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Invalid email format")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Insert your picture Url here")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * EqualTo compare le mot de passe entrée dans le register form hash est en faite le password non hashé il y a pas de connection bdd ici
     *
     * @Assert\EqualTo(propertyPath="hash", message="You passwords do not match")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="We ask you 10 characters minimum")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="We ask you 100 characters minimum")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="author")
     */
    private $ads;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    public function getFullname()
    {

        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * Permet d'initialiser le SLug de slugify
     *
     * J'ai rien compris a ce truc la go voir la doc ORM
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . '' . $this->lastName);

        }

    }

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        //la variable $roles est defini seulement a la connexion de l'utilisateur
        //toArray est une feature de doctrine array collection permettant plus d'option sur les tableaux
        //userRoles retourne un tableau a partir de la db listing des roles, Voir construct
        $roles = $this->userRoles->toArray();
        dump($roles);

        //la fonction map() est arbitraire elele permet de retourner une string
        $roles = $this->userRoles->map(function ($role) {
            return $role->getTitle();
        })->toArray();

        dump($roles);

        $roles[] = 'ROLE_USER';

        return $roles;

        /*  $roles = $this->$userRoles->map(function ($role) {
    return $role->getTitle();
    })->toArray();

    $roles[] = 'ROLE_USER';
    dump($roles);
    return $roles;
     */

    }

    public function getPassword()
    {
        return $this->hash;

    }

    public function getSalt()
    {}

    public function getUsername()
    {
        return $this->email;

    }

    public function eraseCredentials()
    {}

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }
}
