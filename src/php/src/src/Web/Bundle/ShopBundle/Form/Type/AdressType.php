<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdressType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array( 
            'label'  => 'Name',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('street', 'text', array( 
            'label'  => 'Straße',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('postcode', 'text', array( 
            'label'  => 'Postleitzahl',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('location', 'text', array( 
            'label'  => 'Wohnort',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('country', 'text', array( 
            'label'  => 'Nation',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
	}
	

	public function getName()
	{
		return 'Adress';
	}
}