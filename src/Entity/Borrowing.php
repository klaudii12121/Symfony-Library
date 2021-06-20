<?php
/**
 * Borrowing entity.
 */

namespace App\Entity;

use App\Repository\BorrowingRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=BorrowingRepository::class)
 * @ORM\Table(name="borrowings")
 */
class Borrowing
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
     * Borrowing date.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="date")
     */
    private $borrowDate;

    /**
     * Return date.
     *
     * @var DateTimeInterface|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $returnDate;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * Book.
     *
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="borrowings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

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
     * Getter for borrow date.
     *
     * @return DateTimeInterface|null BorrowDate
     */
    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }
    /**
     * Setter for borrow date.
     *
     * @param DateTimeInterface|null $borrowDate
     */
    public function setBorrowDate(\DateTimeInterface $borrowDate): void
    {
        $this->borrowDate = $borrowDate;
    }

    /**
     * Getter for return date.
     *
     * @return DateTimeInterface|null ReturnDate
     */
    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    /**
     * Setter for return date.
     *
     * @param DateTimeInterface|null $returnDate
     */
    public function setReturnDate(?\DateTimeInterface $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    /**
     * Getter for user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * Getter for book.
     *
     * @return Book|null
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * Setter for book.
     *
     * @param Book|null $book
     */
    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
