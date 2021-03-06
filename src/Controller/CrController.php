<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\ManagerRepository;
use App\Entity\Manager;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ManagerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
class CrController extends AbstractController
{
/**
 * @var Environment
 * 
 */
private $twig; 

public function __construct(ManagerRepository $repository , Environment $twig,EntityManagerInterface $em)
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
     return $this->render('cr/home.html.twig', compact('clients'));

 }


//  -------------------------------------------------------------------------------------------------------------------------------------
   /**
     * @Route("/editmanager/editClient_number/{id}/", name="editManager")
    */
    public function edit(Manager $client , Request $request): Response
    {
        $form=$this -> createForm (ManagerType :: class , $client );
        //gerer la requete de la formulaire 
        $form -> handleRequest($request); 
        if ($form -> isSubmitted () && $form -> isValid() )
        {
            //persiste les modification dans la base de donner 
            $this -> em -> flush() ;
            $this -> addFlash('success' , 'bien modifié');
            return $this -> redirectToRoute('home') ; 
        }
        return $this->render('cr/edit.html.twig', [
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
       
        $client= new Manager();
        $form=$this -> createForm (  ManagerType :: class , $client);

        $form = $this->createFormBuilder($client)
            ->add('Name', TextType::class)
            ->add('Age', TextType::class)
            ->add('Code', TextType::class)
            ->add('image', FileType::class, array('label' => 'Photo (png, jpeg)'))
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm(); 


        $form -> handleRequest($request); 
        if ($form -> isSubmitted () && $form -> isValid() )
                    {

            $file = $client->getImage();
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);
            $client->setImage($fileName); 
                    // informe Doctrine que l’on veut ajouter cet objet dans la base de donnees. 
                    $this -> em -> persist($client);
                    //permet d’executer la requ ´ ete et d’envoyer tout ce qui a eté  persist avant a la base de donnees.
                    $this -> em -> flush () ;  
                    return $this -> redirectToRoute('home');
                    }
        return $this->render('cr/new.html.twig',
    [
        'client' => $client , 
        'form' => $form -> createView()


    ]);
    }
// --------------------------------------------------------------------------------

// --------------------------------------------------------------------------------

/**
     * @Route("/delete/{id}", name="delete"  )
     */
    public function delete(Manager $client)
    {
      $this -> em -> remove ($client);
      $this -> em -> flush() ; 
      return $this -> redirectToRoute('home');
    }


}

