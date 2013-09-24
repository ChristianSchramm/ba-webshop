<?php 

namespace Web\Bundle\ShopBundle\Form\Type;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SecurityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('adress', new AdressType());
        $builder->add('security', new SecurityType());
        


    }

    public function getName()
    {
        return 'form';
    }
}