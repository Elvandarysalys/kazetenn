<?php

namespace Kazetenn\Core\Model\blocks;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Model\BlockInterface;

class ImageBlock implements BlockInterface
{
    public function getBlockName(): string
    {
        return 'image';
    }

    public function getModel(): array
    {
        return [
            'source' => '',
            'alt' => ''
        ];
    }

    public function getBlockContent(BaseBlockInterface $content)
    {
        return json_decode($content->getContent());
    }

    public function getBlockTemplate(): string{
        return '@KazetennCore/content/_block_image_content_display.twig';
    }

    public function getFormInfos(): array
    {
        return [
            'label' => false,
            'attr'  => [
                'class' => 'textarea'
            ]
        ];
    }
}
