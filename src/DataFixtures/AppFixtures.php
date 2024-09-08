<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Step;
use App\Entity\Utensil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\File;

class AppFixtures extends Fixture
{
    //real data
    // private const INGREDIENTS_NAME = ["Chocolat", "Beurre", "Mystère"];
    // private const UTENSILS_NAME = ["Fouet", "Moule", "Four"];
    // private const STEPS_NAME = ["Faire fondre le Mystère", "Enfourner le beurre", "Foueter le chocolat"];

    // public function load(ObjectManager $manager): void
    // {
    //     //category
    //     $category = new Category();
    //     $category->setName('Mystère');
    //     $manager->persist($category);

    //     //recipe
    //     $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris') );
    //     $recipe = new Recipe();
    //     $recipe->setTitle('Mystère au Chocolat');
    //     $recipe->setCategory($category);
    //     $recipe->setCreatedAt($now) ;
    //     $manager->persist($recipe);

    //     //ingredients
    //     foreach (self::INGREDIENTS_NAME as $ingredientName) {
    //         $ingredient = new Ingredient();
    //         $ingredient->setName($ingredientName);

    //         $manager->persist($ingredient);
    //         $recipe->addIngredient($ingredient);
    //     }

    //     //utensils
    //     foreach (self::UTENSILS_NAME as $utensilName) {
    //         $utensil = new Utensil();
    //         $utensil->setName($utensilName);

    //         $manager->persist($utensil);
    //         $recipe->addUtensil($utensil);
    //     }

    //     //step
    //     foreach (self::STEPS_NAME as $stepName) {
    //         $step = new Step();
    //         $step->setName($stepName);

    //         $manager->persist($step);
    //         $recipe->addStep($step);
    //     }

    //     $manager->flush();

    // }


    //False data

    private const NB_RECIPES = 20;
    private const NB_INGREDIENTS = 50;
    private const NB_UTENSILS = 20;
    private const NB_CATEGORIES = 5;

    private array $categories;
    private array $ingredients;
    private array $utensils;
    private $faker;
    public function __construct(
        private string $projectDir
    ) {
        $this->faker = Factory::create('en_EN');
        $this->categories = [];
        $this->ingredients = [];
        $this->utensils = [];
    }
    public function load(ObjectManager $manager): void
    {
        //category
        for ($i = 0; $i < self::NB_CATEGORIES; $i++) {
            $this->addCategory($manager);
        }

        //ingredient
        for ($i = 0; $i < self::NB_INGREDIENTS; $i++) {
            $this->addIngredient($manager);
        }

        //utensil
        for ($i = 0; $i < self::NB_UTENSILS; $i++) {
            $this->addUtensil($manager);
        }

        //recipe
        $flush = 0;
        for ($i = 0; $i < self::NB_RECIPES; $i++) {
            $this->addRecipe($manager);

            $flush++;
            if ($flush == 100) {
                $manager->flush();
                $flush = 0;
            }
        }

        $manager->flush();

    }

    private function addCategory(ObjectManager $manager): void 
    {
        $category = new Category();
        $category->setName($this->faker->word());

        $manager->persist($category);
        $this->categories[] = $category;
    }

    private function addIngredient(ObjectManager $manager): void
    {
        $ingredient = new Ingredient();
        $ingredient->setName($this->faker->word());

        $manager->persist($ingredient);
        $this->ingredients[] = $ingredient;
    }

    private function addUtensil(ObjectManager $manager): void
    {
        $utensil = new Utensil();
        $utensil->setName($this->faker->word());

        $manager->persist($utensil);
        $this->utensils[] = $utensil;
    }
    private function addRecipe(ObjectManager $manager): void
    {
        $filepath = $this->faker->image(null, 640, 480);
        $file = new File($filepath);
        $recipe = new Recipe();
        $recipe
            ->setTitle($this->faker->words(1, 3))
            ->setCategory($this->faker->randomElement($this->categories))
            ->setPreview($file, $this->projectDir)
            ->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris') ))

            ->addIngredients(
                $this->faker->randomElements(
                    $this->ingredients,
                    $this->faker->randomDigit()
                )
            )
            ->addUtensils(
                $this->faker->randomElements(
                    $this->utensils,
                    $this->faker->randomDigit()
                )
            )
        ;
        
        for ($j = $this->faker->randomDigit() ; $j > 0; $j-- ) {
            $step = new Step;
            $step->setName($this->faker->sentence());
            $recipe->addStep($step);
            $manager->persist($step);
        }
        $manager->persist($recipe);
    }
}
