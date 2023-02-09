<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Service;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Model\ContentInterface;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class ContentService
{
    public const CLASS_KEY     = 'class';
    public const NAME_KEY      = 'name';
    public const FORM_TYPE_KEY = 'formType';
    protected array $contentTypes;
    protected array $contentTypeMap;

    public function __construct(protected ManagerRegistry $managerRegistry, RewindableGenerator $availableContentTypes)
    {
        /** @var ContentInterface $contentType */
        foreach ($availableContentTypes as $contentType) {
            $this->contentTypes[$contentType->getContentName()] = $contentType;

            $contentTypeDetails                                    = [
                self::CLASS_KEY     => $contentType->getContentClass(),
                self::NAME_KEY      => $contentType->getContentName(),
                self::FORM_TYPE_KEY => $contentType->getFormType(),
            ];
            $this->contentTypeMap[$contentType->getContentName()]  = $contentTypeDetails;
            $this->contentTypeMap[$contentType->getContentClass()] = $contentTypeDetails;
            $this->contentTypeMap[$contentType->getFormType()]     = $contentTypeDetails;
        }
    }

    public function getContentByName(string $contentName): ?ContentInterface
    {
        return array_key_exists($contentName, $this->contentTypes) ? $this->contentTypes[$contentName] : null;
    }

    public function getContentByClass(object $content): ?ContentInterface
    {
        $class = get_class($content);

        if (array_key_exists($class, $this->contentTypeMap)) {
            $contentName = $this->contentTypeMap[$class][self::NAME_KEY];
            return $this->contentTypes[$contentName];
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function getContentById(string $id): BaseContentInterface
    {
        /** @var ContentInterface $contentType */
        foreach ($this->contentTypes as $contentType) {
            $search = $this->managerRegistry->getRepository($contentType->getContentClass())->find($id);
            if (null !== $search){
                /** @var array<BaseContentInterface> $datas */
                $datas[] = $search;
            }
        }

        if (!empty($datas)) {
            if (count($datas) > 1) {
                throw new Exception('More than one content found with this id');
            }

            return $datas[0];
        }
        throw new Exception('No content found with this id');
    }

    /**
     * @return array
     */
    public function getContentTypes(): array
    {
        return $this->contentTypes;
    }

    /**
     * @return array
     */
    public function getContentTypeMap(): array
    {
        return $this->contentTypeMap;
    }
}
