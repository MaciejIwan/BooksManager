<?php

namespace App\Entity;

use App\Entity\Trait\HasTimestamps;
use App\Enum\BookReviewStars;
use App\Repository\BookReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookReviewRepository::class)]
class BookReview
{
    use HasTimestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(enumType: BookReviewStars::class)]
    private ?BookReviewStars $rating = null;

    #[Assert\NotBlank(message: 'Description is required')]
    #[ORM\Column(length: 500)]
    private ?string $description = null;

    #[Assert\NotBlank(message: 'Author is required')]
    #[ORM\Column(length: 500)]
    private ?string $author = null;


    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return BookReview
     */
    public function setEmail(?string $email): BookReview
    {
        $this->email = $email;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
