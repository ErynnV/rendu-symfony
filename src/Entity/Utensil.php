<?php

namespace App\Entity;
use App\Repository\UtensilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity(repositoryClass: UtensilRepository::class)]
class Utensil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:255)]
    private ?string $name = null;

    #[ManyToMany(targetEntity: Recipe::class, mappedBy: 'utensils')]
    private Collection $recipes;

    public function __construct() {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addUtensil($this);
        }

        return $this;
    }
    public function delRecipe(Recipe $recipe): static
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            $recipe->delUtensil($this);
        }

        return $this;
    }
}