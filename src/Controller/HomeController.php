<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/','home.index', methods:['GET'] )]
    public function index(): Response
    {
        return $this->render('home.html.twig',); 
    }
}

// 1. Creation d'un Controller => fichier HomeController.php => class du meme nom class HomeController => extends AbstractController
// 2. Creation d'une fonction =>  public function index(): Response {} => methode render qui envoie vers tempelate twig => render('home.html.twig'); => il faut creer dans le fichier template: home.html.twig
// 3. Pour faire le relais  => #[Route('/','home.index', methods:['GET'] )]

// 4. twig - le moteur de template de symfony pour gerer html(complexe)
// 5. CONSTRUCTIONS D"UN PAGE a l'aide twig
//     { ... }}, used to display the content of a variable or the result of evaluating an expression;
//     {% ... %}, used to run some logic, such as a conditional or a loop;
//     {# ... #}, used to add comments to the template