<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use Symfony\Component\HttpFoundation\Request;
use App\Entity\File;
use Symfony\Component\HttpFoundation\JsonResponse;





class FileController extends AbstractController
{


    /** 
     * @Route("/api/upload", name="upload_image" , methods={"POST"}  )
     */

    public function uploadImage(Request $request)
    {
        $file = new File();
        $uploadedImage = $request->files->get('file');
        /**
         * @var UploadedFile $image
         */
        $image = $uploadedImage;


        $imageName = md5(uniqid()) . '.' . $image->guessExtension();


        $image->move($this->getParameter('image_directory'), $imageName);


        $file->setImage($imageName);


        $em = $this->getDoctrine()->getManager();


        $em->persist($file);


        $em->flush();



        $response = array(

            'code' => 0,
            'message' => 'File Uploaded with success!',
            'errors' => null,
            'result' => null

        );
        return new JsonResponse($response, Response::HTTP_CREATED);
    }



    /** 
     * @Route("api/images", name="show_images" , methods={"GET"}  )
     */



    public function getImages()
    {


        $images = $this->getDoctrine()->getRepository('App:File')->findAll();


        $data = $this->get('jms_serializer')->serialize($images, 'json');

        $response = array(

            'message' => 'images loaded with sucesss',
            'result' => json_decode($data)

        );

        return new JsonResponse($response, 200);
    }



    /** 
     * @Route("api/image/{id}", name="show_image" , methods={"GET"}  )
     */

    public function getImage($id)
    {
        $imageName = $this->getDoctrine()->getRepository('App:File')->find($id)->getImage();


        $response = array(

            'code' => 0,
            'message' => 'get image with success!',
            'errors' => null,
            'result' => $imageName

        );

        return new JsonResponse($response, 200);
    }
}
