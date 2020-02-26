<?php

namespace App\Controller\APIController;

use App\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @Route("/api/tutors")
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

            $tutors = $em->getRepository(User::class)->findAll();
            // TODO: crear funcion en repositorio que retorne los usuarios con rol TUTOR (nombre y casa de estudios)

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
}