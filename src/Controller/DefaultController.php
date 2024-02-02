<?php

namespace App\Controller;

use Detection\MobileDetect;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $mobileDetect = new MobileDetect();
        $isMobile = $mobileDetect->isMobile();

        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        
        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
            'is_mobile' => $isMobile,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/contact', name: 'app_default_contact')]
    public function contact(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository) : Response {

        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('contact.html.twig',[
            'controller_name' => 'DefaultControllerContact',
            'services' => $services,
            'categories' => $categories,
        ]);       
    }
}
