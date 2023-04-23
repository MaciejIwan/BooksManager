<?php

namespace App\Entity;

use App\Entity\Trait\HasTimestamps;
use App\Enum\BookReviewStars;
use App\Repository\BookReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookReviewRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;
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
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?BookReviewStars
    {
        return $this->rating;
    }

    public function setRating(BookReviewStars $rating): self
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

    public function setAuthor(String $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook($book): self
    {
        $this->book = $book;
        return $this;
    }
}
