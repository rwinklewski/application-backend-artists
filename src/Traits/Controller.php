<?php

namespace App\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Teapot\StatusCode;

trait Controller
{
    /**
     * @return Serializer
     */
    private function getSerializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);

        $songsCallback = function($songs){
            foreach ($songs as &$song) {
                $timeInSeconds = $song['length'];
                $minutes = intval($timeInSeconds / 60);
                $seconds = $timeInSeconds % 60;
                $song['length'] = $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
            }
            return $songs;
        };

        $normalizer->setCallbacks([
            'songs' => $songsCallback
        ]);

        return new Serializer([$normalizer], [new JsonEncoder()]);
    }

    /**
     * @param mixed $objectOrArray
     * @param array $context
     * @return Response
     */
    private function sendResponse($objectOrArray, array $context = []): Response
    {
        $response = new Response();

        if (is_null($objectOrArray)) {
            $data = json_encode(["error" => 'object not found']);
            $response->setStatusCode(StatusCode::NOT_FOUND);
        } else {
            $data = $this->getSerializer()->serialize($objectOrArray, 'json', $context);
            $response->setStatusCode(StatusCode::OK);
        }

        $response->setContent($data);
        $response->headers->set('Content-Type','application/json');
        return $response;
    }

    /**
     * @param string $name
     * @return ObjectRepository
     */
    private function getRepository(string $name): ObjectRepository
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository($name);
    }
}

