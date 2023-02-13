<?php

namespace Kazetenn\Pages\Form;

use Kazetenn\Core\Form\ContentType;
use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends ContentType
{
    public function __construct(protected readonly PageRepository $pageRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /** @var Page $currentPage */
        $currentPage = array_key_exists('data', $options) ? $options['data'] : null;

        $builder
            ->add('parent', ChoiceType::class, [
                'choices' => self::buildTargetChoices($this->pageRepository, $currentPage),
                'label'   => 'parent_page.label',
                'attr'    => [
                    'class' => 'input'
                ]
            ])
            ->add('blocks', CollectionType::class, [
                'entry_type'   => PageContentType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'label'        => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }

    private function buildTargetChoices(PageRepository $pageRepository, ?Page $currentPage = null): array
    {
        $return = ['Aucune' => null];

        $datas = $pageRepository->createQueryBuilder('page')
                                ->where('page.id != :currentPage')
                                ->setParameter('currentPage', $currentPage->getId(), 'uuid')
                                ->getQuery()->getResult();

        /** @var Page $page */
        foreach ($datas as $page) {
            $return[$page->getSlug()] = $page->getId()->toRfc4122();
        }

        return $return;
    }
}
