<?php


namespace App\Controller;


use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/files")
 */
class UploadImageController extends AbstractController
{
    /**
     * @Route("/uploadimgajx", name="uploadimgajx")
     */
    public function uploadAjx(Request $request, FileUploader $fileUploader){
        if ($request->isMethod('POST')){
            try {
                //Image Treatment logic Here
                $fileName = $fileUploader->upload($request->files->get('myImage'));
                return new JsonResponse(array('msg'=> 'File Uploaded!','file' => $fileName));
            }catch (\Exception $e){
                return new JsonResponse(array('msg'=> 'Error: No File Selected!'));
            }
        }else{
            return new JsonResponse('Fuck You');
        }
    }
}