<?php

namespace Kazetenn\Core\Form;

use Kazetenn\Pages\Entity\PageContent;
use Kazetenn\Pages\Repository\PageContentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $blocRepository = $options['bloc_repository'];

        $builder
            ->add('content')
            ->add('template')
            ->add('blocOrder')
            ->add('align', ChoiceType::class, [
                'choices' => [
                    PageContent::HORIZONTAL_ALIGN => 'horizontal',
                    PageContent::VERTICAL_ALIGN   => 'vertical'
                ],
                'label'   => 'align.label',
            ])
            ->add('childrens', CollectionType::class, [
                'entry_type' => PageContentType::class,
                'prototype'  => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => PageContent::class,
            'bloc_repository' => PageContentRepository::class
        ]);
    }
}
