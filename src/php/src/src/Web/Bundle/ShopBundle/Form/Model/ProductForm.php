<?php 
namespace Web\Bundle\ShopBundle\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

use Web\Bundle\ShopBundle\Entity\Document;
use Web\Bundle\ShopBundle\Entity\Product;


class ProductForm
{
    /**
     * @Assert\Type(type="Web\Bundle\ShopBundle\Entity\Document")
     */
    protected $document;
    
    /**
     * @Assert\Type(type="Web\Bundle\ShopBundle\Entity\Product")
     */
    protected $product;
    

    
    

    public function setDocument(Document $document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }
    
    public function setProduct(Product $product)
    {
    	$this->product = $product;
    }
    
    public function getProduct()
    {
    	return $this->product;
    }

    
    



}