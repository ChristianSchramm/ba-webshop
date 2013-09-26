<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Entity\Adress;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


use Web\Bundle\ShopBundle\Form\Type\AdressFormType;
use Web\Bundle\ShopBundle\Form\Type\AdressType;
use Web\Bundle\ShopBundle\Form\Model\AdressForm;

class AdressController extends Controller
{
	
	/**
	 * @Route("/account/adress/", name="account_adress")
	 * @Route("/account/", name="account")
	 * @Template()
	 */
	public function indexAction()
	{
		// User aus Session holen
		$user = $this->get('security.context')->getToken()->getUser();

		// Adresse aus der Datenbank mit der aktuellen UserId holen
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());

		// Formular initialisieren
		$form = $this->createForm(new AdressType(), $adress);
			
		// Formular an View übergeben
		return array('form' => $form->createView() );
	}
	
	/**
	 * @Route("/account/adress/edit/", name="account_adress_edit")
	 * @Template()
	 */
	public function editAction()
	{
		// User aus Session holen
		$user = $this->get('security.context')->getToken()->getUser();
		
		// Adresse aus der Datenbank mit der aktuellen UserId holen
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
			
		// Formular initialisieren
		$form = $this->createForm(new AdressType(), $adress);
			
		// Formular an View übergeben
		return array('form' => $form->createView() );
	}
	
	/**
	 * @Route("/account/adress/save/", name="account_adress_save")
	 * @Template()
	 */
	public function saveAction()
	{
		// User aus Session holen
		$user = $this->get('security.context')->getToken()->getUser();

		// Adresse aus der Datenbank mit der aktuellen UserId holen
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
		
		// Formular initialisieren
		$form = $this->createForm(new AdressType(), $adress);
		
		// Request auf das Formular binden
		$form->bind($this->getRequest());
		
		// Testen ob die Eingaben valid sind
		if ($form->isValid()) {
			
			// Daten abgreifen
			$adress = $form->getData();

			// Adresse des Users speichern
			$em->persist($adress);
			$em->flush();
			
			// Weiterleitung zur Übersicht
			return $this->redirect($this->generateUrl('account_adress'));
		}
		
		// Zurück mit Fehlerausgabe
		return $this->render('WebShopBundle:Adress:edit.html.twig',
				array('form' => $form->createView())
		);
		
	}
	
}
