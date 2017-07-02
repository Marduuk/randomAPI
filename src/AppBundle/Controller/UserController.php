<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;



use AppBundle\Form\Type\UserForm;


/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{

    /**
     * @Route("/api")
     */

    public function apiAction(){

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer()); //zrobic serwis z tego !

        $serializer = new Serializer($normalizers, $encoders);

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        $xml=$serializer->serialize($users, 'json');
        var_dump($xml);
        return new Response("$xml");


    }
    /**
     * @Route("custom")
     */
    public function customAction(Request $req,$form){

        $form->handleRequest($req);

        return new Response("raz raz");
    }

    /**
     * @Route("/apiNew")
     */
    public function apiWriteAction(Request $req){

        $user =new User();
        $form=$this->createForm(UserForm::class,
            null, array('action' => $this->generateUrl('app_user_custom')));


       // $form->handleRequest($req);



        return $this->render('AppBundle:User:new.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {

        return $this->render('user/show.html.twig', array(
            'user' => $user,
        ));
    }
}
