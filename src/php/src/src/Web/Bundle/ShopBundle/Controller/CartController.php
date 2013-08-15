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
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	 
    	return array('cart' => $cart);
    }
    
    
    
    /**
     * @Route("/cart/checkout/", name="cart_checkout")
     * @Template()
     */
    public function checkoutAction()
    {
    	$rechnungsNummer = "R-". (rand(100000,999999));
    	
    	
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
    	$pdf->Cell(30,10,utf8_decode('ScheibenBude, Buden Straße 123, Scheibenheim 01234'));

    	// anschrift
    	$pdf->Ln(15);
    	$pdf->SetFont('Arial','',10);
    	$pdf->Cell(30,10,utf8_decode($adress->getName()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Kunden-Nr.: "));
    	$pdf->Cell(30,10,utf8_decode($user->getNumber()));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getStreet()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Rechnungs-Nr.: "));
    	$pdf->Cell(30,10,utf8_decode($rechnungsNummer));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getPostcode(). " " . $adress->getLocation()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Datum.: "));
    	$pdf->Cell(30,10,utf8_decode(date("d.m.Y")));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getCountry()));

    	
    	// betreff

    	$pdf->Ln(30);
    	$pdf->SetFont('Arial','',12);
    	$pdf->Cell(30,10,'Rechnung');
    	
    	// waren
        // header
    	$pdf->Ln(10);
    	$pdf->SetFont('Arial','B',10);
    	$pdf->Cell(30,10,'Pos.','B');
    	$pdf->Cell(70,10,'Beschreibung','B');
    	$pdf->Cell(20,10,'Menge','B');
    	$pdf->Cell(30,10,'E-Preis','B', 0,'R');
    	$pdf->Cell(30,10,'Preis','B', 0,'R');
        //content
    	$pdf->SetFont('Arial','',10);
    	
      $summe = 0;
      $versand = 6.90;
    	$products = $cart->getCartProducts();
    	for ($i = 0 ; $i < $products->count(); $i++){
    		$prod = $products[$i];
    		
	    	$pdf->Ln(10);
	    	$pdf->Cell(30,10,$i+1);
	    	$pdf->Cell(70,10,utf8_decode(substr($prod->getProduct()->getTitle(),0,30)));
	    	$pdf->Cell(20,10,utf8_decode($prod->getAmount()));
	    	$pdf->Cell(30,10,utf8_decode(number_format($prod->getProduct()->getPrice(), 2, '.', ''). " EUR"), 0,0,'R');
	    	$pdf->Cell(30,10,utf8_decode(number_format($prod->getAmount() * $prod->getProduct()->getPrice() , 2, '.', ''). " EUR"), 0,0,'R');
	    	
	    	$summe += $prod->getAmount() * $prod->getProduct()->getPrice();
    	}
    	$pdf->Ln(1);
    	$pdf->Cell(180,10,'','B');
    	
    	// gesamtbetrag
    	$pdf->Ln(10);
    	$pdf->SetFont('Arial','',10);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Brutto Summe:");
    	$pdf->Cell(30,10,number_format($summe , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Versandkosten:");
    	$pdf->Cell(30,10,number_format($versand , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"inkl. 19.00% MwSt.:");
    	$pdf->Cell(30,10,number_format(($summe/119*19) , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->SetFont('Arial','B',10);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Gesamtbetrag:");
    	$pdf->Cell(30,10,number_format(($summe+$versand) , 2, '.', '')." EUR", 0, 0, 'R');
    	

    	// text
    	
    	$pdf->Ln(20);
    	$pdf->Cell(30,10,utf8_decode("Die Ware bleibt bis zur Bezahlung Eingemtum des Lieferanten."));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode("Zahlungsziel ist innerhalb von 14 Tagen nach erhalt der Rechnung."));
    	$pdf->Ln(10);
    	$pdf->Cell(30,10,utf8_decode("Vielen Dank für Ihren Auftrag!"));
    	
    	//footer
    	
    	$pdf->SetY(-25);
    	$pdf->SetFont('Arial','',8);
    	$pdf->Cell(30,0,utf8_decode('UmsatzStID: DE-13579123 - Bankverbindung: Budenbank - Kontonummer: 123456 - BLZ: 9876543'));
    	 
    	
    	$pdf->Output("uploads/rechnung_".$user->getNumber()."_".$rechnungsNummer.".pdf");
    
    	return $this->redirect($this->generateUrl('cart'));
    }
}
