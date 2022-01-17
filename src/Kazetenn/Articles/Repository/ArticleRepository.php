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
use Exception;
use Kazetenn\Articles\Entity\Article;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param string|null $article_path_1
     * @param string|null $article_path_2
     * @return int|mixed|string|null
     * @throws Exception
     */
    public function findPage(?string $article_path_1, ?string $article_path_2)
    {
        if (null === $article_path_1 && null === $article_path_2) {
            return null;
        }
        if (null === $article_path_1 && null !== $article_path_2) {
            throw new Exception('if the second argument is not null, the first argument cannot be null');
        }

        $qb = $this->createQueryBuilder('p');

        if ($article_path_2 !== null) {
            $qb->where('p.slug = :articleSlug')->setParameter('articleSlug', $article_path_2);
//            $qb
//                ->leftJoin('p.parent', 'parent')
//               ->where('parent.slug = :parentSlug')
////               ->where('p.parent.slug = :parentSlug')
//               ->andWhere('parent.slug = :pageSlug')
//               ->setParameters([
//                   'parentSlug' => $article_path_1,
//                   'pageSlug'   => $article_path_2
//               ]);
        } else {
            $qb->where('p.slug = :articleSlug')->setParameter('articleSlug', $article_path_1);
        }

        $results = $qb->getQuery()->getOneOrNullResult();

        if (empty($results)) {
            return null;
        }

        return $results;
    }
}
