<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer extends User
{
    #[ORM\Column(length: 255)]
    private ?string $entreprise = null;

    /**
     * @var Collection<int, Queries>
     */
    #[ORM\OneToMany(targetEntity: Queries::class, mappedBy: 'castomer')]
    private Collection $queries;

    public function __construct()
    {
        $this->queries = new ArrayCollection();
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection<int, Queries>
     */
    public function getQueries(): Collection
    {
        return $this->queries;
    }

    public function addQuery(Queries $query): static
    {
        if (!$this->queries->contains($query)) {
            $this->queries->add($query);
            $query->setCastomer($this);
        }

        return $this;
    }

    public function removeQuery(Queries $query): static
    {
        if ($this->queries->removeElement($query)) {
            // set the owning side to null (unless already changed)
            if ($query->getCastomer() === $this) {
                $query->setCastomer(null);
            }
        }

        return $this;
    }

 
  
  

}
