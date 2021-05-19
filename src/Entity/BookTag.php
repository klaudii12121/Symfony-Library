<?php

namespace App\Entity;

use App\Repository\BookTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookTagRepository::class)
 * @ORM\Table(name="book_tags")
 */
class BookTag
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
     * Book.
     *
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="bookTags")
     */
    private $book;

    /**
     * Tag.
     *
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="bookTags")
     */
    private $tag;

    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->book->removeElement($book);

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
