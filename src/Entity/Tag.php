<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\Table(name="tags")
 */
class Tag
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
     * Tag name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="128",
     * )
     */
    private string $tagName;

    /**
     * Books.
     *
     * @var Collection|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="tags")
     */
    private Collection $books;

    /**
     * Tag constructor.
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
     * Getter for tagName.
     *
     * @return string|null TagName
     */
    public function getTagName(): ?string
    {
        return $this->tagName;
    }
    /**
     * Setter for tagName.
     *
     * @param string $tagName TagName
     */
    public function setTagName(string $tagName): void
    {
        $this->tagName = $tagName;
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
            $book->addTag($this);
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
            $book->removeTag($this);
        }

        return $this;
    }
}
