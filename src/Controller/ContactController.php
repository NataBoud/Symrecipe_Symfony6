<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        MailerInterface $mailer
        ): Response {

        $contact = new Contact();

        if($this->getUser()) {
            $contact->setFirstName($this->getUser()->getFirstName());
            $contact->setLastName($this->getUser()->getLastName());
            $contact->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            
            $manager->persist($contact);
            $manager->flush();

            // Email

            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('admin@symrecipe.com')
            ->subject($contact->getSubject())
            // path of the Twig template to render
            ->htmlTemplate('email/contact.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'contact' => $contact,
                
            ]);

        $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a été envoyé avec success !'
            );
            return $this->redirectToRoute('contact.index');

           }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
