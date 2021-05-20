<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *      * @Assert\Length(
 * min = 2,
 * max = 20,
 * minMessage = "Le nom d'un téléphone doit comporter au moins {{ limit }} caractères",
 * maxMessage = "Le nom d'un téléphone doit comporter au plus {{ limit }} caractères"
 * )
     */
    private $Nom;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     * @Assert\NotEqualTo(
 * value = 0,
 * message = "Le prix d’un téléphone ne doit pas être égal à 0 "
 * )

     */
    private $Prix;

    /**
     * @ORM\Column(type="text")
     
    

     */
    private $caracteristique;

    /**
     * @ORM\ManyToOne(targetEntity=Marque::class, inversedBy="phones")
     */
    private $marque;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getCaracteristique(): ?string
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(string $caracteristique): self
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }
}
