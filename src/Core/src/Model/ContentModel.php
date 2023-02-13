<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Model;

use Kazetenn\Core\Entity\BaseContentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ContentModel extends AbstractController implements ContentInterface
{
    abstract public function getFormType(): string;

    abstract public function getNewContent(): BaseContentInterface;

    abstract public function getContentName(): string;

    abstract public function getContentClass(): string;

    abstract public function getContentTemplate(): string;
}
