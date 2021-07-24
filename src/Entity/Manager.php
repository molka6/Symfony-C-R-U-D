<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ManagerRepository::class)
 */
class Manager
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
     */

    private $Name;


    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $Age;




    /**
     * @ORM\Column(type="integer", nullable=true)
    
     */
    private $Code;



    /**
     * @ORM\Column( )
     */
    private $image;




    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->Age;
    }

    public function setAge(string $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->Code;
    }

    public function setCode(?int $Code): self
    {
        $this->Code = $Code;

        return $this;
    }



}
