<?php

namespace Kazetenn\Core\Model\blocks;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Model\BlockInterface;

class DocumentBlock implements BlockInterface
{
    public function getBlockName(): string
    {
        return 'document';
    }

    public function getModel(): array
    {
        return [
            'source',
            'type'
        ];
    }

    public function getBlockContent(BaseBlockInterface $content): string
    {
        dd(json_decode($content->getContent()));
        return $content->getContent();
    }
}
