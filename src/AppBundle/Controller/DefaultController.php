<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Users;
use AppBundle\Entity\Friends;
use AppBundle\Entity\Messages;
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
     * @Route("/dashboard", name="dashboardPage")
     */
    public function dashboardPage(Request $request){
        $session = $request->getSession();
        if(!$session->has('uid')){
            return $this->redirectToRoute('loginPage');
        }
        else{
            $uid = $session->get('uid');
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Users')->find($uid);
            
            //query to get all friends
            
            $friends_a = $em->getRepository('AppBundle:Friends')->findBy(array('userA' => $uid));
            
            $friends_b = $em->getRepository('AppBundle:Friends')->findBy(array('userB' => $uid));
            
            $friend_ids = array();
            
            foreach($friends_a as $fa){
                $friend_ids[] = $fa->getUserB();
            }
            
            foreach($friends_b as $fb){
                $friend_ids[] = $fb->getUserA();
            }
            
            $friends = $em->getRepository('AppBundle:Users')->findBy(array('userid' => $friend_ids));
            
            $messages = array();
            $selectedFriendId = -1;
            $selectedFriend = null;
            if(!empty($friends)){
                $messages_a = $em->getRepository('AppBundle:Messages')->findBy(array('senderId' => $uid, 'receiverId' => $friends[0]->getUserId()), array('messageId' => 'DESC'));
                
                $messages_b = $em->getRepository('AppBundle:Messages')->findBy(array('senderId' => $friends[0]->getUserId(), 'receiverId' => $uid), array('messageId' => 'DESC'));
                
                $messages = array_merge($messages_a, $messages_b);
                
                usort($messages, function($a, $b)
                {
                    return $a->getMessageId() > $b->getMessageId();
                });
                
                
                $selectedFriendId = $friends[0]->getUserId();
                $selectedFriend = $friends[0];
            }
            
            // somehow create a Response object, like by rendering a template
            $response = $this->render('dashboard.html.twig', array('user' => $user, 'friends' => $friends, 'messages' => $messages, 'selectedFriend' => $selectedFriend, 'selectedFriendId' => $selectedFriendId));
            
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            
            return $response;
            
            /*return $this->render('dashboard.html.twig', array('user' => $user, 'friends' => $friends, 'messages' => $messages));*/
        }   
    }
    
    /**
     * @Route("/api/friends", name="getFriends")
     */
    public function getFriendsAction(Request $request){
        $session = $request->getSession();
        $uid = $session->get('uid');
        $em = $this->getDoctrine()->getManager();
        //query to get all friends    
        $friends_a = $em->getRepository('AppBundle:Friends')->findBy(array('userA' => $uid));

        $friends_b = $em->getRepository('AppBundle:Friends')->findBy(array('userB' => $uid));

        $friend_ids = array();

        foreach($friends_a as $fa){
            $friend_ids[] = $fa->getUserB();
        }

        foreach($friends_b as $fb){
            $friend_ids[] = $fb->getUserA();
        }

        $friends = $em->getRepository('AppBundle:Users')->findBy(array('userid' => $friend_ids));
        
        return new JsonResponse($friends);
    }
    
    /**
     * @Route("/api/users/{user_id}", name="getUser")
     */
    public function getUserAction(Request $request, $user_id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')->find($user_id);
        if(empty($user)){
            return new JsonResponse(array('error' => 404));
        }
        return new JsonResponse(array('first' => $user->getFirstName(), 'last' => $user->getLastName()));
    }
    
    /**
     * @Route("/api/messages", name="createMessage")
     */
    public function createMessage(Request $request){
        $em = $this->getDoctrine()->getManager();
        
        $from_id = $request->request->get('from_id');
        $to_id = $request->request->get('to_id');
        $content = $request->request->get('content');
        
        try{
            $message = new Messages();
            $message->setSenderId($from_id);
            $message->setReceiverId($to_id);
            $message->setContent($content);
            $em->persist($message);
            $em->flush();
        }catch(\Exception $e){
            return new JsonResponse(array("success" => $e->getMessage()));
        }
        return new JsonResponse(array("success" => true));
    }
    
    /**
     * @Route("/api/users/{user_id}/messages/{friend_id}", name="getFriendConversation")
     */
    public function getFriendConversation(Request $request, $user_id, $friend_id){
        $em = $this->getDoctrine()->getManager();
        $messages_a = $em->getRepository('AppBundle:Messages')->findBy(array('senderId' => $user_id, 'receiverId' => $friend_id), array('messageId' => 'DESC'));
                
        $messages_b = $em->getRepository('AppBundle:Messages')->findBy(array('senderId' => $friend_id, 'receiverId' => $user_id), array('messageId' => 'DESC'));

        $messages = array_merge($messages_a, $messages_b);

        $friend = $em->getRepository('AppBundle:Users')->find($friend_id);
        $name = $friend->getFirstName()." ".$friend->getLastName();
        
        usort($messages, function($a, $b)
        {
            return $a->getMessageId() > $b->getMessageId();
        });
        
        $json_response = array();
        
        foreach($messages as $msg){
            $data = array();
            //You are sender
            if($msg->getSenderId() == $user_id){
                $label = "You";
                $type = 1;
            }
            //Friend is sender
            else{
                $label = $name;
                $type = 0;
            }
            
            $data['from_id'] = $msg->getSenderId();
            $data['to_id'] = $msg->getReceiverId();
            $data['content'] = $msg->getContent();
            $data['label'] = $label;
            $data['type'] = $type;
            
            
            $json_response[] = $data;
        }
        
        return new JsonResponse($json_response);
    }
    
    
    /**
     * @Route("/actions/friends/add", name="addFriendAction")
     */
    public function addFriendAction(Request $request){
        $session = $request->getSession();
        if(!$session->has('uid')){
            return $this->redirectToRoute('loginPage');
        }
        $uid = $session->get('uid');
        $email = $request->request->get('email');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')->findOneBy(array('email' => $email));
        if(empty($user)){
            return new JsonResponse(array('code' => 404));
        }
        else{
            $user_id = $user->getUserId();
            
            //check to make sure you aren't already friends.
            $friend_check = $em->getRepository('AppBundle:Friends')->findOneBy(array('userA' => $uid, 'userB' => $user_id));
            
            $friend_check2 = $em->getRepository('AppBundle:Friends')->findOneBy(array('userA' => $user_id, 'userB' => $uid));
            
            //already friends so don't do anything
            if(!empty($friend_check) || !empty($friend_check2)){
                return new JsonResponse(array('code' => 203, 'firstName' => $user->getFirstName()));
            } 
            
            $friend = new Friends();
            $friend->setUserA($uid);
            $friend->setUserB($user_id);
            $em->persist($friend);
            $em->flush();
            return new JsonResponse(array('code' => 200, 'firstName' => $user->getFirstName(), 'lastName' => $user->getLastName()));
        }
    }
    
    /**
     * @Route("/actions/signout", name="signOutAction")
     */
    public function signOutAction(Request $request){
        $session = $request->getSession();
        $session->remove('uid');
        return $this->redirectToRoute('loginPage');
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
            $user = $em->getRepository('AppBundle:Users')->findOneBy(array('email' => $email));
            if(empty($user)){
                return $this->render('login.html.twig', array('error' => "Invalid email or password"));
            }
            //TO-DO: implement session timeout after ~5 minutes
            if($password == $user->getPassword()){
                $uid = $user->getUserId();
                $session = $request->getSession();
                $session->set('uid', $uid);
                return $this->redirectToRoute('dashboardPage');
            }
            else{
                //need to set this to a redirect with parameters
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
            //todo: need to change username to email on user entity
            $em = $this->getDoctrine()->getManager('default');
            $u = new Users();
            $u->setEmail($email);
            $u->setPassword($password);
            $u->setFirstname($first);
            $u->setLastName($last);
            $u->setMajor($major);
            $em->persist($u);
            $em->flush();
            $uid = $u->getUserId();
            $session = $request->getSession();
            $session->set('uid', $uid);
            return $this->redirectToRoute('dashboardPage');
        }
    }
}
