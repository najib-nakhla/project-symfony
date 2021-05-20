<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
class BrandSearch
{
 /**
 * @ORM\ManyToOne(targetEntity="App\Entity\Category")
 */
 private $marque;
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