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

		$form = $this->createForm(new AccountType());
			
		return array('form' => $form->createView() );
	}
	

	/**
	 * @Route("/account/password/save", name="account_password_save")
	 * @Template()
	 */
	public function saveAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();
		
		
		$form = $this->createForm(new AccountType());
		$form->bind($this->getRequest());
		
		if ($form->isValid()) {
			$pass = $form->getData();
		
			$em->persist($adress);
			$em->flush();
			return $this->redirect($this->generateUrl('account_adress'));
		}
		return $this->render('WebShopBundle:Account:password.html.twig',
				array('form' => $form->createView())
		);
			

	}

}
