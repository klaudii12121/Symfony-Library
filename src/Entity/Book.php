<?php
/**
 * book entity.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class book.
 *
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(name="books")
 */
class Book
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
    private int $id;

    /**
     * Book name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private string $bookName;

    /**
     * Book description.
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=1000, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max="1000")
     */
    private ?string $bookDesc;

    /**
     * Release year.
     *
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     */
    private ?int $releaseYear;

    /**
     * Category.
     *
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private Category $category;

    /**
     * Author.
     *
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private Author $author;

    /**
     * Publisher.
     *
     * @var Publisher
     *
     * @ORM\ManyToOne(targetEntity=Publisher::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private Publisher $publisher;

    /**
     * Tags.
     *
     * @var Collection|null
     *
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="books", orphanRemoval=true)
     * @ORM\JoinTable(name="books_tags")
     */
    private ?Collection $tags;

    /**
     * Borrowings.
     *
     * @var Collection|null
     *
     * @ORM\OneToMany(targetEntity=Borrowing::class, mappedBy="book")
     */
    private ?Collection $borrowings;

    /**
     * Amount.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->borrowings = new ArrayCollection();
    }

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
     * Getter for book name.
     *
     * @return string|null BookName
     */
    public function getBookName(): ?string
    {
        return $this->bookName;
    }

    /**
     * Setter for book name.
     *
     * @param string $bookName BookName
     */
    public function setBookName(string $bookName): void
    {
        $this->bookName = $bookName;
    }

    /**
     * Getter for book description.
     *
     * @return string|null BookDesc
     */
    public function getBookDesc(): ?string
    {
        return $this->bookDesc;
    }

    /**
     * Setter for book description.
     *
     * @param string|null $bookDesc BookDesc
     */
    public function setBookDesc(?string $bookDesc): void
    {
        $this->bookDesc = $bookDesc;
    }

    /**
     * Getter for Release year.
     *
     * @return int|null ReleaseYear
     */
    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    /**
     * Setter for Release year.
     *
     * @param int|null $releaseYear ReleaseYear
     */
    public function setReleaseYear(?int $releaseYear): void
    {
        $this->releaseYear = $releaseYear;
    }

    /**
     * Getter for Category.
     *
     * @return Category Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category $category Category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for Author.
     *
     * @return Author Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     *
     * @param Author $author Author
     */
    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    /**
     * Getter for Publisher.
     *
     * @return Publisher Publisher
     */
    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }

    /**
     * Setter for Publisher.
     *
     * @param Publisher $publisher Publisher
     */
    public function setPublisher(Publisher $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * Getter for the tags.
     *
     * @return Collection|Tag[]
     */
    public function getTags(): ?Collection
    {
        return $this->tags;
    }

    /**
     * Add for tag.
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Remove for tag.
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Get borrowings.
     *
     * @return Collection|Borrowing[]
     */
    public function getBorrowings(): ?Collection
    {
        return $this->borrowings;
    }

    /**
     * Add borrowings.
     *
     * @param Borrowing $borrowing
     *
     * @return $this
     */
    public function addBorrowing(Borrowing $borrowing): self
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings[] = $borrowing;
            $borrowing->setBook($this);
        }

        return $this;
    }

    /**
     * Remove borrowing.
     *
     * @param Borrowing $borrowing
     *
     * @return $this
     */
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

    /**
     * Get books amount.
     *
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * Set books amount.
     *
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
