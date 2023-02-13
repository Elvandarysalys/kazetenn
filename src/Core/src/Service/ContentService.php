<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Service;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Entity\BaseContentInterface;
use Kazetenn\Core\Model\ContentInterface;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class ContentService
{
    public const CLASS_KEY     = 'class';
    public const NAME_KEY      = 'name';
    public const FORM_TYPE_KEY = 'formType';

    /**
     * List all the type of contents classed by content name for quick search
     * @var array<ContentInterface>
     */
    protected array $contentTypes;

    /**
     * Map of all the services identifiers as an array to speed the search
     */
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
        // Using ClassUtils to prevent problems with doctrine's proxy classes
        $class = ClassUtils::getClass($content);

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
            if (null !== $search) {
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
     * @throws Exception
     */
    public function getBlockById(string $contentId, string $id): BaseBlockInterface
    {
        // determine the parent to get his class
        $parent = $this->getContentById($contentId);
        $class  = ClassUtils::getClass($parent);

        // check if the parent is known
        if (array_key_exists($class, $this->contentTypeMap)) {
            // get content name
            $contentName = $this->contentTypeMap[$class][self::NAME_KEY];

            // get content service from name
            $contentService = $this->contentTypes[$contentName];

            // get the block class
            $blockClass     = $contentService->getBlockClass();

            // use the block class to search
            // todo: i am sure there is a better way to do it.
            if (null !== $block = $this->managerRegistry->getRepository($blockClass)->find($id)){
                return $block;
            }
        }
        throw new Exception(sprintf('No block found for id %s', $id));
    }

    public function getAllContents(): array
    {
        $contentList = [];

        foreach ($this->contentTypes as $name => $contentType) {
            $contentList[$name] = $this->managerRegistry->getRepository($contentType->getContentClass())->findAll();
        }
        return $contentList;
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
