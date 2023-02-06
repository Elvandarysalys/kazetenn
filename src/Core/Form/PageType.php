<?php

namespace Kazetenn\Core\Form;

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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Page $currentPage */
        $currentPage = $options['data'];
        $repository  = $options['repository'];
        $builder
            ->add('title', TextType::class, [
                'label' => 'page_title.label',
                'attr' =>[
                    'class' => 'input'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'page_url.label',
                'attr' =>[
                    'class' => 'input'
                ]
            ])
            ->add('parent', ChoiceType::class, [
                'choices' => self::buildTargetChoices($repository, $currentPage),
                'label'   => 'parent_page.label',
                'attr' =>[
                    'class' => 'input'
                ]
            ]);

        if (null !== $currentPage->getCreatedAt()) {
            $builder->add('blocks', CollectionType::class, [
                'entry_type'   => PageContentType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'label'        => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'repository' => PageRepository::class
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
