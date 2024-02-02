<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Category1Type;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {        
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {

        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        $category = new Category();
        $form = $this->createForm(Category1Type::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(Category1Type::class, $category);
        $form->handleRequest($request);

        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
