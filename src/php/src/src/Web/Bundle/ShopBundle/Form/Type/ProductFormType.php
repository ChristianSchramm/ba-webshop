<?php 

namespace Web\Bundle\ShopBundle\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product', new ProductType(), array('label' => false));
        $builder->add('document', new DocumentType(), array('label' => false));
        


    }

    public function getName()
    {
        return 'form';
    }
}