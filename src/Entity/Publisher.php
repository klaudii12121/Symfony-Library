<?php
/**
 * Publisher entity.
 */

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Publisher.
 *
 * @ORM\Entity(repositoryClass=PublisherRepository::class)
 * @ORM\Table(name="publishers")
 */
class Publisher
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
     * Publisher name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $publisherName;

    /**
     * Books.
     *
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="publisher")
     */
    private $books;

    /**
     * Publisher constructor.
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
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
     * Getter for Publisher name.
     *
     * @return string|null PublisherName
     */
    public function getPublisherName(): ?string
    {
        return $this->publisherName;
    }

    /**
     * Setter for Publisher name.
     *
     * @param string $publisherName PublisherName
     */
    public function setPublisherName(string $publisherName): void
    {
        $this->publisherName = $publisherName;
    }

    /**
     * Getter for the books.
     *
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * Add for book.
     *
     * @param Book $book
     *
     * @return $this
     */
    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setPublisher($this);
        }

        return $this;
    }

    /**
     * Remove for book.
     *
     * @param Book $book
     *
     * @return $this
     */
    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getPublisher() === $this) {
                $book->setPublisher(null);
            }
        }

        return $this;
    }
}
