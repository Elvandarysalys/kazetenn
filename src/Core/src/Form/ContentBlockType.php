<?php

namespace Kazetenn\Core\Form;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

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
                    BaseBlockInterface::HORIZONTAL_ALIGN => 'horizontal',
                    BaseBlockInterface::VERTICAL_ALIGN   => 'vertical'
                ],
                'label'   => 'align.label',
                'attr' =>[
                    'class' => 'input'
                ]
            ]);
    }
}
