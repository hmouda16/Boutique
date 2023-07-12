<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cette email est déja utilisé.', groups: ['register'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;




    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Le champ ne doit pas etre vide')]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.", groups: ['register'])]
    private $email;




    #[Assert\NotBlank(message: 'Le champ confirmation emailne doit pas etre vide', groups: ['register'])]
    #[Assert\EqualTo(propertyPath: 'email', message: 'Les deux email de passes ne sont pas identiques', groups: ['register'])]
    public $confirmEmail;




    #[ORM\Column(type: 'json')]
    private $roles = [];




    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Le champ ne doit pas etre vide', groups: ['register'])]
    private $password;



    #[Assert\EqualTo(propertyPath: 'password', message: 'Les deux mot de passes ne sont pas identiques', groups: ['register'])]
    public $confirmPassword;



    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champ ne doit pas etre vide', groups: ['register'])]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le message doit etre au moins {{ limit }} caractere', maxMessage: 'Le prenom ne doit pas dépasser {{ limit }} 10 caractère')]
    private $lastName;


    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champ ne doit pas etre vide', groups: ['register'])]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le message doit etre au moins {{ limit }} caractere', maxMessage: 'Le prenom ne doit pas dépasser {{ limit }} 10 caractère', groups: ['register'])]
    private $firstName;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $age;



    private $oldPassword;


    private $NewPassword;


    #[Assert\EqualTo(propertyPath: 'newPassword', message: 'Les deux mot de passes ne sont pas identiques')]
    private $confirmNewPassword;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le pseudo ne doit pas etre vide', groups: ['register'])]
    #[Assert\Length(min: 6, max: 20, minMessage: 'le pseudo doit pas dépasser  {{limit}} cararctéres', groups: ['register'])]
    private $pseudo;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Adress::class)]
    private Collection $adresses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column]
    private ?bool $active = null;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get the value of oldPassword
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set the value of oldPassword
     *
     * @return  self
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }




    /**
     * Get the value of NewPassword
     */
    public function getNewPassword()
    {
        return $this->NewPassword;
    }

    /**
     * Set the value of NewPassword
     *
     * @return  self
     */
    public function setNewPassword($NewPassword)
    {
        $this->NewPassword = $NewPassword;

        return $this;
    }

    /**
     * Get the value of confirmNewPassword
     */
    public function getConfirmNewPassword()
    {
        return $this->confirmNewPassword;
    }

    /**
     * Set the value of confirmNewPassword
     *
     * @return  self
     */
    public function setConfirmNewPassword($confirmNewPassword)
    {
        $this->confirmNewPassword = $confirmNewPassword;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Adress>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setUser($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getUser() === $this) {
                $adress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
