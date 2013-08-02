<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Form\Model\ProductForm;

use Web\Bundle\ShopBundle\Form\Type\ProductFormType;

use Web\Bundle\ShopBundle\Entity\Product;

use Web\Bundle\ShopBundle\Form\Type\ProductType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductController extends Controller
{
	/**
	 * @Route("/admin/product/", name="admin_product")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('WebShopBundle:Product')->findAll();
		 
		return array('products' => $products);
	}
	
	
	/**
	 * @Route("/admin/product/new", name="admin_product_new")
	 * @Template()
	 */
	public function newAction()
	{		
			
		$form = $this->createForm(new ProductFormType(), new ProductForm());
			
		return array('form' => $form->createView() );

	}
}
