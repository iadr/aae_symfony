<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Post("/api/login_check", name="user_login_check")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User was logged in successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="User was not logged in successfully"
     * )
     *
     * @SWG\Parameter(
     *     name="_username",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={
     *     }
     * )
     *
     * @SWG\Parameter(
     *     name="_password",
     *     in="body",
     *     type="string",
     *     description="The password",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function getLoginCheckAction() {}


    /**
     * @Rest\Post("/api/register", name="user_register")
     *
     * @SWG\Response(
     *     response=201,
     *     description="User was successfully registered"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="User was not successfully registered"
     * )
     *
     * @SWG\Parameter(
     *     name="_name",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="_email",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     * @SWG\Parameter(
     *     name="_study",
     *     in="body",
     *     type="string",
     *     description="The institution where the user studies",
     *     schema={}
     * )
     * @SWG\Parameter(
     *     name="_address",
     *     in="body",
     *     type="string",
     *     description="The user's address",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="_password",
     *     in="query",
     *     type="string",
     *     description="The password"
     * )
     *
     * @SWG\Tag(name="User")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        $user = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

//            $name = $request->request->get('_name');
            $email = $request->request->get('_email');
//            $studyIn = $request->request->get('_study');
//            $address = $request->request->get('_address');
            $password = $request->request->get('_password');

            $user = new User();
//            $user->setName($name);
            $user->setEmail($email);
//            $user->setStudyIn($studyIn);
//            ($address)?$user->setAddress($address):null;
            $user->setPassword($encoder->encodePassword($user, $password));

            $em->persist($user);
            $em->flush();

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to register the user - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $user : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Get("/api/aae/userprofile.{_format}", name="api_get_user_profile", defaults={"_format":"json"})
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
     * @SWG\Tag(name="User")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return Response
     */
    public function getUserProfileAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $serializer = $this->get('jms_serializer');
//        $em = $this->getDoctrine()->getManager();
        $profile = [];
        $message = "";
        try {
            $code = 200;
            $error = false;
//            $userId=$this->getUser()->getId();
//            $profile = $em->getRepository(User::class)->find($userId);
            $profile=$this->getUser();
                if (is_null($profile)) {
                    $profile = [];
                }

            } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get user profile - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $profile : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }


/**
     * @Rest\Put("/api/aae/userprofile.{_format}", name="api_update_user_profile", defaults={"_format":"json"})
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
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     * @SWG\Parameter(
     *     name="address",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     * @SWG\Parameter(
     *     name="study_in",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     * @SWG\Parameter(
     *     name="major",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="The ID"
     * )
     *
     *
     * @SWG\Tag(name="User")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return Response
     */
    public function setUserProfileAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $profile = [];
        $message = "";
        try {
            $code = 200;
            $error = false;
            $profile=$em->getRepository(User::class)->find($this->getUser()->getId());

            $name=$request->request->get('name', null);
            $address=$request->request->get('address', null);
            $studyIn=$request->request->get('study_in', null);
            $major=$request->request->get('major', null);
            $description=$request->request->get('description', null);

            $email=$request->request->get('email',$profile->getEmail());
            $password=$request->request->get('password',$profile->getPassword());
            $password=$encoder->encodePassword($profile,$password);
//                if (is_null($profile)) {
//                    $profile = [];
//                }
            $profile->setName($name);
            $profile->setAddress($address);
            $profile->setStudyIn($studyIn);
            $profile->setMajor($major);
            $profile->setDescription($description);

            $profile->setEmail($email);
            $profile->setPassword($password);

            $em->persist($profile);
            $em->flush();




            } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get user profile - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $profile : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

}
