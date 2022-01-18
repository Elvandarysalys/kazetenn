<?php
/*
 * This file is part of the Kazetenn Pages Bundle
 *
 * (c) Gwilherm-Alan Turpin (elvandar.ysalys@protonmail.com) 2022.
 *
 * For more informations about the license and copyright, please view the LICENSE file at the root of the project.
 */

namespace Kazetenn\Pages\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Kazetenn\Pages\Entity\Page;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @param string|null $page_path_1
     * @param string|null $page_path_2
     * @return int|mixed|string|null
     * @throws Exception
     */
    public function findPage(?string $page_path_1, ?string $page_path_2)
    {
        if (null === $page_path_1 && null === $page_path_2) {
            return null;
        }
        if (null === $page_path_1 && null !== $page_path_2) {
            throw new Exception('if the second argument is not null, the first argument cannot be null');
        }

        $qb = $this->createQueryBuilder('p');

        if ($page_path_2 !== null) {
            $qb->where('p.slug = :pageSlug')->setParameter('pageSlug', $page_path_2);
//            $qb
//                ->leftJoin('p.parent', 'parent')
//               ->where('parent.slug = :parentSlug')
////               ->where('p.parent.slug = :parentSlug')
//               ->andWhere('parent.slug = :pageSlug')
//               ->setParameters([
//                   'parentSlug' => $page_path_1,
//                   'pageSlug'   => $page_path_2
//               ]);
        } else {
            $qb->where('p.slug = :pageSlug')->setParameter('pageSlug', $page_path_1);
        }

        $results = $qb->getQuery()->getOneOrNullResult();

        if (empty($results)) {
            return null;
        }

        return $results;
    }
}
