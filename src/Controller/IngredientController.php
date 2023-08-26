<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class IngredientController extends AbstractController

{
    /**
     * This controller display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
     // décoration la controlleur avec Route
    #[Route('/ingredient', name: 'ingredient.index', methods:['GET'])]
    public function index(
        IngredientRepository $repository, 
        PaginatorInterface $paginator, 
        Request $request
        ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $ingredients = $paginator->paginate(
            // $query, /* query NOT result */
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );       
        // dd($ingredients);
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients 
        ]);
    }

    /**
     *  This controller allow us to create a new ingrédient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/creation', name:'ingredient.new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient); 

        $form->handleRequest($request);       
        if($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec success !'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView() 
        ]);
    }

     /**
     * This controller update an igredient
     *
     * @param IngredientRepository $repository
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/edition/{id}', name:'ingredient.edit', methods:['GET', 'POST'])] 
    public function edit(
        Ingredient $ingredient,
        IngredientRepository $repository, int $id,
        Request $request, 
        EntityManagerInterface $manager
        ): Response
    {   
        $ingredient = $repository->findOneBy(['id'=> $id]);

        $form = $this->createForm(IngredientType::class, $ingredient); 

        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()) {          
            $ingredient = $form->getData();           
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec success !'
            ); 
            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView() 
        ]);
    } 

     /**
     * This controller allow us to delete an igredient
     *
     * @param IngredientRepository $repository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")] 
    #[Route('/ingredient/suppression/{id}', name:'ingredient.delete', methods:['GET'])]
    public function delete(
        EntityManagerInterface $manager, 
        IngredientRepository $repository, int $id,
        Ingredient $ingredient,
        ): Response 
    {
        $ingredient = $repository->findOneBy(['id'=> $id]);

        // if(!$ingredient) {
        //     $this->addFlash(
        //         'warning',
        //         'L\'ingrédient en question n\'a été trouvé !'
        //     );
        //     return $this->redirectToRoute('ingredient.index');
        // }
        
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash( 'success',
            'Votre ingrédient a été supprimé avec success !'
            
        );

        return $this->redirectToRoute('ingredient.index');
    }
}