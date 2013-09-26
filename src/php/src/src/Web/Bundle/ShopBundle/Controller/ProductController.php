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
	 * @Route("/admin/", name="admin")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		// Alle Produkte aus der Datenban laden
		$products = $em->getRepository('WebShopBundle:Product')->findAll();
		// Produkte an die View übergeben
		return array('products' => $products);
	}
	
	
	/**
	 * @Route("/admin/product/new", name="admin_product_new")
	 * @Template()
	 */
	public function newAction()
	{		
		// Formular initialisieren
		$form = $this->createForm(new ProductFormType(), new ProductForm());
		// Formular an die View übergeben
		return array('form' => $form->createView(), 'image' => null);

	}
	
	/**
	 * @Route("/admin/product/remove/{id}", name="admin_product_remove")
	 * @Template()
	 */
	public function removeAction($id)
	{
			
		$em = $this->getDoctrine()->getManager();
		// Produkt aus der Dtenbank laden
		$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
		
		// Produkt löschen und alle Referenzen
		$em->remove($product);
		$em->flush();
		// Zurück zur Übersicht
		return $this->redirect($this->generateUrl('admin_product'));
	
	}
	
	/**
	 * @Route("/admin/product/edit/{id}", name="admin_product_edit")
	 * @Template()
	 */
	public function editAction($id)
	{
			
		$em = $this->getDoctrine()->getManager();
		// Proukt aus Datenbnak laden		
		$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
		$image = $product->getImage();
                if (is_object($image)){
		  $image->setPath($product->getImage()->getPath()); 
                }
	
    // Formular initialisieren
    $productForm = new ProductForm();
    $productForm->setProduct($product);
    
		$form = $this->createForm(new ProductFormType(), $productForm);

		// Formular und Daten an View übergeben
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
		// Formular initialisieren
		$form = $this->createForm(new ProductFormType(), new ProductForm());
		// Request abgreifen
		$form->bind($this->getRequest());
		
		// Testen ob Formular valid ist
		if ($form->isValid()) {
			$formData = $form->getData();
			
			// Produkt und Bild anlegen
			$document = $formData->getDocument();
			$product = $formData->getProduct();
			// Bild speichern
			$document->upload();
			
			$product->setImage($document);
			// Bild und Produkt in die Datenbak speichenr
			$em->persist($document);
			$em->persist($product);

			$em->flush();
		  // Weiterleitung zu rÜbersicht
			return $this->redirect($this->generateUrl('admin_product'));
		}
		// Zurück mit Fehlermeldung
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
		// Produkt aus Datenbank laden
		$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
	
		// Formular initialisieren
    $productForm = new ProductForm();
    $productForm->setProduct($product);
		$form = $this->createForm(new ProductFormType(), $productForm);
		
		$form->bind($this->getRequest());

		// Testen ob Formular valid ist
		if ($form->isValid()) {
			$formData = $form->getData();
				
			$product = $formData->getProduct();
			// Aktualisierte Attribute speichern
			$document = $formData->getDocument();
			if ($document->getFile() != ""){
  
				$document->upload();
				$product->setImage($document);
				$em->persist($document);
			}
			
			// Proukt und vl neues Bild in die Datenbanks chreiben
			$em->persist($product);
		
			$em->flush();
		  // Weiterleitung zur Übersicht
			return $this->redirect($this->generateUrl('admin_product'));
		}
		// Zurück zur Fehlermeldung
		return $this->render('WebShopBundle:Product:edit.html.twig',
				array('form' => $form->createView())
		);
	
	}
}
