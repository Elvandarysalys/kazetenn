<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Pages\Controller;

use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Model\ContentInterface;
use Kazetenn\Pages\Entity\Page;
use Kazetenn\Pages\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController implements ContentInterface
{
    public function getFormType(): string
    {
        return PageType::class;
    }

    public function getNewContent(): BaseContentInterface{
        return new Page();
    }

    public function getContentName(): string{
        return 'page';
    }

    public function getContentClass(): string
    {
        return Page::class;
    }

    public function getContentTemplate(): string
    {
        return '@KazetennPages/content/page_form.html.twig';
    }
}
