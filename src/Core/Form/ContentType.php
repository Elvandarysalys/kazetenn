<?php

namespace Kazetenn\Core\Form;

use Kazetenn\Core\Entity\BaseContentInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?BaseContentInterface $content */
        $content = array_key_exists('data', $options) ? $options['data'] : null;

        $builder
            ->add('title', TextType::class, [
                'label' => 'content_title.label',
                'attr'  => [
                    'class' => 'input'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'content_url.label',
                'attr'  => [
                    'class' => 'input'
                ]
            ]);

        if (null !== $content && null !== $content->getCreatedAt()) {
            $builder->add('blocks', CollectionType::class, [
                'entry_type'   => ContentBlockType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'label'        => false,
            ]);
        } else {
            $builder->add('content_type', ChoiceType::class, [
                'choices' => [
                    'page'    => 'page',
                    'article' => 'article',
                ],
                'mapped'  => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'creation' => false
        ]);
    }
}
