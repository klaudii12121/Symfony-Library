<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(name="books")
 */
class Book
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
     * Book name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $bookName;

    /**
     * Book description.
     *
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $bookDesc;

    /**
     * Release year.
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $releaseYear;

    /**
     * Book icon.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bookIcon;

    /**
     * Category.
     *
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * Author.
     *
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * Publisher.
     *
     * @ORM\ManyToOne(targetEntity=Publisher::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    /**
     * Book tags.
     *
     * @ORM\OneToMany(targetEntity=BookTag::class, mappedBy="book")
     */
    private $bookTags;

    /**
     * Borrowings.
     *
     * @ORM\OneToMany(targetEntity=Borrowing::class, mappedBy="book")
     */
    private $borrowings;

    public function __construct()
    {
        $this->bookTags = new ArrayCollection();
        $this->borrowings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookName(): ?string
    {
        return $this->bookName;
    }

    public function setBookName(string $bookName): self
    {
        $this->bookName = $bookName;

        return $this;
    }

    public function getBookDesc(): ?string
    {
        return $this->bookDesc;
    }

    public function setBookDesc(?string $bookDesc): self
    {
        $this->bookDesc = $bookDesc;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(?int $releaseYear): self
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getBookIcon(): ?string
    {
        return $this->bookIcon;
    }

    public function setBookIcon(?string $bookIcon): self
    {
        $this->bookIcon = $bookIcon;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return Collection|BookTag[]
     */
    public function getBookTags(): Collection
    {
        return $this->bookTags;
    }

    public function addBookTag(BookTag $bookTag): self
    {
        if (!$this->bookTags->contains($bookTag)) {
            $this->bookTags[] = $bookTag;
            $bookTag->addBook($this);
        }

        return $this;
    }

    public function removeBookTag(BookTag $bookTag): self
    {
        if ($this->bookTags->removeElement($bookTag)) {
            $bookTag->removeBook($this);
        }

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
            $borrowing->setBook($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->removeElement($borrowing)) {
            // set the owning side to null (unless already changed)
            if ($borrowing->getBook() === $this) {
                $borrowing->setBook(null);
            }
        }

        return $this;
    }
}
