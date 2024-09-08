<?php

namespace App\Entity;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: Step::class, mappedBy: 'recipe')]
    private Collection $steps;

    #[ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    #[JoinTable(name: 'recipes_ingredients')]
    private Collection $ingredients;

    #[ManyToMany(targetEntity: Utensil::class, inversedBy: 'recipes')]
    #[JoinTable(name: 'recipes_utensils')]
    private Collection $utensils;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'recipe')]
    private Category $category;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recipe')]
    private  User $user;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->utensils = new ArrayCollection();
    }


    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function getUtensils(): Collection
    {
        return $this->utensils;
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addRecipe($this);
        }

        return $this;
    }
    public function addIngredients(array $ingredients): static
    {
        foreach ($ingredients as $ingredient) {
            $this->addIngredient($ingredient);
        }

        return $this;
    }

    public function delIngredient(Ingredient $ingredient): static
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            $ingredient->delRecipe($this);
        }

        return $this;
    }
    public function addUtensil(Utensil $utensil): static
    {
        if (!$this->utensils->contains($utensil)) {
            $this->utensils[] = $utensil;
            $utensil->addRecipe($this);
        }

        return $this;
    }
    public function addUtensils(array $utensils): static
    {
        foreach ($utensils as $utensil) {
            $this->addUtensil($utensil);
        }

        return $this;
    }
    public function delUtensil(Utensil $utensil): static
    {
        if ($this->utensils->contains($utensil)) {
            $this->utensils->removeElement($utensil);
            $utensil->delRecipe($this);
        }

        return $this;
    }
    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }

        return $this;
    }
    public function delStep(Step $step): static
    {
        if ($this->steps->contains($step)) {
            $this->steps->removeElement($step);
        }

        return $this;
    }

    #[ORM\Column(type: 'string')]
    private string $previewFilename;

    public function getPreviewFilename(): string
    {
        return $this->previewFilename;
    }

    public function setPreviewFilename(string $previewFilename): self
    {
        $this->previewFilename = $previewFilename;

        return $this;
    }

    public function setPreview($previewfile): self
    {
        $extension = $previewfile->guessExtension();
        $newFilename = rand(1, 99999).'.'.$extension;
        $previewfile->move('recipes/preview/', $newFilename);
        $this->setPreviewFilename('recipes/preview/' . $newFilename);

        return $this;
    }
}