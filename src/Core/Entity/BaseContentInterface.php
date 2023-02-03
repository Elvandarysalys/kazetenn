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
use Symfony\Component\Uid\UuidV4;

interface BaseContentInterface
{
    public function getId(): UuidV4;

    public function setId(UuidV4 $id): void;

    public function getTitle(): string;

    public function setTitle(string $title): void;

    public function getSlug(): string;

    public function setSlug(string $slug): void;

    public function getBlocks(): Collection;

    public function setBlocks(Collection $blocks): void;

    public function getTemplate(): string;

    public function setTemplate(string $template): void;

    public function setCreatedBy($createdBy);

    public function getCreatedBy();

    public function setUpdatedBy($updatedBy);

    public function getUpdatedBy();

    public function setCreatedAt(DateTime $createdAt);

    public function getCreatedAt();

    public function setUpdatedAt(DateTime $updatedAt);

    public function getUpdatedAt();
}
