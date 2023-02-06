<?php

namespace Kazetenn\Core\Form;

use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Entity\PageContent;
use Kazetenn\Pages\Repository\PageContentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var PageContentRepository $blocRepository */
        $blocRepository = $options['bloc_repository'];

        $builder
            ->add('content', TextareaType::class,[
                'label' => false,
                'attr' =>[
                    'class' => 'textarea block_text_area'
                ]
            ])
//            ->add('template', HiddenType::class)
//            ->add('parent', EntityType::class, [
//                'class' => PageContent::class,
//                'choice_label' => 'id'
//            ])
            ->add('blocOrder', IntegerType::class, [
                'attr' =>[
                    'class' => 'input'
                ]
            ])
            ->add('align', ChoiceType::class, [
                'choices' => [
                    PageContent::HORIZONTAL_ALIGN => 'horizontal',
                    PageContent::VERTICAL_ALIGN   => 'vertical'
                ],
                'label'   => 'align.label',
                'attr' =>[
                    'class' => 'input'
                ]
            ]);
//            ->add('children', CollectionType::class, [
//                'entry_type' => PageContentType::class,
//                'prototype'  => true,
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => PageContent::class,
            'bloc_repository' => PageContentRepository::class
        ]);
    }
}
