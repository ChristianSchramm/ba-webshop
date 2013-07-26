<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdressType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text');
		$builder->add('street', 'text');
		$builder->add('postcode', 'text');
		$builder->add('location', 'text');
		$builder->add('country', 'text');
	}
	

	public function getName()
	{
		return 'Adress';
	}
}