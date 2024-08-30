<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryDeleteType;
use App\Form\CategoryRegistrationType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_admin_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {   $categories = $categoryRepository->findAll();
        $categoriesDeleteForm = [];
        foreach ($categories as $category) {
            $categoriesDeleteForm[$category->getId()] = $this->createForm(CategoryDeleteType::class, $category)->createView();
        }
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'deleteForms' => $categoriesDeleteForm,
        ]);
    }

    #[Route('/admin/category/create', name: 'app_admin_category_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryRegistrationType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_admin_category_create');

        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/category/update/{id}', name: 'app_admin_category_update')]
    public function update(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryRegistrationType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_admin_category_update', ['id' => $category->getId()]);

        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/category/delete', name: 'app_admin_category_delete')]

    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(CategoryDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $category = $entityManager->getRepository(Category::class)->find($data['id']);
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_admin_category');

        }

        throw new \Exception('Something went wrong!');
    }
}