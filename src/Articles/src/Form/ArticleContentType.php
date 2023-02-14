<?php

namespace Kazetenn\Articles\Form;

use Kazetenn\Core\Form\ContentBlockType;
use Kazetenn\Pages\Entity\PageContent;
use Kazetenn\Pages\Repository\PageContentRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleContentType extends ContentBlockType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => PageContent::class,
            'bloc_repository' => PageContentRepository::class
        ]);
    }
}
