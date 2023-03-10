<?php

namespace Kazetenn\Core\Form;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Service\BlockService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ContentBlockType extends AbstractType
{
    private array $typeChoices;

    public function __construct(protected BlockService $blockService)
    {
        foreach ($this->blockService->getTypes() as $type){
            $this->typeChoices[$type] = $type;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr'  => [
                    'class' => 'textarea block_text_area'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => $this->typeChoices,
                'label'   => 'bloc_type.label',
                'attr'    => [
                    'class' => 'input'
                ]
            ])
            ->add('blocOrder', IntegerType::class, [
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('align', ChoiceType::class, [
                'choices' => [
                    BaseBlockInterface::HORIZONTAL_ALIGN => 'horizontal',
                    BaseBlockInterface::VERTICAL_ALIGN   => 'vertical'
                ],
                'label'   => 'align.label',
                'attr'    => [
                    'class' => 'input'
                ]
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder){
            $form = $event->getForm();
            $data = $event->getData();

            /** @var ?BaseBlockInterface $data */
            if (null !== $data){
                $type = $data->getType();

                $contentForm = $form->get('content');

                foreach ($this->typeChoices as $typeChoice){
                    if ($type === $typeChoice){
                        $formInfos = $this->blockService->getFormInfos($type);


                        $form->remove('content');
                        $form->add('content', TextareaType::class, $formInfos);
//                        dump($formInfos, $contentForm);
                    }
                }
            }
        });
    }
}
