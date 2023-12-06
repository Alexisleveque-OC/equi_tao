<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function findAllInvalidate() {
		return $this->createQueryBuilder('c')
			->andWhere('c.isValidate = :val')
			->setParameter('val', false)
			->orderBy('c.id', 'DESC')
			->getQuery()
			->getResult();
	}
//    /**

	/**
	 * @throws NonUniqueResultException
	 */
	public function findOneById(int $id) {
		return $this->createQueryBuilder('c')
			->andWhere('c.id = :val')
			->setParameter('val', $id)
			->getQuery()
			->getOneOrNullResult();
	}
}
