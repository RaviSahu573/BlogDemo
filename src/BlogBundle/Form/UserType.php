<?php



/*
 *
 */

namespace BlogBundle\Form;

use BlogBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = new User();
        $builder
            ->add('fullName', TextType::class)
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('roles', CollectionType::class, array(
                'entry_type'   => ChoiceType::class,
                'entry_options'  => array(
                    'choices'  => array(
                        'Admin' => 'ROLE_ADMIN',
                        'User'     => 'ROLE_USER',
                    ),
                ),
            ))
              /*->add('roles' , ChoiceType::class ,array(
                    'choices' => array(
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                        'SuperAdmin' => null,
                    ),
                    'choice_attr' => function($val, $key, $index) {
                        // adds a class like attending_yes, attending_no, etc
                        return ['class' => 'attending_'.strtolower($key)];
                    },
                ))*/
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}