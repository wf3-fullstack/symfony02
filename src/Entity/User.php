<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

// A RAJOUTER POUR POUVOIR UTILISER LES CONTRAINTES SUR MON ENTITE
// https://symfony.com/doc/current/reference/constraints/Email.html
use Symfony\Component\Validator\Constraints as Assert;

// DON'T forget the following use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *      fields="username", 
 *      message="bad username"
 * )
 * @UniqueEntity(
 *      fields="email", 
 *      message="DESOLE CHANGE D'EMAIL STP..."
 * )
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "ARRETE DE HACKER MON EMAIL '{{ value }}'"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     */
    private $roles = "";

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="user", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Annonce", mappedBy="users")
     */
    private $likeannonces;

    /**
     * @ORM\Column(type="string", length=160)
     */
    private $cleActivation;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->likeannonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles);
        // guarantee every user at least has ROLE_USER
        // $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = json_encode($roles);
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setUser($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getUser() === $this) {
                $annonce->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getLikeannonces(): Collection
    {
        return $this->likeannonces;
    }

    public function addLikeannonce(Annonce $likeannonce): self
    {
        if (!$this->likeannonces->contains($likeannonce)) {
            $this->likeannonces[] = $likeannonce;
            $likeannonce->addUser($this);
        }

        return $this;
    }

    public function removeLikeannonce(Annonce $likeannonce): self
    {
        if ($this->likeannonces->contains($likeannonce)) {
            $this->likeannonces->removeElement($likeannonce);
            $likeannonce->removeUser($this);
        }

        return $this;
    }

    public function getCleActivation(): ?string
    {
        return $this->cleActivation;
    }

    public function setCleActivation(string $cleActivation): self
    {
        $this->cleActivation = $cleActivation;

        return $this;
    }
}
