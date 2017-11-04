<?php

/*
 *
 */

namespace BlogBundle\Form;

use BlogBundle\Entity\Post;
use BlogBundle\Form\Type\DateTimePickerType;
use BlogBundle\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Defines the form used to create and manipulate blog posts.
 *
 * @author Ravi Sahu
 */
class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('title', null, [
                'attr' => ['autofocus' => true],
                'label' => 'Title',
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Summary',
            ])
            ->add('content', null, [
                'attr' => ['rows' => 20],
                'label' => 'Content',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
