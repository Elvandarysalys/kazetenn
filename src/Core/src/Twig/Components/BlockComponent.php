<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Twig\Components;

use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Model\BlockInterface;
use Kazetenn\Core\Service\BlockService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent('block', template: '@KazetennCore/components/block.html.twig')]
class BlockComponent
{
    public BaseBlockInterface $block;
    /** @var BlockInterface mixed */
    public mixed  $definition;
    public string $blockType;
    public        $blockContent;
    public string $blockTemplate;
    public bool   $isValidType;

    public function __construct(private BlockService $blockService)
    {

    }

    #[PostMount]
    public function postMount()
    {
        $this->definition = $this->blockService->getBlockDefinitionByName($this->block->getType());

        $this->blockType = $this->block->getType();
        if (null === $this->definition) {
            $this->isValidType   = false;
            $this->blockContent  = '';
            $this->blockTemplate = '@KazetennCore/content/_block_content_display.twig';
        } else {
            $this->blockContent  = $this->definition->getBlockContent($this->block);
            $this->blockTemplate = $this->definition->getBlockTemplate();
            $this->isValidType   = true;
        }
    }
}
