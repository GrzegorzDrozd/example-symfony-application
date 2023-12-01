<?php

declare(strict_types=1);

namespace App\Entity\Vico;

use App\Entity\Client;
use App\Entity\Vico;
use App\Enums\Ratings;
use App\Repository\Vico\RatingScoreRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RatingScoreRepository::class)]
#[ORM\UniqueConstraint(columns: ['vico_id', 'client_id', 'name'])]
#[ORM\Table(name: 'vico_rating_score')]
class RatingScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list', 'view'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, enumType: Ratings::class)]
    #[Groups(['list', 'view'])]
    private ?Ratings $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['list', 'view'])]
    private ?int $value = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Gedmo\Timestampable(on: "create")]
    #[Groups(['list', 'view'])]
    private ?DateTimeImmutable $created;

    #[ORM\Column(nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Gedmo\Timestampable(on: "update")]
    #[Groups(['list', 'view'])]
    private ?DateTimeImmutable $updated;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['view'])]
    private ?Vico $vico = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['view'])]
    private ?Client $client = null;

    public function __construct()
    {
        $this->created = new DateTimeImmutable();
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

    public function getName(): ?Ratings
    {
        return $this->name;
    }

    public function setName(Ratings $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

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

    public function getUpdated(): ?DateTimeImmutable
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeImmutable $updated): static
    {
        $this->updated = $updated;

        return $this;
    }

    #[Groups(['view'])]
    public function getVico(): ?Vico
    {
        return $this->vico;
    }

    public function setVico(?Vico $vico): static
    {
        $this->vico = $vico;

        return $this;
    }

    #[Groups(['view'])]
    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
