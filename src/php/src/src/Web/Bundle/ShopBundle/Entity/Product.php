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
     * @ORM\Column(name="Status", type="string", length=255, nullable=true)
     */
    private $status;
    
    /**
     * @var float
     *
     * @ORM\Column(name="Price", type="float")
     */
    private $price;
    

    /**
     * @var float
     *
     * @ORM\Column(name="Description", type="text", nullable=true)
     */
    private $description;
    
    

    /**
     * @ORM\OneToMany(targetEntity="CartProduct", mappedBy="product")
     */
    protected $cartProducts;
    
    

    /**
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="products")
     * @ORM\JoinTable(name="product_genres")
     */
    private $genres;
    
    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="products")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;
    

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->cartProducts = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->genres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set status
     *
     * @param string $status
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add genres
     *
     * @param \Web\Bundle\ShopBundle\Entity\Genre $genres
     * @return Product
     */
    public function addGenre(\Web\Bundle\ShopBundle\Entity\Genre $genres)
    {
        $this->genres[] = $genres;
    
        return $this;
    }

    /**
     * Remove genres
     *
     * @param \Web\Bundle\ShopBundle\Entity\Genre $genres
     */
    public function removeGenre(\Web\Bundle\ShopBundle\Entity\Genre $genres)
    {
        $this->genres->removeElement($genres);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Set type
     *
     * @param \Web\Bundle\ShopBundle\Entity\Type $type
     * @return Product
     */
    public function setType(\Web\Bundle\ShopBundle\Entity\Type $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Web\Bundle\ShopBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }
}