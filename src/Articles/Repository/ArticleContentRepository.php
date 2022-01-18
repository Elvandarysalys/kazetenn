<?php
/*
 * This file is part of the Kazetenn Articles Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Articles\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kazetenn\Articles\Entity\ArticleContent;

/**
 * @method ArticleContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleContent[]    findAll()
 * @method ArticleContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleContent::class);
    }
}
