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

class IngredientController extends AbstractController
{
    /**
     * This function display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient_index', methods:['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {   
        $ingredients = $paginator->paginate(
            // $query, /* query NOT result */
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );       
        // dd($ingredients);
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients 
        ]);
    }

    #[Route('/ingredient/nouveau', 'ingredient.new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient); 

        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            // dd($form->getData());
            $ingredient = $form->getData();
            
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec success !'
            );

            // dd($ingredient);

            // $this->redirectToRoute('ingredient_index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView() 
        ]);
    }
}
