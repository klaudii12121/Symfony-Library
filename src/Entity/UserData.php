<?php
/**
 * Userdata entity.
 */

namespace App\Entity;

use App\Repository\UserDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserDataRepository::class)
 * @ORM\Table(name="usersData")
 */
class UserData
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Nick.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="6",
     *     max="10",
     * )
     * @Assert\NotBlank
     */
    private $nick;

    /**
     * First name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $firstName;

    /**
     * Last name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $lastName;

    /**
     * User icon.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userIcon;

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for nick.
     *
     * @return string|null Result
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Setter for nick.
     *
     * @param string $nick Nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * Getter for first name.
     *
     * @return string|null Result
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Setter for first name.
     *
     * @param string|null $firstName FirstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Getter for last name.
     *
     * @return string|null Result
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Setter for last name.
     *
     * @param string|null $lastName LastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Getter for user icon.
     *
     * @return string|null Result
     */
    public function getUserIcon(): ?string
    {
        return $this->userIcon;
    }

    /**
     * Setter for user icon.
     *
     * @param string|null $userIcon UserIcon
     */
    public function setUserIcon(?string $userIcon): void
    {
        $this->userIcon = $userIcon;
    }
}
