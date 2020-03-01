<?php

namespace App\Controller\APIController;

use App\Entity\Appointment;
use App\Entity\Subject;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
        $objects = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $objects = $em->getRepository(Subject::class)->findThemAll(); // TODO: Función que retorne solo las materias con tutores

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
        ];

        return new Response($serializer->serialize($response, "json"));
    }

}