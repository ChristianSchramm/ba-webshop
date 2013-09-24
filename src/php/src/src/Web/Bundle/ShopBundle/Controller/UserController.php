<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Form\Type\SecurityFormType;

use Web\Bundle\ShopBundle\Form\Model\SecurityForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
	
	/**
	 * @Route("/admin/user/", name="admin_user")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$users = $em->getRepository('WebShopBundle:User')->findAll();
		 
		return array('users' => $users);
	}
	
	
	
	/**
	 * @Route("/admin/user/edit/{id}", name="admin_user_edit")
	 * @Template()
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->findOneById($id);
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
		
		$securityForm = new SecurityForm();
		$securityForm->setSecurity($user);
		$securityForm->setAdress($adress);
		$form = $this->createForm(new SecurityFormType(), $securityForm);
			
		return array('user' => $user,
				         'form' => $form->createView(),
				);
	}
	


	/**
	 * @Route("/admin/user/show/{id}", name="admin_user_show")
	 * @Template()
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->findOneById($id);
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
		
		$securityForm = new SecurityForm();
		$securityForm->setSecurity($user);
		$securityForm->setAdress($adress);
		$form = $this->createForm(new SecurityFormType(), $securityForm);
			
		return array('user' => $user,
				         'form' => $form->createView(),
				);
	}



	/**
	 * @Route("/admin/user/remove/{id}", name="admin_user_remove")
	 * @Template()
	 */
	public function removeAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->findOneById($id);
		

		$em->remove($user);
		$em->flush();
			

		return $this->redirect($this->generateUrl('admin_user'));
	}

}
