<?php

namespace Kazetenn\Core\Model\blocks;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Model\BlockInterface;

class TextBlock implements BlockInterface
{
    public function getBlockName(): string
    {
        return 'text';
    }

    public function getModel(): array
    {
        return [''];
    }

    public function getBlockContent(BaseBlockInterface $content): string|null
    {
        return $content->getContent();
    }

    public function getBlockTemplate(): string{
        return '@KazetennCore/content/_block_content_display.twig';
    }

    public function getFormInfos(): array
    {
        return [
            'label' => false,
            'attr'  => [
                'class' => 'textarea block_text_area'
            ]
        ];
    }
}
