<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * Primary key.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * User name.
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * Password.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * Role
     *
     * @ORM\Column(type="json")
     */
    private $role = [];

    /**
     * @ORM\OneToOne(targetEntity=UserData::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userData;

    /**
     * @ORM\OneToMany(targetEntity=Borrowing::class, mappedBy="user")
     */
    private $borrowings;


    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUserData(): ?UserData
    {
        return $this->userData;
    }

    public function setUserData(UserData $userData): self
    {
        // set the owning side of the relation if necessary
        if ($userData->getUser() !== $this) {
            $userData->setUser($this);
        }

        $this->userData = $userData;

        return $this;
    }

    /**
     * @return Collection|Borrowing[]
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    public function addBorrowing(Borrowing $borrowing): self
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings[] = $borrowing;
            $borrowing->setUser($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->removeElement($borrowing)) {
            // set the owning side to null (unless already changed)
            if ($borrowing->getUser() === $this) {
                $borrowing->setUser(null);
            }
        }

        return $this;
    }
}
