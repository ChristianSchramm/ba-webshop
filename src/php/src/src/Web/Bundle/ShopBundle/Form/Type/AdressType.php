<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Web\Bundle\ShopBundle\Entity\Adress'
		));
	}
	

	public function getName()
	{
		return 'Adress';
	}
}