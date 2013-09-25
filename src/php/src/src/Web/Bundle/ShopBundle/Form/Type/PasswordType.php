<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', 'text', array( 
            'label'  => 'Email',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
	}
	

	public function getName()
	{
		return 'password';
	}
}