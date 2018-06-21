<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Users;
use App\Entity\Todos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\UsersLocaleType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersController extends Controller
{

    /**
     * @Route("/profile", name="profile")
     * @Security("has_role('ROLE_USER')")
     */
    public function profile(UserInterface $user)
    {
        //return new Response(var_dump($_SESSION));
        $request = Request::createFromGlobals();
        if ($request->isMethod('POST')) {
            $params = array();
            $content = $request->getContent();
            
            if (!empty($content)){
                $params = json_decode($content, true);
                //return new Response(var_dump($content));
                if (isset($params['locale']) && isset($params['_token'])){
                    //return new Response(var_dump($params));
                    $submittedToken = $params['_token'];

                    // 'delete-item' is the same value used in the template to generate the token
                    if ($this->isCsrfTokenValid('form_locale', $submittedToken)) {
                        // ... do something, like deleting an object
                        $user->setLocale($params['locale']);
                        $this->get('session')->set('_locale', $params['locale']);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                        
                        return new Response("Updated");
                    }                   
                    
                }

            }
        }
        $defaults = array('locale' => $user->getLocale());
        
        $form = $this->createForm(UsersLocaleType::class, $defaults);
        /*$form = $this->createFormBuilder($defaults, $options = array(
                // enable/disable CSRF protection for this form
                'csrf_protection' => true,
                // the name of the hidden HTML field that stores the token
                'csrf_field_name' => '_token',
                // an arbitrary string used to generate the value of the token
                // using a different string for each form improves its security
                'csrf_token_id'   => 'form_locale'
            ))
            ->add('locale', ChoiceType::class, array(
                'choices' => array(
                    'English' => 'en',
                    'Spanish' => 'es_ES',
                ),
                'attr' => array('onChange' => 'saveLocale(this)'),
            ))
            ->getForm();
            */

        $count = $this->getDoctrine()->getRepository(Todos::class)->countTodos($user->getIdUser());

        return $this->render('users/profile.html.twig', [
            'total_todos' => $count[1],
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        //$translator = $this->get('translator');
        //$translator->setLocale('es_ES');
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('dashboard');
        }else{
            return $this->render('static/home.html.twig');
        }
    }

    /**
     * @Route("/dash", name="dashboard")
     * @Security("has_role('ROLE_USER')")
     */
    public function dash()
    {
        return $this->render('users/dashboard.html.twig', [
            'email' => 'asdr1'
        ]);
    }

    /**
     * @Route("/api/todos/list", name="api_todos_list")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET","POST"})
     */
    public function api_todos_list(UserInterface $user)
    {
        $request = Request::createFromGlobals();
        // Falta validar
        $params = array();
        $content = $request->getContent();
        
        if (!empty($content)){
            $params = json_decode($content, true); // 2nd param to get as array
            $showAll=$params["showAll"];
        } else { $showAll = $user->getConfigShowAll(); }

        //return JSON object with TODos data
        if ($showAll==0){
            $todos = $this->getDoctrine()->getRepository(Todos::class)->findUncompleted($user->getIdUser());
        }
        else {
            $todos = $this->getDoctrine()->getRepository(Todos::class)->myfindAll($user->getIdUser());
        }

        if (!$todos) {
            throw $this->createNotFoundException(
                'No todos found '
            );
        }
        $array_data=array();
        foreach ($todos as $key => $value) {
            $array_data[$key]=array(
                "id_todo" => $todos[$key]->getIdTodo(),
                "text" => $todos[$key]->getText(),
                "details" => $todos[$key]->getDetails(),
                "date_creation" => $todos[$key]->getDateCreation(),
                "date_start" => $todos[$key]->getDateStart(), //->format('Y-m-d'),
                "date_finish" => $todos[$key]->getDateFinish(),
                "creator" => $todos[$key]->getCreator(),
                "state" => $todos[$key]->getState());
        }
        //return new Response('Todos: '.var_dump($array_data));
        //return new Response(new JsonResponse($array_data)); //enviaba headers como body
        //return new Response(new JsonResponse($todos[0]->getText()));
        //return new Response('Todos: '.var_dump($todos));

        $response = new Response();
        $response->setContent(json_encode($array_data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/todos/create", name="api_todos_create")
     * @Security("has_role('ROLE_USER')")
     */
    public function api_todos_create(UserInterface $user)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();
        $translator= $this->get('translator');
        $todo = new Todos();
        $todo->setText($translator->trans("New Todo..."));
        $todo->setDateStart(new \DateTime("now"));
        $todo->setCreator($user->getIdUser());
        $todo->setState('0');

        $entityManager->persist($todo);
        $entityManager->flush();
        
        $response = new Response();
        $response->setContent(json_encode([
            "id_todo" => $todo->getIdTodo(),
            "text" => $todo->getText(),
            "details" => $todo->getDetails(),
            "date_creation" => $todo->getDateCreation(),
            "date_start" => $todo->getDateStart(), //->format('Y-m-d'),
            "date_finish" => $todo->getDateFinish(),
            "creator" => $todo->getCreator(),
            "state" => $todo->getState()
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/todos/edit", name="api_todos_edit")
     * @Security("has_role('ROLE_USER')")
     */
    public function api_todos_edit(UserInterface $user,ValidatorInterface $validator)
    {   
        $request = Request::createFromGlobals();
        // Falta validar
        $params = array();
        $content = $request->getContent();
        
        if (!empty($content)){
            $params = json_decode($content, true); // 2nd param to get as array
            if (isset($params['id_todo'])){ $id_todo=$params["id_todo"]; } else { return new Response("No data"); }
            if (isset($params['text'])){ $text=$params["text"]; }
            if (isset($params['details'])){ $details=$params["details"]; }
            if (isset($params['date_start'])){ 
                $date_start=new \Datetime($params["date_start"]["date"]);
            }
            if (isset($params['date_finish'])){ 
                $date_finish=new \Datetime($params["date_finish"]["date"]);
            }
            if (isset($params['state'])){ $state=$params["state"]; }

        } else { return new Response("No data"); }

        $entityManager = $this->getDoctrine()->getManager();
        $todo = $entityManager->getRepository(Todos::class)->findOneByIdTodo($id_todo,$user->getIdUser());

        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id_todo '.$id_todo
            );
        }else{            
            if (isset($params['text'])){ $todo->setText($text); }
            if (isset($params['details'])){ $todo->setDetails($details); }
            if (isset($params['date_start'])){ $todo->setDateStart($date_start); }
            if (isset($params['date_finish'])){ $todo->setDateFinish($date_finish); }
            if (isset($params['state'])){ $todo->setState($state); }            

            //validate data, (when using forms Symfony does it automatically) 
            $errors = $validator->validate($todo);
            if (count($errors) > 0) {
                /*
                * Uses a __toString method on the $errors variable which is a
                * ConstraintViolationList object. This gives us a nice string
                * for debugging.
                */
                $errorsString = (string) $errors;

                return new Response($errorsString);
            }

            $entityManager->persist($todo);
            $entityManager->flush();
            return new Response("Updated");
        }
        return null;
    }

     /**
     * @Route("/api/todos/delete", name="api_todos_delete")
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET","POST"})
     */
    public function api_todos_delete(UserInterface $user)
    {
        $request = Request::createFromGlobals();
        // Falta validar
        $params = array();
        $content = $request->getContent();
        
        if (!empty($content)){
            $params = json_decode($content, true); // 2nd param to get as array
            if (isset($params['id_todo'])){ $id_todo=$params["id_todo"]; } else { return new Response("No data"); }
        } else { return new Response("No data"); }

        $todo = $this->getDoctrine()->getRepository(Todos::class)->findOneByIdTodo($id_todo, $user->getIdUser());

        if (!$todo) { throw $this->createNotFoundException('No todo found '); }
        else{
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->remove($todo);
            $entityManager->flush();            
            return new Response("Deleted");
        }
    }

    /**
     * @Route("/api/users/config", name="api_users_config")
     * @Security("has_role('ROLE_USER')")
     */
    public function api_users_config(UserInterface $user){
        //if get send json data
        //if post get data and edit (password username and email not changeable)
        $request = Request::createFromGlobals();
        if ($request->isMethod('GET')){
            //send data
            $response = new Response();
            $response->setContent(json_encode([
                "numRows" => $user->getConfigNumRows(),
                "showAll" => $user->getConfigShowAll(),
                "showDateStart" => $user->getConfigShowDateStart(),
                "showDateFinish" => $user->getConfigShowDateFinish(),
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }elseif ($request->isMethod('POST')) {
            // Falta validar
            $params = array();
            $content = $request->getContent();
            
            if (!empty($content)){
                $params = json_decode($content, true);

                if (isset($params['numRows'])){ $numRows=$params["numRows"]; $user->setConfigNumRows($numRows);}
                if (isset($params['showAll'])){ $showAll=$params["showAll"]; $user->setConfigShowAll($showAll);}
                if (isset($params['showDateStart'])){ $showDateStart=$params["showDateStart"]; $user->setConfigShowDateStart($showDateStart); }
                if (isset($params['showDateFinish'])){ $showDateFinish=$params["showDateFinish"]; $user->setConfigShowDateFinish($showDateFinish); }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return new Response("Updated");

            } else { return new Response("No data"); }

            }   
        return null;        
    }


    /**
     * @Route("/api/users/logged", name="api_users_logged")
     * @Security("has_role('ROLE_USER')")
     */
    public function api_users_logged(){
        return new Response("Connected");
    }
}
