<?php

namespace App\Controller;

use App\Entity\RatingService;
use App\Form\RatingServiceType;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RatingServiceRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/avis')]
class RatingServiceController extends AbstractController
{
    #[Route('/index', name: 'app_rating_service_index', methods: ['GET'])]
    public function index(RatingServiceRepository $ratingServiceRepository,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('rating_service/index.html.twig', [
            'rating_services' => $ratingServiceRepository->findAll(),
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/publish/{id}', name: 'app_rating_service_publish', methods: ['POST'])]
    public function publish($id, EntityManagerInterface $entityManager): Response
    {
        $rating = $entityManager->getRepository(RatingService::class)->find($id);
        if (!$rating) {
            throw $this->createNotFoundException('Avis non trouvé avec l\'ID : ' . $id);
        }
        $rating->setPublished(!$rating->isPublished());
        $entityManager->flush();
        return $this->redirectToRoute('app_rating_service_index');
    }

    #[Route('/', name: 'app_rating_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository, Security $security): Response
    {
        $ratingService = new RatingService();
        $form = $this->createForm(RatingServiceType::class, $ratingService);
        $form->handleRequest($request);
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        $user = $security->getUser();
        $ratingService->setUser($user);
        $ratingService->setPublished(false);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ratingService);
            $entityManager->flush();

            return $this->redirectToRoute('app_rating_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rating_service/new.html.twig', [
            'rating_service' => $ratingService,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_rating_service_show', methods: ['GET'])]
    public function show(RatingService $ratingService): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('rating_service/show.html.twig', [
            'rating_service' => $ratingService,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rating_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RatingService $ratingService, EntityManagerInterface $entityManager,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(RatingServiceType::class, $ratingService);
        $form->handleRequest($request);
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rating_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rating_service/edit.html.twig', [
            'rating_service' => $ratingService,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_rating_service_delete', methods: ['POST'])]
    public function delete(Request $request, RatingService $ratingService, EntityManagerInterface $entityManager,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ratingService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ratingService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rating_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
