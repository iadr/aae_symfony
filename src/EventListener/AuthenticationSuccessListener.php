<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

//        $data['data'] = array(
//            'roles' => $user->getRoles(),
//        );
        $data['data']=[
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
            "studyIn" => $user->getStudyIn(),
            "major" => $user->getMajor(),
            "address" => $user->getAddress(),
            "description" => $user->getDescription(),
            ];

        $event->setData($data);
    }
}

