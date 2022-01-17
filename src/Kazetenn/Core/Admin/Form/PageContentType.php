<?php

namespace Kazetenn\Kazetenn\Core\Admin\Form;

use Kazetenn\Kazetenn\Core\Admin\Entity\Page;
use Kazetenn\Kazetenn\Core\Admin\Entity\PageContent;
use Kazetenn\Repository\PageContentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $blocRepository = $options['bloc_repository'];

        $parentChoices = [];
        if (array_key_exists('data', $options)){
            $parentChoices = self::buildParentChoices($options['data'], $blocRepository);
        }

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
            ->add('parent', ChoiceType::class, [
                'choices' => $parentChoices,
                'label'   => 'parent_bloc.label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => PageContent::class,
            'bloc_repository' => PageContentRepository::class
        ]);
    }

    private function buildParentChoices(Page $page, PageContentRepository $pageContentRepository)
    {
        $return = ['Aucune' => null];
        foreach ($pageContentRepository->findBy(['page' => $page]) as $bloc) {
            $return[(string)$bloc->getId()] = $bloc;
        }

        return $return;
    }
}
