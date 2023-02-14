<?php

namespace Kazetenn\Articles\Form;

use Kazetenn\Articles\Entity\Article;
use Kazetenn\Articles\Repository\CategoryRepository;
use Kazetenn\Core\Form\ContentType;
use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Repository\PageRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends ContentType
{
    public function __construct(protected readonly CategoryRepository $categoryRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('categories', ChoiceType::class, [
                'choices' => self::buildTargetChoices($this->categoryRepository),
                'label'   => 'categories.label',
                'attr'    => [
                    'class' => 'input'
                ]
            ])
            ->add('blocks', CollectionType::class, [
                'entry_type'   => ArticleContentType::class,
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
            'data_class' => Article::class,
        ]);
    }

    private function buildTargetChoices(CategoryRepository $categoryRepository): array
    {
        $return = ['Aucune' => null];

        $datas = $categoryRepository->findAll();

        foreach ($datas as $category) {
            $return[$category->getSlug()] = $category->getId()->toRfc4122();
        }

        return $return;
    }
}
