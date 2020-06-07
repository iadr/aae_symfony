<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    // /**
    //  * @return Appointment[] Returns an array of Appointment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Appointment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function newAppointment(int $studentId, int $tutorId, int $subjectId, string $date, string $hour)
    {
        $conn = $this->getEntityManager()->getConnection();

        try {
            return $conn->insert(
                'appointment',
                [
                    'subject_id' => $subjectId,
                    'student_id' => $studentId,
                    'tutor_id' => $tutorId,
                    'date' => $date,
                    'hour' => $hour,
                ]
            );
        } catch (DBALException $e) {
            return 500;
        }
    }

    public function getTutorAppointments(int $tutorId)
    {
        $conn = $this->getEntityManager()->getConnection();
//        $sql = 'select appointment.id, subject_id, u.id as user_id, email, name, address, date, hour from appointment join user u on appointment.student_id = u.id where tutor_id=:tutor AND date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 15 DAY ';
        $sql = 'select appointment.id as id,subject_id, s.name as subject_name, date, hour, student_id, tutor_id,user.name as name,user.email as email, user.address as address from appointment 
                join user on appointment.student_id = user.id 
                join subject s on appointment.subject_id = s.id
                where tutor_id=:tutor AND date >= CURRENT_DATE';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute(['tutor' => $tutorId]);

        return $stmt->fetchAll();
    }

    public function getStudentAppointments(int $studentId)
    {
        $conn = $this->getEntityManager()->getConnection();
//        $sql = 'select appointment.id, subject_id, u.id as user_id, email, name, address, date, hour from appointment join user u on appointment.tutor_id = u.id where student_id=:student AND date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 15 DAY ';
        $sql = 'select appointment.id as id,subject_id, s.name as subject_name, date, hour, student_id, tutor_id,user.name as name,user.email as email from appointment 
                join user on appointment.student_id = user.id 
                join subject s on appointment.subject_id = s.id
                where student_id=:student AND date >= CURRENT_DATE';

        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute(['student' => $studentId]);

        return $stmt->fetchAll();
    }
}
