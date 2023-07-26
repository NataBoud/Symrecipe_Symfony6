<?php

namespace App\Controller;

use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Command\UserPasswordHashCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{   
    /**
     * This controller allow us to edit user's profile
     *
     * @param UserRepository $repository
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['POST', 'GET'])]
    public function edit(
        UserRepository $repository, int $id, 
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher  
        ): Response
    {
        $user = $repository->findOneBy(['id'=> $id]);

        if(!$this->getUser())
        {
            return $this->redirectToRoute('security.login');
        }

        if($this->getUser() !== $user)
        {
            return $this->redirectToRoute('recipe.index');
        } 

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())
            ) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiées.'
                );
                return $this->redirectToRoute('recipe.index');
            }else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe reinseigné est incorrect.'
                );
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}',  name: 'user.edit.password', methods: ['POST', 'GET'])]
    public function editPassword(
        UserRepository $repository, int $id, 
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher  
    ): Response 
    {
        $user = $repository->findOneBy(['id'=> $id]);
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            if($hasher->isPasswordValid($user, $form->getData()['plainPassword'])
            ) {
                // $user->setPlainPassword(
                //     $form->getData()['newPassword']
                // );

                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->getData()['newPassword']
                    )
                );


                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a bien été modifié.'
                );

                return $this->redirectToRoute('recipe.index');
            }else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe reinseigné est incorrect.'
                );
            }   
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
