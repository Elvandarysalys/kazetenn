<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Kazetenn\Core\Entity\BaseBlockInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;

interface BaseContentInterface
{
    public function getId(): UuidV4;

    public function setId(UuidV4 $id): void;

    public function getTitle(): string;

    public function setTitle(string $title): void;

    public function getSlug(): string;

    public function setSlug(string $slug): void;

    public function getTemplate(): string;

    public function setTemplate(string $template): void;

    /**
     * @param string $createdBy
     *
     * @return $this
     */
    public function setCreatedBy($createdBy);

    /**
     * @return string
     */
    public function getCreatedBy();

    /**
     * @param string $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy($updatedBy);

    /**
     * @return string
     */
    public function getUpdatedBy();

    /**
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt);

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return DateTime
     */
    public function setUpdatedAt(DateTime $updatedAt);

    /**
     * @return $this
     */
    public function getUpdatedAt();

    public function getBlocks(): Collection;

    /**
     * @return array<BaseBlockInterface>
     */
    public function getBlocksOrdered(): array;

    public function setBlocks(Collection $blocks): void;
}
