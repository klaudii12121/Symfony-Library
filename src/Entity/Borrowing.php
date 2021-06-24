<?php
/**
 * Borrowing entity.
 */

namespace App\Entity;

use App\Repository\BorrowingRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BorrowingRepository::class)
 * @ORM\Table(name="borrowings")
 */
class Borrowing
{
    /**
     * Primary key.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * Borrowing date.
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $borrowDate;

    /**
     * Return date.
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $returnDate;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $user;

    /**
     * Book.
     *
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="borrowings")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Book $book;

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
    public function getBorrowDate(): ?DateTimeInterface
    {
        return $this->borrowDate;
    }

    /**
     * Setter for borrow date.
     *
     * @param DateTimeInterface|null $borrowDate
     */
    public function setBorrowDate(DateTimeInterface $borrowDate): void
    {
        $this->borrowDate = $borrowDate;
    }

    /**
     * Getter for return date.
     *
     * @return DateTimeInterface|null ReturnDate
     */
    public function getReturnDate(): ?DateTimeInterface
    {
        return $this->returnDate;
    }

    /**
     * Setter for return date.
     */
    public function setReturnDate(?DateTimeInterface $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    /**
     * Getter for user.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * Getter for book.
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * Setter for book.
     */
    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
