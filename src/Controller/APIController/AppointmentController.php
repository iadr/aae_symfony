<?php

namespace App\Controller\APIController;

use App\Entity\Appointment;
use App\Entity\Subject;
use App\Entity\TutorHours;
use App\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class AppointmentController
 * @package App\Controller\APIController
 * @Route("/api/aae/appointments")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AppointmentController extends FOSRestController
{

    /**
     * @Rest\Get("/.{_format}", name="api_new_appointment_form", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description=""
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description=""
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     *
     * @SWG\Tag(name="Appointment")
     * @param Request $request
     * @return Response
     */
    public function newAppointmentFormAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $subjects = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $subjects = $em->getRepository(Subject::class)->findAvailableSubjects();

            if (is_null($subjects)) {
                $subjects = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get available subjects - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $subjects : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/subject_tutors", name="api_tutors_by_subject", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description=""
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description=""
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     * @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     type="string",
     *     description="The Appointment Type"
     * )
     *
     * @SWG\Parameter(
     *     name="subject_id",
     *     in="query",
     *     type="string",
     *     description="The Subject ID"
     * )
     *
     * @SWG\Parameter(
     *     name="date",
     *     in="query",
     *     type="string",
     *     description="The search date"
     * )
     *
     *
     * @SWG\Tag(name="Appointment")
     * @param Request $request
     * @return Response
     */
    public function getTutorsBySubjectAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $objects = [];
        $tutorsData=[];
        $message = "";
        try {
            $code = 200;
            $error = false;
            $subjectId=$request->query->get('subject_id');
            $date=$request->query->get('date');
            $type=$request->query->get('type');
            $tutors = $em->getRepository(Subject::class)->getTutorIds($subjectId);
            if ($type==1){
                $objects = $em->getRepository(TutorHours::class)->getTutorAvailableHours($tutors,$date);
            } else {
                $objects = $em->getRepository(TutorHours::class)->getAvailableTutor($tutors,$date);
            }
            $tutorsData = $em->getRepository(User::class)->findTutorList($tutors);
            if (is_null($objects)) {
                $objects = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all objects - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $objects : $message,
            'tutors' => $code == 200 ? $tutorsData : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/tutor_free_hours/{tutor_id}/{date}.{_format}", name="api_tutor_available_hours", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description=""
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description=""
     * )
     *
     * @SWG\Parameter(
     *     name="tutor_id",
     *     in="query",
     *     type="number",
     *     description="The Tutor ID"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     *
     * @SWG\Tag(name="Appointment")
     * @param Request $request
     * @param int $tutorId
     * @param string $date
     * @return Response
     * @throws \Exception
     */
    public function getTutorAvailableHoursAction(Request $request,int $tutorId,string $date) // No se utiliza
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $hours = [];
        $message = "";
        try {
            $code = 200;
            $error = false;
//            $hours = $em->getRepository(TutorHours::class)->getTutorAvailableHours($tutorId);
            $hours = $em->getRepository(TutorHours::class)->getTutorAvailableHours([$tutorId],$date);
            if (is_null($hours)) {
                $hours = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all hours - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $hours : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Post("/new.{_format}", name="api_new_appointment", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description=""
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description=""
     * )
     *
     * @SWG\Parameter(
     *     name="tutor_id",
     *     in="query",
     *     type="number",
     *     description="The Tutor ID"
     * )
     *
     * @SWG\Parameter(
     *     name="subject_id",
     *     in="query",
     *     type="number",
     *     description="The Subject ID"
     * )
     *
     * @SWG\Parameter(
     *     name="dates",
     *     in="query",
     *     type="string",
     *     description="The Appointments date"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     *
     * @SWG\Tag(name="Appointment")
     * @param Request $request
     * @return Response
     */
    public function newAppointmentAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $dates = [];
        $appointment = [];
        $message = "";
        try {
            $code = 200;
            $error = false;
            $studentId = $this->getUser()->getId();
            $tutorId = $request->request->get('tutor_id');
            $subjectId = $request->request->get('subject_id');
            $dates = $serializer->deserialize($request->request->get('dates'), "array", "json");

            if (!is_null($dates) and !empty($dates)) {
                foreach ($dates as $date) {
//                    $dates[]=[$date["date"],$date["hour"]]; // YA NO ES NECESARIO

                    $appointment = $em->getRepository(Appointment::class)->newAppointment($studentId, $tutorId, $subjectId, $date["date"], $date["hour"]);
                    if ($appointment == 500) {
                        $code = 500;
                        break;
                    }


                }
                //                $dates = [];

            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to set appointments - Error: {$ex->getMessage()}";
        } catch (\Exception $e) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to set appointments - Error: {$e->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $appointment : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }
}