<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Form\Type\GenreType;

use Web\Bundle\ShopBundle\Entity\Genre;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GenreController extends Controller
{
    /**
     * @Route("/admin/genre/", name="admin_genre")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	// Alle Genres laden
    	$genres = $em->getRepository('WebShopBundle:Genre')->findAll();
    	
    	// Genres an die View übergeben
    	return array('genres' => $genres);
    }
    
    /**
     * @Route("/admin/genre/new", name="admin_genre_new")
     * @Template()
     */
    public function newAction()
    {
    	// Neues Genre Formular initialisieren
      $form = $this->createForm(new GenreType(), new Genre());
			
      // Formular an View übergeben
		  return array('form' => $form->createView() );
    }

    /**
     * @Route("/admin/genre/create", name="admin_genre_create")
     * @Template()
     */
    public function createAction()
    {
			$em = $this->getDoctrine()->getManager();

			// Genre Form initialisieren
			$form = $this->createForm(new GenreType(), new Genre());
			
			// Formular aus Request abfangen
			$form->bind($this->getRequest());
			
			// Testen ob dasFormular valid ist
			if ($form->isValid()) {
				$genre = $form->getData();
				// Genre in Datenbnak speichern
				$em->persist($genre);
				$em->flush();
				// Weiterleitung zur Übersicht
				return $this->redirect($this->generateUrl('admin_genre'));
			}
			// Zurück mit Fehlermeldung
			return $this->render('WebShopBundle:Genre:new.html.twig',
					array('form' => $form->createView())
			);
    }
    
    /**
     * @Route("/admin/genre/remove/{id}", name="admin_genre_remove")
     */
    public function removeAction($id)
    {

    	$em = $this->getDoctrine()->getManager();
    	// Genre aus Datenbank laden
    	$genre = $em->getRepository('WebShopBundle:Genre')->findOneById($id);
    	
    	// Genre löschen
    	if ($genre){
    		$em->remove($genre);
    		$em->flush();
    	}
    	
    	// Weiterleitung zur Übersicht
			return $this->redirect($this->generateUrl('admin_genre'));
    }
    
}
