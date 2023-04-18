<?php

namespace App\Entity;

use App\Entity\Trait\HasTimestamps;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    use HasTimestamps;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 200, minMessage: 'Title must be at least 1 character', maxMessage: 'Title must be less than 200 characters')]
    #[ORM\Column(length: 200)]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, minMessage: 'Description must be at least 1 character')]
    #[ORM\Column(type: 'text')]
    private ?string $description;

    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 13, exactMessage: 'ISBN must be have least 4 and max 13 characters')]
    #[ORM\Column(length: 13)]
    private ?string $ISBN ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
