<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Vico\RatingComment;
use App\Entity\Vico\RatingScore;
use App\Repository\VicoRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VicoRepository::class)]
#[ORM\Index(columns: ['name'], name: 'name_idx')]
class Vico
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Assert\NotBlank]
    #[Gedmo\Timestampable(on: "create")]
    private ?DateTimeImmutable $created;

    #[ORM\OneToMany(mappedBy: 'vico', targetEntity: RatingComment::class, cascade: ['persist'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'vico', targetEntity: RatingScore::class, cascade: ['persist'])]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, RatingComment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(RatingComment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setVico($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RatingScore>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(RatingScore $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setVico($this);
        }

        return $this;
    }

    public function removeRating(RatingScore $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getVico() === $this) {
                $rating->setVico(null);
            }
        }

        return $this;
    }
}
