<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail_path = null;

    #[ORM\Column(length: 255)]
    private ?string $prompt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnail_path;
    }

    public function setThumbnailPath(?string $thumbnail_path): static
    {
        $this->thumbnail_path = $thumbnail_path;

        return $this;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
