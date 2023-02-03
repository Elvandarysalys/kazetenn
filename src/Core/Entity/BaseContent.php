<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

class BaseContent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private ?UuidV4 $id;
}
