<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="carts")
 * @ORM\Entity(repositoryClass="Web\Bundle\ShopBundle\Entity\CartRepository")
 */
class Cart
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
     * @ORM\OneToMany(targetEntity="CartProduct", mappedBy="Cart")
     */
    protected $cartProducts;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="carts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->cartProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Add cartProducts
     *
     * @param \Web\Bundle\ShopBundle\Entity\CartProduct $cartProducts
     * @return Cart
     */
    public function addCartProduct(\Web\Bundle\ShopBundle\Entity\CartProduct $cartProducts)
    {
        $this->cartProducts[] = $cartProducts;
    
        return $this;
    }

    /**
     * Remove cartProducts
     *
     * @param \Web\Bundle\ShopBundle\Entity\CartProduct $cartProducts
     */
    public function removeCartProduct(\Web\Bundle\ShopBundle\Entity\CartProduct $cartProducts)
    {
        $this->cartProducts->removeElement($cartProducts);
    }

    /**
     * Get cartProducts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCartProducts()
    {
        return $this->cartProducts;
    }

    /**
     * Set user
     *
     * @param \Web\Bundle\ShopBundle\Entity\User $user
     * @return Cart
     */
    public function setUser(\Web\Bundle\ShopBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Web\Bundle\ShopBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}