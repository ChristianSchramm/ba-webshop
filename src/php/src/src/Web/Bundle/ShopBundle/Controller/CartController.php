<?php

namespace Web\Bundle\ShopBundle\Controller;



use Doctrine\Common\Collections\ArrayCollection;

use Web\Bundle\ShopBundle\Entity\Product;

use Web\Bundle\ShopBundle\Entity\Bill;

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
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	if (is_object($user)){
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	}else {
    		$cart = $session->get('tmpCart');

    	}
    	

    	
    	
    	return array('cart' => $cart);
    }
    
    
    /**
     * @Template()
     */
    public function showCartAction()
    {
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	 
    	$user = $this->get('security.context')->getToken()->getUser();
    	 
    	if (is_object($user)){
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	}else {
    		$cart = $session->get('tmpCart');
    
    	}
    	
    	// berechne Warenkorb
    	$cartCount = 0;
    	$cartSum = 0;
    	if (!is_null($cart)){
    		foreach ($cart->getCartProducts() as $product){
    			$cartSum += $product->getAmount() * $product->getProduct()->getPrice();
    			$cartCount += $product->getAmount();
    		}
    	}
    	 
    	$cartSum = number_format($cartSum, 2, ',', ' ');

    	 
    	return array('cart' => $cart,
    			         'cartCount' => $cartCount,
      		         'cartSum' => $cartSum
    			);
    }

    /**
     * @Route("/cart/add/{id}/", name="cart_add")
     * @Template()
     */
    public function addAction($id)
    {
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	$user = $this->get('security.context')->getToken()->getUser();

    	$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);

    	if (is_object($user)){

    	  // logged user
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
    	  
    	}else {

    		
    		// anonym user
    		$user = $session->get("tmpUser");
    		$cart = $session->get("tmpCart");
    		
    		$exits = false;
    		
    		
    		foreach ($cart->getCartProducts() as $key => $value){
    			if (!is_null($value) && $value->getProduct()->getPubId() == $id ){
    				// incremtnt ammount
    				$cart->getCartProducts()->get($key)->setAmount($value->getAmount()+1);
    				$exits = true;
    			}
    		
    		}

    		
    		if (!$exits){
    			// new
    			$prod = new Product();
    			$prod->setPrice($product->getPrice());
    			$prod->setTitle($product->getTitle());
    			$prod->setPubId($product->getId()) ;
    			
    			
    			$cartProduct = new CartProduct();
    			$cartProduct->setCart($cart);
    			$cartProduct->setAmount(1);
    			$cartProduct->setProduct($prod);
    			
    			$cart->addCartProduct($cartProduct);
    		}
    		
    		
    		$session->set("tmpCart", $cart);

    	}


    	


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
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	
    	$user = $this->get('security.context')->getToken()->getUser();

    	if (is_object($user)){
	    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
	    	
	    	// try find item
	    	$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $id);
	    	$em->remove($cartProduct);
	    	$em->flush();
    	
    	}else {
    		$cart = $session->get("tmpCart");
    		
    		
    		foreach ($cart->getCartProducts() as $key => $value){
    			if (!is_null($value) && $value->getProduct()->getPubId() == $id ){
    				$cart->getCartProducts()->remove($key);
    			}
    			
    		}
    		$session->set("tmpCart", $cart);
    	}
    	

    	return $this->redirect($this->generateUrl('cart'));
    }
    
    /**
     * @Route("/cart/order/", name="cart_order")
     * @Template()
     */
    public function orderAction()
    {
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	 
    	$user = $this->get('security.context')->getToken()->getUser();
    	$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
    	
    	// warenkorb mit temp updaten
    	$tmpUser = $session->get('tmpUser');
    	$tmpCart = $session->get('tmpCart');
    	if (is_object($user) && $tmpUser != null && $tmpCart != null){
    		// warenkorb befüllen, wenn der user sich neu einloggt
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUserIdOverview($user);
    	
    		if ($tmpCart->getCartProducts()->count() > 0){
    			foreach ($tmpCart->getCartProducts() as $key => $value){
    				$product = $em->getRepository('WebShopBundle:Product')->findOneById($value->getProduct()->getPubId());
    	
    				$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $product->getId());
    					
    				if (!is_null($cartProduct)){
    					$cartProduct->setAmount($cartProduct->getAmount()+$value->getAmount());
    				}else {
    	
    					$cartProduct = new CartProduct();
    					$cartProduct->setCart($cart);
    					$cartProduct->setAmount($value->getAmount());
    					$cartProduct->setProduct($product);
    				}
    	
    				$em->persist($cartProduct);
    				$em->flush();
    			}
    			 
    	
    		}

    		$session->set('tmpUser', null);
    		$session->set('tmpCart', null);
    	}else{
    		$cart = $session->get('tmpCart');
    	}
    	
    	
    	
    	
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	 
    	return array('cart' => $cart, 'adress' => $adress);
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
    	
    	$products = $cart->getCartProducts();
    	
    	if ($products->count() == 0) {
    		return $this->redirect($this->generateUrl('cart'));
    	}
    	
      // header
    	$pdf = new FPDFHelper();
    	$pdf->AddPage();
    	$pdf->SetFont('Arial','B',16);
    	$pdf->Cell(80);
    	$pdf->Cell(30,10,'Rechnung');
    	
    	//adress
    	$pdf->Ln(20);
    	$pdf->SetFont('Arial','',8);
    	$pdf->Cell(30,10,utf8_decode('ScheibenBude, Buden Straße 123, 01234 Scheibenheim'));

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
    	$pdf->Cell(30,10,"Netto Summe:");
    	$pdf->Cell(30,10,number_format($summe/1.19 , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"zzgl. 19.00% MwSt.:");
    	$pdf->Cell(30,10,number_format(($summe/119*19) , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Versandkosten:");
    	$pdf->Cell(30,10,number_format($versand , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->SetFont('Arial','B',10);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Gesamtbetrag:");
    	$pdf->Cell(30,10,number_format(($summe+$versand) , 2, '.', '')." EUR", 0, 0, 'R');
    	

    	// text
    	
    	$pdf->Ln(20);
    	$pdf->Cell(30,10,utf8_decode("Die Ware bleibt bis zur vollständigen Bezahlung Eingemtum des Verkäufers."));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode("Zahlungsziel ist innerhalb von 14 Tagen nach Erhalt der Rechnung."));
    	$pdf->Ln(10);
    	$pdf->Cell(30,10,utf8_decode("Vielen Dank für Ihren Auftrag!"));
    	
    	//footer
    	
    	$pdf->SetY(-25);
    	$pdf->SetFont('Arial','',8);
    	$pdf->Cell(30,0,utf8_decode('USt-ID: DE-13579123 - Bankverbindung: Budenbank - Kontonummer: 123456 - BLZ: 9876543'));
    	 
    	
    	$fileName = "rechnung_".$user->getNumber()."_".$rechnungsNummer.".pdf";
    	
    	$pdf->Output("uploads/bills/".$fileName);
    	
    	$bill = new Bill();
    	$bill->setPath($fileName);
    	$bill->setUser($user);
    	$bill->setKundennummer($user->getNumber());
    	$bill->setRechnungsnummer($rechnungsNummer);
    	
    	$em->persist($bill);
    	
    	// amount verringern
    	for ($i = 0 ; $i < $products->count(); $i++){
    		// TODO
    	}
    	
    	// clear cart
    	
    	$products = $cart->getCartProducts();
    	for ($i = 0 ; $i < $products->count(); $i++){
    		$em->remove($products[$i]);
    	}
    	
    	
    	$em->flush();
    	
    	$message = \Swift_Message::newInstance()     // we create a new instance of the Swift_Message class
    	->setSubject('Scheiben-Bude.de.vu')     // we configure the title
    	->setFrom('notification@scheiben-bude.de.vu')     // we configure the sender
    	->setTo($user->getEmail())     // we configure the recipient
    	->setBody(
    			$this->renderView('WebShopBundle:Cart:checkout.email.html.twig',
    					array( 'user' => $user)),
    			'text/html'
    	)
    	->attach(\Swift_Attachment::fromPath("uploads/bills/".$fileName))
    	// and we pass the $name variable to the text template which serves as a body of the message
    	;
    	$this->get('mailer')->send($message);     // then we send the message.
    	
    	
    	
    	
    
    	return array('bill' => $bill);
    }
}
