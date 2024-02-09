<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\FileUploaderHelper;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    #[Route('/', name: 'app_appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();

        $currentDate = new \DateTime();

        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
            'services' => $services,
            'categories' => $categories,
            'currentDate' => $currentDate,
        ]);
    }

    #[Route('/new', name: 'app_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository,FileUploaderHelper $fileUploaderHelper): Response {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
    
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Upload the PDF file
            $pdfUploadError = $fileUploaderHelper->uploadFile($form, $appointment, 'pdf');
            if ($pdfUploadError === '') {
                // Save the entity and do any other necessary actions
                $entityManager->persist($appointment);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'PDF upload failed: ' . $pdfUploadError);
            }
        }
    
        return $this->render('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository, FileUploaderHelper $fileUploaderHelper): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
    
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
                
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a new PDF file is uploaded
            $newPdfFile = $form['pdf']->getData();
            if ($newPdfFile) {
               
                // Upload the new PDF file
                $pdfUploadError = $fileUploaderHelper->uploadFile($form, $appointment, 'pdf');
                
                if ($pdfUploadError !== '') {
                    $this->addFlash('error', 'PDF upload failed: ' . $pdfUploadError);
                    return $this->redirectToRoute('app_appointment_edit', ['id' => $appointment->getId()]);
                }
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,    
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
