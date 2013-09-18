<?php

namespace Web\Bundle\ShopBundle\Controller;

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
		$user = $em->getRepository('WebShopBundle:User')->findOneById($is);
			
		return array('user' => $user);
	}
	


	/**
	 * @Route("/admin/user/show/{id}", name="admin_user_show")
	 * @Template()
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->findOneById($is);
			
		return array('user' => $user);
	}



	/**
	 * @Route("/admin/user/remove/{id}", name="admin_user_remove")
	 * @Template()
	 */
	public function removeAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('WebShopBundle:User')->findOneById($is);
			

		return $this->redirect($this->generateUrl('admin_user'));
	}

}
