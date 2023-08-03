<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RecipeController extends AbstractController
{
    /**
     * This controller display all recipes
     *
     * @param PaginatorInterface $paginator
     * @param RecipeRepository $repository
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recipe.index', methods: ['GET'])]
    public function index(
        PaginatorInterface $paginator, 
        RecipeRepository $repository, 
        Request $request
        ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $recipes = $paginator->paginate(           
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1), /*page number*/10/*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recette/communaute', name:'recipe.community', methods:['GET'])]
    public function indexPublic(
        PaginatorInterface $paginator,
        RecipeRepository $repository,
        Request $request   
        ): Response {
        
        $recipes = $paginator->paginate(
            $repository->findPublicRecipe(null),
            $request->query->getInt('page', 1), 8

        ); 
        return $this->render('pages/recipe/community.html.twig', [
            'recipes' => $recipes 
        ]);
    }
    
    // #[IsGranted(new Expression("subject.isPublic === true"), subject: 'recipe')]  
    // #[IsGranted(
    //     new Expression('is_granted("ROLE_ADMIN") and recipe.isPublic === true')
    //  )]

    #[Security("is_granted('ROLE_USER') and recipe.isPublic === true")]  
    #[Route('/recette/{id}', name:'recipe.show', methods:['GET'])]  
    public function show(
        Recipe $recipe, 
        RecipeRepository $repository, int $id,
        ): Response {

        $recipe = $repository->findOneBy(['id'=> $id]);
                
        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    } 

    /**
     * This controller allow us to create a new recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/nouveau', name:'recipe.new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager 
    ) : Response {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
    
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());
            
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été créé avec success !'
            );
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
   
    /**
     * This controller allow us to edit a recipe
     * @param RecipeRepository $repository
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */ 
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
    #[Route('/recette/edition/{id}', name:'recipe.edit', methods:['GET', 'POST'])] 
    public function edit(
        Recipe $recipe,
        RecipeRepository $repository, int $id,
        Request $request, 
        EntityManagerInterface $manager
        ): Response
    {      
        $recipe = $repository->findOneBy(['id'=> $id]);
        $form = $this->createForm(RecipeType::class, $recipe); 

        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()) {          
            $recipe = $form->getData();           
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifié avec success !'
            );
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView() 
        ]);
    }
    
    /**
     * This controller allow us to delete a recipe
     *
     * @param RecipeRepository $repository
     * @param EntityManagerInterface $manager
     * @return Response
     */ 
    #[Route('/recette/suppression/{id}', name:'recipe.delete', methods:['GET'])]
    public function delete(
        EntityManagerInterface $manager, 
        RecipeRepository $repository, int $id,
        ): Response 
    {
        $recipe = $repository->findOneBy(['id'=> $id]);
       
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash( 'success',
            'Votre recette a été supprimé avec success !'
            
        );

        return $this->redirectToRoute('recipe.index');
    }
}
