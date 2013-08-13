<?php

namespace Web\Bundle\ShopBundle\Controller;



use Web\Bundle\ShopBundle\Helper\FPDFHelper;

use Web\Bundle\ShopBundle\Entity\CartProduct;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CartController extends Controller
{
    /**
     * @Route("/cart/", name="cart")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$user = $this->get('security.context')->getToken()->getUser();
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	
    	return array('cart' => $cart);
    }

    /**
     * @Route("/cart/add/{id}/", name="cart_add")
     * @Template()
     */
    public function addAction($id)
    {
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	$em = $this->getDoctrine()->getManager();
    	$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	
    	// try find item
    	$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $id);

    	if (!is_null($cartProduct)){
    		$cartProduct->setAmount($cartProduct->getAmount()+1);
    	}else {
	    	$cartProduct = new CartProduct();
	    	$cartProduct->setCart($cart);
	    	$cartProduct->setAmount(1);
	    	$cartProduct->setProduct($product);
    	}

    	$em->persist($cartProduct);
    	$em->flush();

    	$url = $this->getRequest()->headers->get("referer");
    	if (empty($url)){
    		return $this->redirect($this->generateUrl('cart'));
    	}
    	return new RedirectResponse($url);
    }

    /**
     * @Route("/cart/del/{id}/", name="cart_del")
     * @Template()
     */
    public function removeAction($id)
    {
    	
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	$em = $this->getDoctrine()->getManager();
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	
    	// try find item
    	$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $id);


    	$em->remove($cartProduct);
    	$em->flush();
    	

    	return $this->redirect($this->generateUrl('cart'));
    }
    
    /**
     * @Route("/cart/order/", name="cart_order")
     * @Template()
     */
    public function orderAction()
    {
    	
    	$em = $this->getDoctrine()->getManager();
    	 
    	$user = $this->get('security.context')->getToken()->getUser();
    	$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	
      // header
    	$pdf = new FPDFHelper();
    	$pdf->AddPage();
    	$pdf->SetFont('Arial','B',16);
    	$pdf->Cell(80);
    	$pdf->Cell(30,10,'Rechnung');
    	
    	//adress
    	$pdf->Ln(20);
    	$pdf->SetFont('Arial','',8);
    	$pdf->Cell(30,10,utf8_decode('Scheiben-Stube, Stuben Straße 123, Stubenheim 01234'));
    	
    	$pdf->Ln(10);
    	$pdf->SetFont('Arial','',10);
    	$pdf->Cell(30,10,utf8_decode($adress->getName()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Kunden-Nr.: "));
    	$pdf->Cell(30,10,utf8_decode("U-000".$user->getId()));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getStreet()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Rechnungs-Nr.: "));
    	$pdf->Cell(30,10,utf8_decode("R-". (rand(100000,999999))));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getPostcode(). " " . $adress->getLocation()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Datum.: "));
    	$pdf->Cell(30,10,utf8_decode(date("d.m.Y")));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getCountry()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Seite.: "));
    	$pdf->Cell(30,10,utf8_decode("1"));
    	
    	
    	$pdf->Output("uploads/lol.pdf");
    
    	return $this->redirect($this->generateUrl('cart'));
    }
}
