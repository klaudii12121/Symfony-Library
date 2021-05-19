<?php

namespace App\Entity;

use App\Repository\BorrowingRepository;
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
    private $id;

    /**
     * Borrow Date.
     *
     * @ORM\Column(type="date")
     */
    private $borrowDate;

    /**
     * Return date.
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $returnDate;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrowings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Book.
     *
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="borrowings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(\DateTimeInterface $borrowDate): self
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
