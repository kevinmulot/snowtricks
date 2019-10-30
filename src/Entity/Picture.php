<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="picture")
     *
     */
    private $trick;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * Picture constructor.
     */
    public function __construct()
    {
        $this->statut = "normal";
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return trick|null
     */
    public function getTrick(): ?trick
    {
        return $this->trick;
    }

    /**
     * @param trick|null $trick
     * @return Picture
     */
    public function setTrick(?trick $trick): self
    {
        $this->trick = $trick;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     * @return Picture
     */
    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}
