<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="album",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="album_token_uq",columns={"token"})}
 * )
 */
class Album
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
    private $title;

    /**
     * @Groups({"artists","albums"})
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $cover;

    /**
     * @Groups({"albums"})
     * @Assert\Type("string")
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Groups({"albums"})
     * @Assert\Type("array")
     *
     * @ORM\Column(type="json")
     */
    private $songs;

    /**
     * @Groups({"albums"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="albums", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="token", nullable=false)
     */
    private $artist;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Album
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     * @return Album
     */
    public function setCover(string $cover): self
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Album
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return array
     */
    public function getSongs(): array
    {
        return $this->songs;
    }

    /**
     * @param array $songs
     * @return Album
     */
    public function setSongs(array $songs): self
    {
        $this->songs = $songs;
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
     * @return Album
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return Artist
     */
    public function getArtist(): Artist
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     * @return Album
     */
    public function setArtist(Artist $artist): self
    {
        $this->artist = $artist;
        return $this;
    }
}