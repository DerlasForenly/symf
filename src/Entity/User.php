<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['nickname'], message: 'This nickname is already in use.')]
#[UniqueEntity(fields: ['email'], message: 'This email address is already registered.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);

            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }
}
