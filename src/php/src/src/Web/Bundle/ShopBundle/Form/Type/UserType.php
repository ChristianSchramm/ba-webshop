<?php 

namespace Web\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array( 
            'label'  => 'Email',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
        $builder->add('username', 'text', array( 
            'label'  => 'Benutzername',
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label'),
            )
        );
        $builder->add('password', 'repeated', array(
           'first_name' => 'password',
           'second_name' => 'confirm',
           'type' => 'password',
        	 'first_options' => array(
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label')),
        	 'second_options' => array(
            'attr'   =>  array( 'class'   => 'input'),
				    'label_attr' =>  array( 'class'   => 'label')),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Web\Bundle\ShopBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}