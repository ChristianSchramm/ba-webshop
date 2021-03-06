<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GenreType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array( 
            'label'  => 'Genre',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
	}
	

	public function getName()
	{
		return 'Genre';
	}
}