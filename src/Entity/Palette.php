<?php
namespace App\Entity;

use App\Repository\PaletteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Logs::class, mappedBy="palette")
     */
    private $logs;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

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

    /**
     * @return Collection|Logs[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Logs $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setPalette($this);
        }

        return $this;
    }

    public function removeLog(Logs $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getPalette() === $this) {
                $log->setPalette(null);
            }
        }

        return $this;
    }
}
