<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Repository\ContactRepository;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/index', name: 'app_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, CategoryRepository $categoryRepository, MailerInterface $mailer): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        $contact = new Contact();
        $contact->setDate(new \DateTimeImmutable());
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
    
            // Envoyer un e-mail
            $this->sendEmail($contact, $mailer);
    
            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact,ServiceRepository $serviceRepository,CategoryRepository $categoryRepository): Response
    {
        $services = $serviceRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }

    private function sendEmail(Contact $contact, MailerInterface $mailer)
{
    $email = (new Email())
        ->from('evan.moreau50T@gmail.com')
        ->to('evan.moreau50@gmail.com')
        ->subject('Nouveau contact créé')
        ->html('Un nouveau contact a été créé. Détails : ' . $contact->getEmail());

    $mailer->send($email);
}
}
