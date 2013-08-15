<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartProduct
 *
 * @ORM\Table(name="cart_products")
 * @ORM\Entity(repositoryClass="Web\Bundle\ShopBundle\Entity\CartProductRepository")
 */
class CartProduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;
    
    

    /**
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="cartProducts")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    protected $cart;
    
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="cartProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $product;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return CartProduct
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set cart
     *
     * @param \Web\Bundle\ShopBundle\Entity\Cart $cart
     * @return CartProduct
     */
    public function setCart(\Web\Bundle\ShopBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;
    
        return $this;
    }

    /**
     * Get cart
     *
     * @return \Web\Bundle\ShopBundle\Entity\Cart 
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set product
     *
     * @param \Web\Bundle\ShopBundle\Entity\Product $product
     * @return CartProduct
     */
    public function setProduct(\Web\Bundle\ShopBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \Web\Bundle\ShopBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}