<?php
namespace App\Controller;
use Twig\Environment;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
class HomeController extends AbstractController


{
/**
 * @var Environment
 * 
 */
   private $twig; 

   public function __construct(ClientRepository $repository , Environment $twig,EntityManagerInterface $em)
{
    $this-> repository= $repository;
    $this -> twig =$twig; 
    $this -> em =$em ;
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
     * @Route("/edit/editClient_number/{id}/", name="edit")
    */
public function edit(Client $client , Request $request): Response
{
    $form=$this -> createForm ( ClientType :: class , $client );
    //gerer la requete de la formulaire 
    $form -> handleRequest($request); 
    if ($form -> isSubmitted () && $form -> isValid() )
    {
        //persiste les modification dans la base de donner 
        $this -> em -> flush() ;
        $this -> addFlash('success' , 'bien modifié');
        return $this -> redirectToRoute('home') ; 
    }
    return $this->render('pages/edit.html.twig', [
        'client' => $client , 
        'form' => $form -> createView()
    ]
);
}

/**
     * @Route("/new", name="new")
     */
    public function new (Request $request): Response
    {
       
        $client= new Client();
        $form=$this -> createForm (  ClientType :: class , $client);
        $form -> handleRequest($request); 
        if ($form -> isSubmitted () && $form -> isValid() )
                    {
                    // informe Doctrine que l’on veut ajouter cet objet dans la base de donnees. 
                    $this -> em -> persist($client);
                    //permet d’executer la requ ´ ete et d’envoyer tout ce qui a eté  persist avant a la base de donnees.
                    $this -> em -> flush () ;  
                    return $this -> redirectToRoute('home');
                    }
        return $this->render('pages/new.html.twig',
    [
        'client' => $client , 
        'form' => $form -> createView()


    ]);
    }

    /**
     * @Route("/delete/{id}", name="delete"  )
     */
    public function delete(Client $client)
    {
      $this -> em -> remove ($client);

      $this -> em -> flush() ; 
      return $this -> redirectToRoute('home');
    }
}
