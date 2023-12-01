<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Vico\RatingComment;
use App\Entity\Vico\RatingScore;
use App\Repository\ClientRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_70E4FA78F85E0677', columns: ['username'])]
#[ORM\Index(fields: ['username'], name: 'username_idx')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(length: 96)]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Assert\NotBlank]
    #[Gedmo\Timestampable(on: "create")]
    private ?DateTimeImmutable $created;

    #[ORM\Column(length: 96)]
    #[Assert\NotBlank]
    private ?string $first_name = null;

    #[ORM\Column(length: 96)]
    #[Assert\NotBlank]
    private ?string $last_name = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: RatingComment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: RatingScore::class)]
    private Collection $ratings;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Collection<int, RatingComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return Collection<int, RatingScore>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }
}
