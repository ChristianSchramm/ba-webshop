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
		$form = $this->createForm(new PasswordType());
	
		return array('form' => $form->createView());
	}
	
	/**
	 * @Route("/password/new", name="reset_password_email")
	 * @Template()
	 */
	public  function newAction(){
		$form = $this->createForm(new PasswordType());;
		$form->bind($this->getRequest());
		$email = $form->getData();
	
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->findOneByEmail($email);
	
		//check user
		$roles = $user->getRoles();
		if (!($user && $roles[0]->getName() != "ROLE_INACTIVE" && $roles[0]->getName() != "ROLE_BLOCK")){
			return $this->redirect($this->generateUrl('reset_password'));
		}
	
		// create new activation
		$activation = new Activation();
		$user->addActivation($activation);
		$activation->setUser($user);
	
		$em->persist($activation);
		$em->persist($user);
		$em->flush();
	
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
			
		return array();
	}
	
	/**
	 * @Route("/password/{code}", name="reset_password_save")
	 * @Template()
	 */
	public  function createAction($code){
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->loadUserByActivateCode($code);
		 
		if ($user){
			// set pw
			$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
			//encode password using current encoder
			$helper = new MiscHelper();
			$pass = $helper->getRandString(8);
			$password = $encoder->encodePassword($pass, $user->getSalt());
	
			//set encrypted password
	
			$user->setPassword($password);
	
			$em->persist($user);
			$em->flush();
	
			// activation code löschen
			$activation = $em->getRepository('WebShopBundle:Activation')->findOneByCode($code);
			if ($activation){
				$em->remove($activation);
				$em->flush();
			}
	
	
			// neues password versenden
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
	
			// login
	
	
	
		}else {
			return $this->redirect($this->generateUrl('default'));
		}
	
		return array();
	}

}