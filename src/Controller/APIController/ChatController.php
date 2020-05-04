<?php

namespace App\Controller\APIController;

use App\Entity\Chat;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/aae/chat", name="chat")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class ChatController extends FOSRestController
{
    /**
     * @Rest\Get("/{appointment_id}.{_format}", name="api_chat_get_messages", defaults={"_format":"json"})
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
     *     name="appoinment_id",
     *     in="path",
     *     type="string",
     *     description="The ID"
     * )
     *
     *
     * @SWG\Tag(name="Chat")
     * @param int $appointment_id
     * @return Response
     */
    public function getMessagesAction(int $appointment_id)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $objects = [];
        $message = "";
        try {
            $code = 200;
            $error = false;

            $objects = $em->getRepository(Chat::class)->findBy(['appointmentId' => $appointment_id]);

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

    /**
     * @Rest\Post("/{appointment_id}.{_format}", name="api_", defaults={"_format":"json"})
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
     *     name="message",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     * @SWG\Tag(name="Subject")
     * @param Request $request
     * @param int     $appointment_id
     * @return Response
     */
    public function Action(Request $request, int $appointment_id)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $objects = [];
        $message = "";
        try {
            $code = 200;
            $error = false;
            $chat_message = $request->get('message');
            $sender = $this->getUser()->getId();

            $objects = new Chat();
            $objects->setAppointmentId($appointment_id);
            $objects->setMessage($chat_message);
            $objects->setSender($sender);
            $em->persist($objects);
            $em->flush();

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
