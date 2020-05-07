<?php

namespace App\Repository;

use App\Entity\TutorHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method TutorHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method TutorHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method TutorHours[]    findAll()
 * @method TutorHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorHoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutorHours::class);
    }

    // /**
    //  * @return TutorHours[] Returns an array of TutorHours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TutorHours
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getTutorAvailableHours(array $arr)
    {
        $conn = $this->getEntityManager()->getConnection();

//        $sql = 'select sq.* from (
//	select td.db_date,ht.hour from time_dimension as td
//	join (
//		select th.tutor_id,hours.* from tutor_hours th,
//			JSON_TABLE(hours,\'$.hours[*]\' columns(
//				-- dateid for ordinality,
//				day int path \'$.day\',
//				hour time path \'$.hour\')
//				) hours where th.tutor_id=:tutorId) as ht
//		where ht.day=td.day_of_week) as sq
//    where (sq.db_date,sq.hour) not in (select date,hour from appointment where tutor_id=:tutorId);';

        $sql = 'select sq.* from (
	select td.db_date,ht.hour,tutor_id from (SELECT * FROM time_dimension WHERE db_date BETWEEN  CURRENT_DATE AND CURRENT_DATE + INTERVAL 1 MONTH ) as td
	join (
		select th.tutor_id,hours.* from tutor_hours th,
			JSON_TABLE(hours,\'$.hours[*]\' columns(
				-- dateid for ordinality,
				day int path \'$.day\',
				hour time path \'$.hour\')
				) hours where th.tutor_id IN (?)) as ht
		where ht.day=td.day_of_week) as sq
    where (sq.db_date,sq.hour) not in (select date,hour from appointment where tutor_id IN (?)) ORDER BY sq.db_date,sq.hour;';
        try {
            $stmt = $conn->executeQuery($sql, [$arr, $arr], [\Doctrine\DBAL\Connection::PARAM_INT_ARRAY, \Doctrine\DBAL\Connection::PARAM_INT_ARRAY]);
        } catch (DBALException $e) {
        }
        return $stmt->fetchAll();
    }
}
