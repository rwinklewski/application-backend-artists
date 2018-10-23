<?php

namespace App\Controller;

use App\Traits\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    use Controller;

    /**
     * @Route("/albums/{token}", methods={"GET"})
     * @param string $token
     * @return Response
     */
    public function showAlbum(string $token): Response
    {
        $album = $this->getRepository('App:Album')->find($token);
        return $this->sendResponse($album, ['groups' => ['albums']]);
    }
}
