<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text');
		$builder->add('price', 'text');
		$builder->add('amount', 'text');
		$builder->add('type', 'entity', array(
				'class' => 'WebShopBundle:Type',
				'required' => true,
		));
		$builder->add('genres', 'entity', array(
				'class' => 'WebShopBundle:Genre',
				'required' => false,
				'multiple' => true
		));
		$builder->add('description', 'textarea');
		$builder->add('shipping', 'choice', array(
    'choices'   => array(
        '2'   => '2 Tage',
        '3' => '3 Tage',
        '5'   => '5 Tage',
        '7'   => '7 Tage',
        '10'   => '10 Tage',
        '14'   => '14 Tage',
    ),));
		
		$builder->add('status', 'choice', array(
				'choices'   => array(
						'New'   => 'Neu',
						'Old' => 'Gebraucht',
				),));

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