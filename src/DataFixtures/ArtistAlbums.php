<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Exception\RuntimeException;

class ArtistAlbums extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $content = file_get_contents("https://gist.githubusercontent.com/fightbulc/9b8df4e22c2da963cf8ccf96422437fe/raw/8d61579f7d0b32ba128ffbf1481e03f4f6722e17/artist-albums.json");
        $rawData = json_decode($content, true);

        if (!$rawData) {
            throw new RuntimeException("invalid fixtures");
        }

        foreach ($rawData as $artistEntry) {

            $artist = new Artist();
            $artist->setName($artistEntry['name']);

            foreach ($artistEntry['albums'] as $albumEntry) {
                $album = $this->createAlbum($albumEntry);
                $album->setArtist($artist);

                $manager->persist($album);
            }
            $manager->persist($artist);
        }
        $manager->flush();
    }

    /**
     * @param array $rawData
     * @return Album
     */
    private function createAlbum(array $rawData): Album
    {
        $album = new Album();
        $album->setTitle($rawData['title']);
        $album->setCover($rawData['cover']);
        $album->setDescription($rawData['description']);

        foreach ($rawData['songs'] as &$song) {

            $length = explode(':', $song['length']);
            $length = ($length[0] * 60) + $length[1];
            $song['length'] = $length;
        }
        $album->setSongs($rawData['songs']);

        return $album;
    }
}