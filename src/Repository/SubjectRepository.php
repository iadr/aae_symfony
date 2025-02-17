<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;

//use Symfony\Component\HttpFoundation\Response;

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
//        $sql='select id,name,
//                CASE
//                    WHEN level =1 THEN "1° básico"
//                    WHEN level =2 THEN "2° básico"
//                    WHEN level =3 THEN "3° básico"
//                    WHEN level =4 THEN "4° básico"
//                    WHEN level =5 THEN "5° básico"
//                    WHEN level =6 THEN "6° básico"
//                    WHEN level =7 THEN "7° básico"
//                    WHEN level =8 THEN "8° básico"
//                    WHEN level =9 THEN "1° medio"
//                    WHEN level =10 THEN "2° medio"
//                    WHEN level =11 THEN "3° medio"
//                    WHEN level =12 THEN "4° medio"
//                END as level
//                FROM subject;';
        $sql='select id,name,level FROM subject;';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function findyByLevel(string $level)
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
    
    public function getNotRelatedSubjects(int $user)
    {
        $conn= $this->getEntityManager()->getConnection();
        $sql='select id,name,level from subject s 
where s.id not in (select subject_id from subject_user where user_id=:user);';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute(['user' => $user]);
        return $stmt->fetchAll(); 
    }

    public function addSubjectTutor(int $subject, int $tutor)
    {
        $conn = $this->getEntityManager()->getConnection();

        try {
            $conn->insert('subject_user', ['subject' => $subject, 'tutor' => $tutor]);

        } catch (DBALException $e) {
            return "problem";
        }
        return "OK";
    }

    public function findAvailableSubjects()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select s.id as id,s.name as name,level, count(*) as tutors from subject s 
                join subject_user su on s.id=su.subject_id
                join user u on su.user_id=u.id 
                where u.enabled 
                group by s.id;';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTutorList(int $subject)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select id,name, study_in from user u
                join subject_user su on u.id=su.user_id
                where su.subject_id=:subject and u.enabled;';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute(['subject' => $subject]);

        return $stmt->fetchAll();
    }

    public function getTutorIds(int $subject)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select id from user u
                join subject_user su on u.id=su.user_id
                where su.subject_id=:subject and u.enabled;';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute(['subject' => $subject]);

        return $stmt->fetchAll(FetchMode::COLUMN);
    }
}
