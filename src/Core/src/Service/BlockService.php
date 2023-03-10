<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Service;

use Doctrine\Persistence\ManagerRegistry;
use Kazetenn\Core\Model\BlockInterface;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class BlockService
{
    /** @var array<BlockInterface>  */
    protected array $blockTypes;

    public function __construct(protected ManagerRegistry $managerRegistry, RewindableGenerator $availableBlockTypes)
    {
        /** @var BlockInterface $blockType */
        foreach ($availableBlockTypes as $blockType) {
            $this->blockTypes[$blockType->getBlockName()] = $blockType;
        }
    }

    public function getBlockDefinitionByName(string $type): ?BlockInterface {
        if (array_key_exists($type, $this->blockTypes)){
            return $this->blockTypes[$type];
        }
        return null;
    }

    public function getTypes(): array{
        return array_keys($this->blockTypes);
    }

    public function getFormInfos(string $type): array{
        /** @var BlockInterface $data */
        $data = $this->blockTypes[$type];
        return $data->getFormInfos();
    }
}
