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

			
			$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
			//Neues Passwort für den Benutzer erzuegen
			$password = $encoder->encodePassword($pass['password'], $user->getSalt());
	
			// Passwort verschlüsselt in der Datenbank speichern
			$user->setPassword($password);
		
			// Adresse in die Datenbank speichern
			$em->persist($user);
			$em->flush();
			
			// Weiterleitung zur Übersicht 
			return $this->redirect($this->generateUrl('account_password_ok'));
		}
		// Zurück mit Fehlerausgabe
		return $this->render('WebShopBundle:Account:password.html.twig',
				array('form' => $form->createView())
		);
	}
	
	/**
	 * @Route("/account/password/ok", name="account_password_ok")
	 * @Template()
	 */
	public function okAction()
	{		
		return array( );
	}
	

	/**
	 * @Route("/account/account", name="account_account")
	 * @Template()
	 */
	public function accountAction()
	{
		return array( );
	}
	

	/**
	 * @Route("/account/delete", name="account_account_delete")
	 * @Template()
	 */
	public function deleteAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		
		$role = $em->getRepository('WebShopBundle:Role')->findOneByName('ROLE_DELETED');
		
		$oldRole = $user->getRoles();
		$user->removeRole($oldRole[0]);
		$user->addRole($role);
		
		$em->persist($user);
		$em->flush();
		return $this->redirect($this->generateUrl('logout'));
	}

}
