<?php

namespace App\Controller;

use App\Traits\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    use Controller;

    /**
     * @Route("/artists", methods={"GET"})
     * @return Response
     */
    public function showArtists(): Response
    {
        $artists = $this->getRepository('App:Artist')->findAll();
        return $this->sendResponse($artists, ['groups' => ['artists']]);
    }

    /**
     * @Route("/artists/{token}", methods={"GET"})
     * @param string $token
     * @return Response
     */
    public function showArtist(string $token): Response
    {
        $artist = $this->getRepository('App:Artist')->find($token);
        return $this->sendResponse($artist, ['groups' => ['artists']]);
    }
}