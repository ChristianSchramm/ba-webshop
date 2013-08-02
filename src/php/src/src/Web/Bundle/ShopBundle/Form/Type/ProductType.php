<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text');
		$builder->add('price', 'text');
		$builder->add('genre', 'entity', array(
				'class' => 'WebShopBundle:Genre',
				'required' => false,
		));

	}
	

	public function getName()
	{
		return 'Product';
	}
}