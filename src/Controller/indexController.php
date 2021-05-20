<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Phone;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\PhoneType;
use App\Entity\Marque;
use App\Form\MarqueType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Entity\BrandSearch;
use App\Form\BrandSearchType;
use App\Form\PriceSearchType;
use App\Entity\PriceSearch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class indexController extends AbstractController
{
/**
 *@Route("/",name="phone_list")
 */
public function home(Request $request)
 {
 $propertySearch = new PropertySearch();
 $form = $this->createForm(PropertySearchType::class,$propertySearch);
 $form->handleRequest($request);
 //initialement le tableau des téléphones est vide,
 //c.a.d on affiche les téléphones que lorsque l'utilisateur
 //clique sur le bouton rechercher
 $téléphones= [];

 if($form->isSubmitted() && $form->isValid()) {
 //on récupère le nom de téléphone tapé dans le formulaire
 $nom = $propertySearch->getNom();
 if ($nom!="")
 //si on a fourni un nom de téléphone on affiche tous les téléphones ayant ce nom
 $téléphones= $this->getDoctrine()->getRepository(Phone::class)->findBy(['Nom' => $nom] );
 else
 //si si aucun nom n'est fourni on affiche tous les téléphones  
 $téléphones= $this->getDoctrine()->getRepository(Phone::class)->findAll();
 }
 return $this->render('téléphones/index.html.twig',[ 'form' =>$form->createView(), 'téléphones' => $téléphones]);
 }



/**
 * @Route("/phone/save")
 */
public function save() {
    $entityManager = $this->getDoctrine()->getManager();
    $téléphone = new Phone();
    $téléphone->setNom('téléphone 1');
    $téléphone->setPrix(1000);
    $téléphone->setCaracteristique('text 1');
   
    $entityManager->persist($téléphone);
    $entityManager->flush();
    return new Response('Article enregisté avec id '.$téléphone->getId());
    }





/**
 * @IsGranted("ROLE_EDITOR")
 * @Route("/phone/new", name="new_phone")
 * Method({"GET", "POST"})
 */
public function new(Request $request) {
    $téléphone = new Phone();
    $form = $this->createForm(PhoneType::class,$téléphone);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $téléphone = $form->getData();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($téléphone);
    $entityManager->flush();
    return $this->redirectToRoute('phone_list');
    }
    return $this->render('téléphones/new.html.twig',['form' => $form->createView()]);
    }
   

/**
 * @Route("/phone/{id}", name="phone_show")
 */
public function show($id) {
    $téléphone = $this->getDoctrine()->getRepository(Phone::class)->find($id);
    return $this->render('téléphones/show.html.twig',
    array('téléphone' => $téléphone));
     }


/**
 * @IsGranted("ROLE_EDITOR")
 * @Route("/phone/edit/{id}", name="edit_phone")
 * Method({"GET", "POST"})
 */
public function edit(Request $request, $id) {
    $téléphone = new Phone();
   $téléphone = $this->getDoctrine()->getRepository(Phone::class)->find($id);
   
    $form = $this->createForm(PhoneType::class,$téléphone);
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
   
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();
   
    return $this->redirectToRoute('phone_list');
    }
   
    return $this->render('téléphones/edit.html.twig', ['form' =>$form->createView()]);
    }

 /**
 * @IsGranted("ROLE_EDITOR")
 * @Route("/phone/delete/{id}",name="delete_phone")
 * @Method({"DELETE"})
*/

 public function delete(Request $request, $id) {
    $téléphone = $this->getDoctrine()->getRepository(Phone::class)->find($id);
   
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($téléphone);
    $entityManager->flush();
   
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('phone_list');
    }
   

    /**
 * @Route("/brand/newBrand", name="new_brand")
 * Method({"GET", "POST"})
 */
 public function newCategory(Request $request) {
    $marque = new Marque();
    $form = $this->createForm(MarqueType::class,$marque);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $téléphone = $form->getData();
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($marque);
    $entityManager->flush();
    }
   return $this->render('téléphones/newbrand.html.twig',['form'=>$form->createView()]);
    }


/**
 * @Route("/tel_mar/", name="téléphone_par_marque")
 * Method({"GET", "POST"})
 */
public function telephoneparMarque(Request $request) {
    $brandsearch = new BrandSearch();
    $form = $this->createForm(BrandSearchType::class,$brandsearch);
    $form->handleRequest($request);
    $téléphones= [];
    if($form->isSubmitted() && $form->isValid()) {
        $marque = $brandsearch->getMarque();
        
        if ($marque!="")
       $téléphones= $marque->getPhones();
        else
        $téléphones= $this->getDoctrine()->getRepository(Phone::class)->findAll();
        }
       
        return $this->render('téléphones/téléphoneparMarque.html.twig',['form' => $form->createView(),'téléphones' => $téléphones]);
        }

 /**
 * @Route("/tel_prix/", name="telephone_par_prix")
 * Method({"GET"})
 */
public function articlesParPrix(Request $request)
{

$priceSearch = new PriceSearch();
$form = $this->createForm(PriceSearchType::class,$priceSearch);
$form->handleRequest($request);
$téléphones= [];
if($form->isSubmitted() && $form->isValid()) {
$minPrice = $priceSearch->getMinPrice();
$maxPrice = $priceSearch->getMaxPrice();

$téléphones= $this->getDoctrine()->
getRepository(Phone::class)->findByPriceRange($minPrice,$maxPrice);
}
return $this->render('téléphones/telparPrix.html.twig',[ 'form' =>$form->createView(), 'téléphones' => $téléphones]);
}


}
