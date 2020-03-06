<?php

namespace App\Controller\APIController;

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
 * @Route("/api/aae/subjects")
 */
class SubjectController extends FOSRestController
{
    /**
     * @Rest\Get("/.{_format}", name="api_subjects_index", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all subjects."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all subjects."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The subject ID"
     * )
     *
     *
     * @SWG\Tag(name="Subject")
     * @return Response
     */
    public function getAllSubjectAction() {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $subjects = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $subjects = $em->getRepository(Subject::class)->findThemAll();

            if (is_null($subjects)) {
                $subjects = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all Subjects - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $subjects : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/{level}.{_format}", name="api_subjects_by_level", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all subjects."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all subjects."
     * )
     *
     * @SWG\Parameter(
     *     name="level",
     *     in="path",
     *     type="string",
     *     description="The subject ID"
     * )
     *
     *
     * @SWG\Tag(name="Subject")
     * @param         $level
     * @return Response
     */
    public function getSubjectsByLevelAction(int $level) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $subjects = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $subjects = $em->getRepository(Subject::class)->findyByLevel($level);

            if (is_null($subjects)) {
                $subjects = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all Subjects - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $subjects : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }


}