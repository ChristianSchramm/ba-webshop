<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('file', 'file', array( 
            'label'  => 'Bilddatei',
				    'required' => false,
            'attr'   =>  array( 'class'   => 'file-input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Web\Bundle\ShopBundle\Entity\Document'
		));
	}
	

	public function getName()
	{
		return 'Document';
	}
}