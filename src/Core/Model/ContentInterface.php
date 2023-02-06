<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Model;

use Kazetenn\Core\Entity\BaseContentInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('kazetenn.content_type_tag')]
interface ContentInterface
{
    public function getFormType(): string;

    public function getNewContent(): BaseContentInterface;

    public function getContentName(): string;

    public function getContentClass(): string;

    public function getContentTemplate(): string;
}
