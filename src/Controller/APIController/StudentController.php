<?php

namespace App\Controller\APIController;

use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;


/**
 * @Route("/api/aae/students")
 */
class StudentController extends FOSRestController
{
    /**
     * @Rest\Get("/appointments.{_format}", name="api_get_student_appointments", defaults={"_format":"json"})
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
     * @SWG\Tag(name="Student Appointments")
     * @param Request $request
     * @return Response
     */
    public function getAppointmentsAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $appointments = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $student=$em->getRepository(User::class)->find($this->getUser()->getId());
            $appointments=$student->getAppointments();

            if (is_null($appointments)) {
                $appointments = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all appointments - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $appointments : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }
}