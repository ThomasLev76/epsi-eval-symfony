<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDestruction = null;

    #[ORM\ManyToOne(inversedBy: 'oeuvres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

    /**
     * @var Collection<int, Artiste>
     */
    #[ORM\ManyToMany(targetEntity: Artiste::class, inversedBy: 'oeuvres')]
    private Collection $artistes;

    /**
     * @var Collection<int, Technique>
     */
    #[ORM\ManyToMany(targetEntity: Technique::class, inversedBy: 'oeuvres')]
    private Collection $techniques;

    #[ORM\OneToOne(targetEntity: self::class, inversedBy: 'oeuvreRecouverte', cascade: ['persist', 'remove'])]
    private ?self $oeuvreRecouvrante = null;

    #[ORM\OneToOne(targetEntity: self::class, mappedBy: 'oeuvreRecouvrante', cascade: ['persist', 'remove'])]
    private ?self $oeuvreRecouverte = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'oeuvre')]
    private Collection $photos;

    public function __construct()
    {
        $this->artistes = new ArrayCollection();
        $this->techniques = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

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

    public function getDateDestruction(): ?\DateTime
    {
        return $this->dateDestruction;
    }

    public function setDateDestruction(?\DateTime $dateDestruction): static
    {
        $this->dateDestruction = $dateDestruction;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection<int, Artiste>
     */
    public function getArtistes(): Collection
    {
        return $this->artistes;
    }

    public function addArtiste(Artiste $artiste): static
    {
        if (!$this->artistes->contains($artiste)) {
            $this->artistes->add($artiste);
        }

        return $this;
    }

    public function removeArtiste(Artiste $artiste): static
    {
        $this->artistes->removeElement($artiste);

        return $this;
    }

    /**
     * @return Collection<int, Technique>
     */
    public function getTechniques(): Collection
    {
        return $this->techniques;
    }

    public function addTechnique(Technique $technique): static
    {
        if (!$this->techniques->contains($technique)) {
            $this->techniques->add($technique);
        }

        return $this;
    }

    public function removeTechnique(Technique $technique): static
    {
        $this->techniques->removeElement($technique);

        return $this;
    }

    public function getOeuvreRecouvrante(): ?self
    {
        return $this->oeuvreRecouvrante;
    }

    public function setOeuvreRecouvrante(?self $oeuvreRecouvrante): static
    {
        $this->oeuvreRecouvrante = $oeuvreRecouvrante;

        return $this;
    }

    public function getOeuvreRecouverte(): ?self
    {
        return $this->oeuvreRecouverte;
    }

    public function setOeuvreRecouverte(?self $oeuvreRecouverte): static
    {
        // unset the owning side of the relation if necessary
        if ($oeuvreRecouverte === null && $this->oeuvreRecouverte !== null) {
            $this->oeuvreRecouverte->setOeuvreRecouvrante(null);
        }

        // set the owning side of the relation if necessary
        if ($oeuvreRecouverte !== null && $oeuvreRecouverte->getOeuvreRecouvrante() !== $this) {
            $oeuvreRecouverte->setOeuvreRecouvrante($this);
        }

        $this->oeuvreRecouverte = $oeuvreRecouverte;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setOeuvre($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getOeuvre() === $this) {
                $photo->setOeuvre(null);
            }
        }

        return $this;
    }
}
