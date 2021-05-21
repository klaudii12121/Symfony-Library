<?php
/**
 * book entity.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private $id;

    /**
     * book name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $bookName;

    /**
     * book description.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $bookDesc;

    /**
     * Release year.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $releaseYear;

    /**
     * book icon.
     *
     * @var string
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
     * Tags.
     *
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="books")
     */
    private $tags;

    /**
     * book constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Getter for book icon.
     *
     * @return string|null BookIcon
     */
    public function getBookIcon(): ?string
    {
        return $this->bookIcon;
    }

    /**
     * Setter for book Icon.
     *
     * @param string|null $bookIcon BookIcon
     */
    public function setBookIcon(?string $bookIcon): void
    {
        $this->bookIcon = $bookIcon;
    }

    /**
     * Getter for Category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for Author.
     *
     * @return Author|null Author
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     *
     * @param Author|null $author Author
     */
    public function setAuthor(?Author $author): void
    {
        $this->author = $author;
    }

    /**
     * Getter for Publisher.
     *
     * @return Publisher|null Publisher
     */
    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    /**
     * Setter for Publisher.
     *
     * @param Publisher|null $publisher Publisher
     */
    public function setPublisher(?Publisher $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * Getter for the tags.
     *
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
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
}