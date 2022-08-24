<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact')]
class ContactController extends AbstractController
{
    #[Route('', name: 'admin_contact_index')]
    public function index(ContactRepository $contactRepository, Request $request): Response
    {
        if ( $button = $request->query->get('button')) {
            $contacts = $contactRepository->search($button);
        } else {
            $contacts = $contactRepository->findBy([], ['id' => 'DESC']);
        }
    
        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contacts
        ]);
    }

    #[Route('/{id}', name: 'admin_contact_view')]
    public function view(Contact $contact): Response
    {
        return $this->render('admin/contact/view.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_contact_delete')]
    public function delete(Contact $contact, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($contact);
        $entityManager->flush();
        $this->addFlash('success', "Demande supprimée.");
        return $this->redirectToRoute('admin_contact_index');
    }
}
