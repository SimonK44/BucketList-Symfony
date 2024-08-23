<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    #[Assert\NotBlank(message: 'Titre obligatoire')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Minimum {{ limit }} characters please!',
        maxMessage: 'Maximum {{ limit }} characters please!'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 5000,
        minMessage: "Minimum {{ limit }} characters please!",
        maxMessage: "Maximum {{ limit }} characters please!"
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateUpdated = null;

    #[ORM\ManyToOne(inversedBy: 'wishes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'wishes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->isPublished = false;
        $this->dateCreated = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?\DateTimeInterface $dateUpdated): static
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
