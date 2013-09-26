<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Form\Type\AccountType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



class AccountController extends Controller
{
	/**
	 * @Route("/account/password/", name="account_password")
	 * @Template()
	 */
	public function passwordAction()
	{
		// Formular erstellen
		$form = $this->createForm(new AccountType());
			
		//Formular an die View übergeben
		return array('form' => $form->createView() );
	}
	

	/**
	 * @Route("/account/password/save", name="account_password_save")
	 * @Template()
	 */
	public function saveAction()
	{
		// User Session 
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
				
		// Formular initialisieren
		$form = $this->createForm(new AccountType());
		$form->bind($this->getRequest());
		
		// Speichern, wenn das Formular Valid ist
		if ($form->isValid()) {
			$pass = $form->getData();
		
			// Adresse in die Datenbank speichern
			$em->persist($adress);
			$em->flush();
			
			// Weiterleitung zur Übersicht 
			return $this->redirect($this->generateUrl('account_adress'));
		}
		// Zurück mit Fehlerausgabe
		return $this->render('WebShopBundle:Account:password.html.twig',
				array('form' => $form->createView())
		);
	}

}
