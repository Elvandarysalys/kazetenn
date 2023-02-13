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
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Service\ContentService;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AutoconfigureTag('controller.argument_value_resolver', ['priority' => 150])]
class ContentResolver implements ValueResolverInterface
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
        return $argument->getType() === BaseContentInterface::class;
    }

    private function apply(Request $request): array
    {
        $id = $request->attributes->get('content');

        if(null !== $id) {
            try {
                return [$this->contentService->getContentById($id)];
            }catch (Exception){
                return [];
            }
        }
        return [];
    }
}
