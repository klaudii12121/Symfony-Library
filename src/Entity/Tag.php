<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\Table(name="tags")
 */
class Tag
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
     * Tag name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tagName;

    /**
     * Book Tags.
     *
     * @ORM\OneToMany(targetEntity=BookTag::class, mappedBy="tag")
     */
    private $bookTags;

    public function __construct()
    {
        $this->bookTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;

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
            $bookTag->setTag($this);
        }

        return $this;
    }

    public function removeBookTag(BookTag $bookTag): self
    {
        if ($this->bookTags->removeElement($bookTag)) {
            // set the owning side to null (unless already changed)
            if ($bookTag->getTag() === $this) {
                $bookTag->setTag(null);
            }
        }

        return $this;
    }
}
