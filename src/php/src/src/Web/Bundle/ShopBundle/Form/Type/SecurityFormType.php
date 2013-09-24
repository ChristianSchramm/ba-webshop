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
        /*$builder->add('role', 'entity', array(
				    'class' => 'WebShopBundle:Role',
				    'property' => 'name',
        		'attr'   =>  array( 'class'   => 'select'),
        		'label_attr' =>  array( 'class'   => 'label'),
       
        )
        );
*/        


    }

    public function getName()
    {
        return 'form';
    }
}