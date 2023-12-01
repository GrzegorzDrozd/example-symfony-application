<?php

declare(strict_types=1);

namespace App\Entity\Vico;

use App\Entity\Client;
use App\Entity\Vico;
use App\Repository\Vico\RatingCommentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RatingCommentRepository::class)]
#[ORM\UniqueConstraint(columns: ['vico_id', 'client_id'])]
#[ORM\Table(name: 'vico_rating_comment')]
class RatingComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: Vico::class)]
    #[ORM\JoinColumn(name: 'vico_id', referencedColumnName: 'id', nullable: false)]
    private ?Vico $vico = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Gedmo\Timestampable(on: "create")]
    private ?DateTimeImmutable $created;

    #[ORM\Column(nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Gedmo\Timestampable(on: "update", field: ["content"])]
    private ?DateTimeImmutable $updated;

    public function __construct()
    {
        $this->created = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getVico(): ?Vico
    {
        return $this->vico;
    }

    public function setVico(Vico $vico): static
    {
        $this->vico = $vico;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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
}
