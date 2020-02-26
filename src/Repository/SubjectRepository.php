<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    // /**
    //  * @return Subject[] Returns an array of Subject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subject
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findThemAll()
    {
        $conn= $this->getEntityManager()->getConnection();
        $sql='select id,name,
                CASE
                    WHEN level =1 THEN "1° básico"
                    WHEN level =2 THEN "2° básico"
                    WHEN level =3 THEN "3° básico"
                    WHEN level =4 THEN "4° básico"
                    WHEN level =5 THEN "5° básico"
                    WHEN level =6 THEN "6° básico"
                    WHEN level =7 THEN "7° básico"
                    WHEN level =8 THEN "8° básico"
                    WHEN level =9 THEN "1° medio"
                    WHEN level =10 THEN "2° medio"
                    WHEN level =11 THEN "3° medio"
                    WHEN level =12 THEN "4° medio"
                END as level
                FROM subject;';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function findyByLevel(int $level)
    {
        $conn= $this->getEntityManager()->getConnection();
        $sql='select id,name FROM subject where level=:level;';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute(['level' => $level]);
        return $stmt->fetchAll();
    }
}
