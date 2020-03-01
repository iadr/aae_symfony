<?php

namespace App\Controller\APIController;

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
 * @Route("/api/aae/tutors")
 */
class TutorController extends FOSRestController
{
    /**
     * @Rest\Get("/.{_format}", name="api_tutors_index", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all tutors."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all tutors."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The tutor ID"
     * )
     *
     *
     * @SWG\Tag(name="Tutor")
     * @param Request $request
     * @return Response
     */
    public function listAllTutorsAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $tutors = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $tutors = $em->getRepository(User::class)->findTutors();

            if (is_null($tutors)) {
                $tutors = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all tutors - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $tutors : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/subjects.{_format}", name="api_list_tutor_subjects", defaults={"_format":"json"})
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
     * @SWG\Tag(name="Tutor Subjects")
     * @param Request $request
     * @return Response
     */
    public function listAllTutorSubjectsAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $subjects = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $tutorId=$this->getUser()->getId();
            $tutor=$em->getRepository(User::class)->findOneBy(['id' => $tutorId]);
            $subjects=$tutor->getSubjects();
                if (is_null($subjects)) {
                    $subjects = [];
                }

            } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all subjects - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $subjects : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/subjects/new.{_format}", name="api_new_subject_tutor_relation_form", defaults={"_format":"json"})
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
     * @SWG\Tag(name="Tutor Subjects")
     * @param Request $request
     * @return Response
     */
    public function newSubjectTutorFormAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $subjects = [];
        $message = "";
        try {
            $code = 200;
            $error = false;
            $tutorId=$this->getUser()->getId();
            $subjects = $em->getRepository(Subject::class)->getNotRelatedSubjects($tutorId);

                if (is_null($subjects)) {
                    $subjects = [];
                }
    
            } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get subjects - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $subjects : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Post("/subjects/new.{_format}", name="api_new_subject_tutor_relation", defaults={"_format":"json"})
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
     *     name="subject_id",
     *     in="query",
     *     type="int",
     *     description="The Subject ID"
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
     * @SWG\Tag(name="Tutor Subjects")
     * @param Request $request
     * @return Response
     */
    public function newSubjectTutorAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $subject = [];
        $message = "";
        try {
            $code = 201;
            $error = false;
            $subjectId=$request->request->get('subject_id');

            //            $subject = $em->getRepository(Subject::class)->addSubjectTutor($subjectId,$tutorId);

                if (!is_null($subjectId)) {
                    $tutorId=$this->getUser()->getId();
                    $tutor=$em->getRepository(User::class)->find($tutorId);
                    $subject=$em->getRepository(Subject::class)->find($subjectId);
                    $tutor->addSubject($subject);
                    $em->persist($tutor);
                    $em->flush();
                }
    
            } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all subject - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 201 ? $subject : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/hours.{_format}", name="api_get_tutor_hours", defaults={"_format":"json"})
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
     * @SWG\Tag(name="Subject")
     * @param Request $request
     * @return Response
     */
    public function getTutorHoursAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $hours = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $tutorId=$this->getUser()->getId();
            $hours = $em->getRepository(TutorHours::class)->findBy(['tutor' => $tutorId]);
    
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
     * @Rest\Put("/config_hours.{_format}", name="api_set_tutor_hours", defaults={"_format":"json"})
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
     * @SWG\Parameter(
     *     name="hours",
     *     in="query",
     *     type="json",
     *     description="The ID"
     * )
     *
     *
     * @SWG\Tag(name="Subject")
     * @param Request $request
     * @return Response
     */
    public function setTutorHoursAction(Request $request)
    {
        // TODO: Escribir metodo para Setteo de Horas de usuario
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $tutorHours = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $hours=$request->request->get('hours');

            if(!is_null($hours) && !empty($hours))
            {
                $hours=$serializer->deserialize($hours,"array","json");
            } else {
                $hours=null;
            }

            $tutorId=$this->getUser()->getId();
            $tutorHours = $em->getRepository(TutorHours::class)->findOneBy(['tutor' => $tutorId]);

            if (is_null($tutorHours) || empty($tutorHours) ) {
                $tutorHours = new TutorHours();
                $tutor=$em->getRepository(User::class)->find($tutorId);
                $tutorHours->setTutor($tutor);
            }
            $tutorHours->setHours($hours);
            $em->persist($tutorHours);
            $em->flush();


        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to set tutorHours - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $tutorHours : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }
}