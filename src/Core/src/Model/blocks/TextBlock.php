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

    public function getBlockContent(BaseBlockInterface $content): string
    {
        return $content->getContent();
    }
}
