<?php

namespace App\Controller;

use DateTime;
use Detection\MobileDetect;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use App\Repository\AppointmentRepository;
use App\Repository\RatingServiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository, RatingServiceRepository $ratingSericeRepository, AppointmentRepository $appointmentRepository): Response
    {
        $mobileDetect = new MobileDetect();
        $isMobile = $mobileDetect->isMobile();

        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        $ratingService = $ratingSericeRepository->findAll();
        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
            'is_mobile' => $isMobile,
            'services' => $services,
            'categories' => $categories,
            'ratings' => $ratingService,
            'appointments' => $appointmentRepository->findAll(),

        ]);
    }

}
