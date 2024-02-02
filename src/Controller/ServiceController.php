<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use App\Services\ImageUploaderHelper;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository, ImageUploaderHelper $imageUploaderHelper): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
        
            $img1 = $form->get('img1')->getData();
            $img2 = $form->get('img2')->getData();
            $img3 = $form->get('img3')->getData();

            if (isset($img1)) {
                $errorMessage = $imageUploaderHelper->uploadImage($form, $service, 'img1'); // Pass 'img1' as the field name
                if (!empty($errorMessage)) {
                    $this->addFlash('danger', 'An error has occurred: ' . $errorMessage);
                }
            }
        
            if (isset($img2)) {
                $errorMessage = $imageUploaderHelper->uploadImage($form, $service, 'img2'); // Pass 'img2' as the field name
                if (!empty($errorMessage)) {
                    $this->addFlash('danger', 'An error has occurred: ' . $errorMessage);
                }
            }
        
            if (isset($img3)) {
                $errorMessage = $imageUploaderHelper->uploadImage($form, $service, 'img3'); // Pass 'img3' as the field name
                if (!empty($errorMessage)) {
                    $this->addFlash('danger', 'An error has occurred: ' . $errorMessage);
                }
            }
        
            $entityManager->persist($service);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        
        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('service/show.html.twig', [
            'service' => $service,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
