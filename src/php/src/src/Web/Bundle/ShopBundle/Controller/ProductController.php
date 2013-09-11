<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Form\Model\ProductForm;
use Web\Bundle\ShopBundle\Form\Type\ProductFormType;
use Web\Bundle\ShopBundle\Entity\Product;
use Web\Bundle\ShopBundle\Form\Type\ProductType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
			
		return array('form' => $form->createView(), 'image' => null);

	}
	
	/**
	 * @Route("/admin/product/remove/{id}", name="admin_product_remove")
	 * @Template()
	 */
	public function removeAction($id)
	{
			
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
		
		$em->remove($product);
		$em->flush();
			
		return $this->redirect($this->generateUrl('admin_product'));
	
	}
	
	/**
	 * @Route("/admin/product/edit/{id}", name="admin_product_edit")
	 * @Template()
	 */
	public function editAction($id)
	{
			
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
		$image = $product->getImage();
                if (is_object($image)){
		  $image->setPath($product->getImage()->getPath()); 
                }
	
    $productForm = new ProductForm();
    $productForm->setProduct($product);
		$form = $this->createForm(new ProductFormType(), $productForm);
			
		return array( 'form' => $form->createView(),
				          'id' => $product->getId(),
				          'image' => $image );
	
	}
	
	/**
	 * @Route("/admin/product/create", name="admin_product_create")
	 * @Template()
	 */
	public function createAction()
	{
		$em = $this->getDoctrine()->getManager();			
		$form = $this->createForm(new ProductFormType(), new ProductForm());
		
		$form->bind($this->getRequest());
		
		if ($form->isValid()) {
			$formData = $form->getData();
			
			$document = $formData->getDocument();
			$product = $formData->getProduct();
			
			$document->upload();
			
			$product->setImage($document);
			
			$em->persist($document);
			$em->persist($product);

			$em->flush();
		
			return $this->redirect($this->generateUrl('admin_product'));
		}
		return $this->render('WebShopBundle:Product:new.html.twig',
				array('form' => $form->createView())
		);
	
	}
	
	/**
	 * @Route("/admin/product/save/{id}", name="admin_product_save")
	 * @Template()
	 */
	public function saveAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
	
    $productForm = new ProductForm();
    $productForm->setProduct($product);
		$form = $this->createForm(new ProductFormType(), $productForm);
		
		$form->bind($this->getRequest());
		
		if ($form->isValid()) {
			$formData = $form->getData();
				
			$product = $formData->getProduct();
			
			$document = $formData->getDocument();
			if ($document->getFile() != ""){
  
				$document->upload();
				$product->setImage($document);
				$em->persist($document);
			}
			$em->persist($product);
		
			$em->flush();
		
			return $this->redirect($this->generateUrl('admin_product'));
		}
		return $this->render('WebShopBundle:Product:edit.html.twig',
				array('form' => $form->createView())
		);
	
	}
}
