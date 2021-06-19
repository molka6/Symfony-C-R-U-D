<?php
namespace App\Controller;
use Twig\Environment;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController


{
/**
 * @var Environment
 * 
 */
   private $twig; 

   public function __construct(ClientRepository $repository , Environment $twig)
{
    $this-> repository= $repository;
    $this -> twig =$twig; 
}

	 /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
      
        $clients=$this->repository->findall(); 
        return $this->render('pages/home.html.twig', compact('clients'));

    }
    /**
     * @Route("/edit/{id}/edit", name="Preporty.crud.edit")
     */





}
