<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Users;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends Controller
{   
    
    /**
     * @Route("/", name="index")
     */
    public function indexAction(){
        return $this->redirectToRoute('loginPage');
    }
    
    /**
     * @Route("/register", name="registerPage")
     */
    public function registerPage(Request $request){
        return $this->render('register.html.twig');
    }
    
    /**
     * @Route("/login", name="loginPage")
     */
    public function loginPage(Request $request){
        return $this->render('login.html.twig');
    }
    
    /**
     * @Route("/private", name="privatePage")
     */
    public function privatePage(Request $request){
        $session = $request->getSession();
        if(!$session->has('uid')){
            return $this->redirectToRoute('loginPage');
        }
        else{
            $uid = $session->get('uid');
            return $this->render('private.html.twig', array('uid' => $uid));
        }   
    }
    
    /**
     * @Route("/actions/login", name="loginAction")
     */
    public function loginAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        
        //check to ensure we have both require POST parameters
        if(empty($email) || empty($password)){
            return $this->render('login.html.twig', array('error' => "Invalid email or password"));
        }
        else{
            $user = $em->getRepository('AppBundle:Users')->findOneBy(array('username' => $email));
            if(empty($user)){
                return $this->render('login.html.twig', array('error' => "Invalid email or password"));
            }
            //TO-DO: implement session timeout after ~5 minutes
            if($password == $user->getPassword()){
                $uid = $user->getUserId();
                $session = $request->getSession();
                $session->set('uid', $uid);
                return $this->redirectToRoute('privatePage');
            }
            else{
                return $this->render('login.html.twig', array('error' => "Invalid email or password"));
            }
        }
    }
    
    /**
     * @Route("/actions/register", name="registerAction")
     */
    public function registerAction(Request $request){
        $first = $request->request->get('FirstName');
        $last = $request->request->get('LastName');
        $email = $request->request->get('Email');
        $password = $request->request->get('Password');
        $major = $request->request->get('Major');
        
        if(empty($email) || empty($password) || empty($first) || empty($last) || empty($major)){
            return $this->render('register.html.twig', array('error' => "Missing required fields"));
        }
        else{
            $em = $this->getDoctrine()->getManager('default');
            $u = new Users();
            $u->setUsername($email);
            $u->setPassword($password);
            $u->setFirstname($first);
            $u->setLastName($last);
            $u->setMajor($major);
            $em->persist($u);
            $em->flush();
            $uid = $u->getUserId();
            $session = $request->getSession();
            $session->set('uid', $uid);
            return $this->redirectToRoute('privatePage');
        }
    }
}
