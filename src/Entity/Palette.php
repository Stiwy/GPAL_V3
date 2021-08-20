<?php
namespace App\Entity;

use App\Repository\PaletteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaletteRepository::class)
 */
class Palette
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="palettes")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $weg;

    /**
     * @ORM\Column(type="integer")
     */
    private $shelf;

    /**
     * @ORM\Column(type="date")
     */
    private $insertDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getWeg(): ?int
    {
        return $this->weg;
    }

    public function setWeg(int $weg): self
    {
        $this->weg = $weg;

        return $this;
    }

    public function getShelf(): ?int
    {
        return $this->shelf;
    }

    public function setShelf(int $shelf): self
    {
        $this->shelf = $shelf;

        return $this;
    }

    public function getInsertDate(): ?\DateTimeInterface
    {
        return $this->insertDate;
    }

    public function setInsertDate(\DateTimeInterface $insertDate): self
    {
        $this->insertDate = $insertDate;

        return $this;
    }
}
