<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Model;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Service\ContentService;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AutoconfigureTag('controller.argument_value_resolver', ['priority' => 150])]
class BlockResolver implements ValueResolverInterface
{
    public function __construct(protected ManagerRegistry $managerRegistry, protected ContentService $contentService)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$this->supports($argument)) {
            return [];
        }
        return $this->apply($request);

    }

    // Vérifie le type attendu par le contrôleur
    private function supports(ArgumentMetadata $argument): bool
    {
        return $argument->getType() === BaseBlockInterface::class;
    }

    private function apply(Request $request): array
    {
        $blockId = $request->attributes->get('content');
        $id = $request->attributes->get('baseBlock');

        if(null !== $id) {
            try {
                return [$this->contentService->getBlockById($blockId, $id)];
            }catch (Exception){
                return [];
            }
        }
        return [];
    }
}
