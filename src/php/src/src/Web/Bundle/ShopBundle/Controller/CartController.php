<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Entity\CartProduct;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CartController extends Controller
{
    /**
     * @Route("/cart", name="cart")
     * @Template()
     */
    public function indexAction()
    {
    	
    	return array();
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
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
}
