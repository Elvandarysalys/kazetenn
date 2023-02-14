<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Kazetenn\Core\Entity\BaseBlockInterface;
use Kazetenn\Core\Entity\BaseContentInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\MappedSuperclass]
abstract class BaseContent implements BaseContentInterface
{
    const DEFAULT_TEMPLATE = '@KazetennCore/content/_block_content_display.twig'; // todo: do something about this

    use TimestampableEntity;
    // todo: set the user by email, do we keep it or do we create a real relation
    use BlameableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected UuidV4 $id;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    protected string $title;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: false)]
    protected string $slug;

    // OneToMany association does not work with MappedSuperclass. todo: check how to handle this, can it be handled in a service or not ?
    //    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: BaseBlock::class)]
    //    #[ORM\OrderBy(["blocOrder" => "asc"])]
    protected Collection $blocks;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    protected string $template;

    public function __construct(?string $template = null)
    {
//        $this->blocks = new ArrayCollection();

        $this->id = Uuid::v4();

        if (null === $template) {
            $this->template = self::DEFAULT_TEMPLATE;
        } else {
            $this->template = $template;
        }
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function setId(UuidV4 $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return Collection
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     * This return an ordered array of the direct descendant blocks.
     * @return array<BaseBlockInterface>
     */
    public function getBlocksOrdered(): array{
        $data = $this->blocks;

        $return = [];
        foreach ($data as $datum) {
            if ($datum->getparent() === null) {
                $return[$datum->getBlocOrder()] = $datum;
            }
        }

        return $return;
    }

    /**
     * @param Collection $blocks
     */
    public function setBlocks(Collection $blocks): void
    {
        $this->blocks = $blocks;
    }
}
