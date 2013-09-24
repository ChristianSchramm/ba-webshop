<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text', array( 
            'label'  => 'Titel',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('price', 'text', array( 
            'label'  => 'Preis',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('amount', 'text', array( 
            'label'  => 'Verfügbare Anzahl',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('type', 'entity', array(
				'class' => 'WebShopBundle:Type',
				'required' => true,
        'label'  => 'Type',
        'attr'   =>  array( 'class'   => 'input'),
				'label_attr' =>  array( 'class'   => 'label'),
		));
		$builder->add('genres', 'entity', array(
				'class' => 'WebShopBundle:Genre',
				'required' => false,
				'multiple' => true,
        'label'  => 'Genres',
        'attr'   =>  array( 'class'   => 'select'),
				'label_attr' =>  array( 'class'   => 'label'),
		));
		$builder->add('description', 'textarea', array( 
            'label'  => 'Beschreibung',
            'attr'   =>  array( 'class'   => 'textarea'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
		$builder->add('shipping', 'choice', array(
        'choices'   => array(
	        '2'   => '2 Tage',
	        '3' => '3 Tage',
	        '5'   => '5 Tage',
	        '7'   => '7 Tage',
	        '10'   => '10 Tage',
	        '14'   => '14 Tage'),
				'label'  => 'Versanddauer',
				'attr'   =>  array( 'class'   => 'input'),
				'label_attr' =>  array( 'class'   => 'label'),

    ));
		
		$builder->add('status', 'choice', array(
				'choices'   => array(
				  'New'   => 'Neu',
				  'Old' => 'Gebraucht'),
        'label'  => 'Zustand',
        'attr'   =>  array( 'class'   => 'input'),
				'label_attr' =>  array( 'class'   => 'label'),));

	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Web\Bundle\ShopBundle\Entity\Product'
		));
	}
	

	public function getName()
	{
		return 'Product';
	}
}