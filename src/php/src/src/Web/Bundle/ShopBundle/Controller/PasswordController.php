<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Form\Type\PasswordType;

use Web\Bundle\ShopBundle\Helper\MiscHelper;

use Web\Bundle\ShopBundle\Entity\User;

use Web\Bundle\ShopBundle\Entity\Activation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Web\Bundle\ShopBundle\Form\Type\ProfileType;
use Web\Bundle\ShopBundle\Form\Model\Registration;

use Symfony\Component\Security\Core\SecurityContext;



class PasswordController extends Controller
{
	/**
	 * @Route("/password", name="reset_password")
	 * @Template()
	 */
	public  function indexAction(){
		// Password Formular initialisieren
		$form = $this->createForm(new PasswordType());
	
		// Formular an View übergeben
		return array('form' => $form->createView());
	}
	
	/**
	 * @Route("/password/new", name="reset_password_email")
	 * @Template()
	 */
	public  function newAction(){
		// PAssword Formular initialisieren
		$form = $this->createForm(new PasswordType());;
		$form->bind($this->getRequest());
		// Post request abgreifen
		$email = $form->getData();
	
		
		$em = $this->getDoctrine()->getManager();
		
		// Testen ob Email in der Datenbnk vorhanden ist
		$user = $em->getRepository('WebShopBundle:User')->findOneByEmail($email);
	
		// Teste ob der User nicht gesperrt ist
		$roles = $user->getRoles();
		if (!($user && $roles[0]->getName() != "ROLE_INACTIVE" && $roles[0]->getName() != "ROLE_BLOCK")){
			return $this->redirect($this->generateUrl('reset_password'));
		}
	
		// Ein aktivierungcode für ein neues Passwort generieren
		$activation = new Activation();
		$user->addActivation($activation);
		$activation->setUser($user);
	
		// Aktivierungscode in der Datenbank speichern
		$em->persist($activation);
		$em->persist($user);
		$em->flush();
	
		// Email mit Aktivieruncode an die Emailadresse versenden
		$message = \Swift_Message::newInstance()     // we create a new instance of the Swift_Message class
		->setSubject('Scheiben-Bude.de.vu')     // we configure the title
		->setFrom('notification@scheiben-bude.de.vu')     // we configure the sender
		->setTo($user->getEmail())     // we configure the recipient
		->setBody(
				$this->renderView('WebShopBundle:Password:new.email.html.twig',
						array( 'code' => $activation->getCode(), 'user' => $user)),
				'text/html'
		);
		$this->get('mailer')->send($message);     // then we send the message.
			
		// Ergebnis View rendern
		return array();
	}
	
	/**
	 * @Route("/password/{code}", name="reset_password_save")
	 * @Template()
	 */
	public  function createAction($code){
		$em = $this->getDoctrine()->getManager();
		// User mit dem Aktivieurngscode finden
		$user = $em->getRepository('WebShopBundle:User')->loadUserByActivateCode($code);
		 // 
		 
		// Testen ob der User in der Datenbank vorhanden ist
		if ($user){
			// set pw
			$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
			//Neues Passwort für den Benutzer erzuegen
			$helper = new MiscHelper();
			$pass = $helper->getRandString(8);
			$password = $encoder->encodePassword($pass, $user->getSalt());
	
			// Passwort verschlüsselt in der Datenbank speichern
			$user->setPassword($password);
	
			$em->persist($user);
			$em->flush();
	
			// Aktivierungscode löschen
			$activation = $em->getRepository('WebShopBundle:Activation')->findOneByCode($code);
			if ($activation){
				$em->remove($activation);
				$em->flush();
			}
	
	
			// Neues Passwort per Email versenden
			$message = \Swift_Message::newInstance()     // we create a new instance of the Swift_Message class
			->setSubject('Scheiben-Bude.de.vu')     // we configure the title
			->setFrom('notification@scheiben-bude.de.vu')     // we configure the sender
			->setTo($user->getEmail())     // we configure the recipient
			->setBody(
					$this->renderView('WebShopBundle:Password:create.email.html.twig',
							array( 'pass' => $pass, 'user' => $user)),
					'text/html'
			);
			$this->get('mailer')->send($message);     // then we send the message.
	
		}else {
			// Weiterleitung zur Sartseite
			return $this->redirect($this->generateUrl('default'));
		}
	
		return array();
	}

}