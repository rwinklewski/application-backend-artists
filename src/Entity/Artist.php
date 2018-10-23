<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="artist",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="artist_token_uq",columns={"token"})}
 * )
 */
class Artist
{

    /**
     * @Groups({"artists","albums"})
     * @Assert\Type("string")
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Utils\TokenGenerator")
     * @ORM\Column(type="string", length=6, unique=true)
     */
    private $token;

    /**
     * @Groups({"artists","albums"})
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"artists"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Album", mappedBy="artist", cascade={"persist"})
     */
    private $albums;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Artist
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Artist
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return ArrayCollection|Album[]
     */
    public function getAlbums(): ArrayCollection
    {
        return $this->albums;
    }

    /**
     * @param ArrayCollection $albums
     * @return Artist
     */
    public function setAlbums(ArrayCollection $albums): self
    {
        $this->albums = $albums;
        return $this;
    }
}