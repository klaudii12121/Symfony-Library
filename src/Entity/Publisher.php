<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublisherRepository::class)
 */
class Publisher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $publisher_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublisherName(): ?string
    {
        return $this->publisher_name;
    }

    public function setPublisherName(string $publisher_name): self
    {
        $this->publisher_name = $publisher_name;

        return $this;
    }
}
