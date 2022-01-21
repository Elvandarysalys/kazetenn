<?php

namespace Kazetenn\Core\Admin\Form;

use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $options['repository'];
        $builder
            ->add('title', TextType::class, [
                'label' => 'page_title.label'
            ])
            ->add('slug', TextType::class, [
                'label' => 'page_url.label'
            ])
            ->add('parent', ChoiceType::class, [
                'choices' => self::buildTargetChoices($repository),
                'label'   => 'parent_page.label',
            ])
            ->add('children', CollectionType::class, [
                'entry_type' => PageContentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'repository' => PageRepository::class
        ]);
    }

    private function buildTargetChoices(PageRepository $pageRepository)
    {
        $return = ['Aucune' => null];
        foreach ($pageRepository->findAll() as $page) {
            $return[$page->getSlug()] = $page;
        }

        return $return;
    }
}
