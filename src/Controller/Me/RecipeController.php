<?php

namespace App\Controller\Me;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Step;
use App\Entity\Utensil;
use App\Form\IngredientDeleteType;
use App\Form\IngredientRegistrationType;
use App\Form\IngredientSelectType;
use App\Form\RecipeDeleteType;
use App\Form\RecipeRegistrationType;
use App\Form\StepDeleteType;
use App\Form\StepRegistrationType;
use App\Form\UtensilDeleteType;
use App\Form\UtensilRegistrationType;
use App\Form\UtensilSelectType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/me/recipe', name: 'app_me_recipe')]
    public function index(RecipeRepository $recipeRepository): Response
    {   $recipes = $recipeRepository->findAll();
        $recipesDeleteForm = [];
        foreach ($recipes as $recipe) {
            $recipesDeleteForm[$recipe->getId()] = $this->createForm(RecipeDeleteType::class, $recipe)->createView();
        }
        return $this->render('me/recipe/index.html.twig', [
            'recipes' => $recipes,
            'deleteForms' => $recipesDeleteForm,
        ]);
    }

    #[Route('/me/recipe/show/{id}', name: 'app_me_recipe_details', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('me/recipe/detail.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/me/recipe/create', name: 'app_me_recipe_create' , methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeRegistrationType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $recipe = $form->getData();

            $recipe->setUser($this->getUser());
            $recipe->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);

        }

        return $this->render('/me/recipe/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/me/recipe/update/{id}', name: 'app_me_recipe_update' , methods:['POST', 'GET'])]
    public function update(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeRegistrationType::class, $recipe);

        $ingredientDeleteForms = [];
        foreach ($recipe->getIngredients() as $ingredient) {
            $ingredientDeleteForms[$ingredient->getId()] = $this->createForm(IngredientDeleteType::class, $ingredient)->createView();
        }

        $utensilDeleteForms = [];
        foreach ($recipe->getUtensils() as $utensil) {
            $utensilDeleteForms[$utensil->getId()] = $this->createForm(UtensilDeleteType::class, $utensil)->createView();
        }

        $stepDeleteForms = [];
        foreach ($recipe->getSteps() as $step) {
            $stepDeleteForms[$step->getId()] = $this->createForm(StepDeleteType::class, $step)->createView();
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $recipe = $form->getData();

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);

        }

        $ingredient = new Ingredient();

        $ingredientCreationForm = $this->createForm(IngredientRegistrationType::class, $ingredient);

        $ingredientSelectForm = $this->createForm(IngredientSelectType::class);

        $utensil = new Utensil();

        $utensilCreationForm = $this->createForm(UtensilRegistrationType::class, $utensil);

        $utensilSelectForm = $this->createForm(UtensilSelectType::class);

        $step = new Step();

        $stepCreationForm = $this->createForm(StepRegistrationType::class, $step);

        return $this->render('me/recipe/update.html.twig', [
            'form' => $form,
            'recipe' => $recipe,
            'ingredientCreationForm' => $ingredientCreationForm,
            'ingredientSelectForm' => $ingredientSelectForm,
            'ingredientDeleteForms' => $ingredientDeleteForms,
            'utensilCreationForm' => $utensilCreationForm,
            'utensilSelectForm' => $utensilSelectForm,
            'utensilDeleteForms' => $utensilDeleteForms,
            'stepCreationForm' => $stepCreationForm,
            'stepDeleteForms' => $stepDeleteForms,
        ]);
    }

    #[Route('/me/recipe/delete', name: 'app_me_recipe_delete' , methods:['DELETE'])]

    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(RecipeDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $recipe = $entityManager->getRepository(Recipe::class)->find($data['id']);
            $entityManager->remove($recipe);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe');

        }

        throw new \Exception('Something went wrong!');
    }

    #[Route('/me/recipe/update/{id}/add-ingredient', name: 'app_me_recipe_ingredient_add' , methods:['POST'])]
    public function addIngredient(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngredientSelectType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $ingredient = $data['ingredient'];

            $ingredient->addRecipe($recipe);

            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);
        }

        throw new \Exception('Something went wrong!');

    }


    #[Route('/me/recipe/update/{id}/ingredient', name: 'app_me_recipe_ingredient_create' , methods:['POST'])]
    public function createIngredient(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientRegistrationType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $ingredient = $form->getData();

            $ingredient->addRecipe($recipe);

            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);
        }

        throw new \Exception('Something went wrong!');

    }

    #[Route('/me/recipe/update/{id}/delete-ingredient', name: 'app_me_recipe_ingredient_delete' , methods:['POST'])]

    public function deleteIngredient(Request $request, EntityManagerInterface $entityManager, Recipe $recipe): Response
    {

        $form = $this->createForm(IngredientDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $ingredient = $entityManager->getRepository(Ingredient::class)->find($data['id']);
            $ingredient->delRecipe($recipe);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);

        }

        throw new \Exception('Something went wrong!');
    }

    #[Route('/me/recipe/update/{id}/add-utensil', name: 'app_me_recipe_utensil_add' , methods:['POST'])]
    public function addUtensil(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtensilSelectType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $utensil = $data['utensil'];

            $utensil->addRecipe($recipe);

            $entityManager->persist($utensil);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);
        }

        throw new \Exception('Something went wrong!');

    }


    #[Route('/me/recipe/update/{id}/utensil', name: 'app_me_recipe_utensil_create' , methods:['POST'])]
    public function createUtensil(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $utensil = new Utensil();

        $form = $this->createForm(UtensilRegistrationType::class, $utensil);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $utensil = $form->getData();

            $utensil->addRecipe($recipe);

            $entityManager->persist($utensil);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);
        }

        throw new \Exception('Something went wrong!');

    }

    #[Route('/me/recipe/update/{id}/delete-utensil', name: 'app_me_recipe_utensil_delete' , methods:['POST'])]

    public function deleteUtensil(Request $request, EntityManagerInterface $entityManager, Recipe $recipe): Response
    {

        $form = $this->createForm(UtensilDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $utensil = $entityManager->getRepository(Utensil::class)->find($data['id']);
            $utensil->delRecipe($recipe);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);

        }

        throw new \Exception('Something went wrong!');
    }

    #[Route('/me/recipe/update/{id}/step', name: 'app_me_recipe_step_create' , methods:['POST'])]
    public function createStep(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $step = new Step();

        $form = $this->createForm(StepRegistrationType::class, $step);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $step = $form->getData();

            $step->setRecipe($recipe);

            $entityManager->persist($step);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);
        }

        throw new \Exception('Something went wrong!');

    }

    #[Route('/me/recipe/update/{id}/delete-step', name: 'app_me_recipe_step_delete' , methods:['POST'])]

    public function deleteStep(Request $request, EntityManagerInterface $entityManager, Recipe $recipe): Response
    {

        $form = $this->createForm(StepDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $step = $entityManager->getRepository(Step::class)->find($data['id']);
            $entityManager->remove($step);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_me_recipe_update', ['id' => $recipe->getId()]);

        }

        throw new \Exception('Something went wrong!');
    }
}
