<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="Web\Bundle\ShopBundle\Entity\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=255, nullable=true)
     */
    private $type;
    


    /**
     * @var string
     *
     * @ORM\Column(name="Genre", type="string", length=255, nullable=true)
     */
    private $genre;
    



    /**
     * @var string
     *
     * @ORM\Column(name="Stauts", type="string", length=255, nullable=true)
     */
    private $stauts;
    
    

    /**
     * @ORM\OneToMany(targetEntity="CartProduct", mappedBy="Product")
     */
    protected $cartProducts;
    
    

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
     * Set title
     *
     * @param string $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    
    /**
     * Add cartProducts
     *
     * @param \Web\Bundle\ShopBundle\Entity\CartProduct $cartProducts
     * @return Product
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
     * Set type
     *
     * @param string $type
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set genre
     *
     * @param string $genre
     * @return Product
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    
        return $this;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set stauts
     *
     * @param string $stauts
     * @return Product
     */
    public function setStauts($stauts)
    {
        $this->stauts = $stauts;
    
        return $this;
    }

    /**
     * Get stauts
     *
     * @return string 
     */
    public function getStauts()
    {
        return $this->stauts;
    }
}