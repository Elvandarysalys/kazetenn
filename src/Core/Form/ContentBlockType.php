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

class ContentBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class,[
                'label' => false,
                'attr' =>[
                    'class' => 'textarea block_text_area'
                ]
            ])
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
    }
}
