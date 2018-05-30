<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @Method({"GET","POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $user = new Users();

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('nickname', TextType::class)
            ->add('name', TextType::class)
            ->add('locale', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Register'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            //$encoder = $this->get('security.encoder_factory')->getEncoder($user);
            
            //Usuario
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $user->setTokenVerificationEmail(base64_encode(random_bytes(10)));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            //send email for validating account
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('migweb.com@gmail.com')
            ->setTo('katarian.com@gmail.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    array('email' => $user->getEmail(),'token' => $user->getTokenVerificationEmail())
                ),
                'text/html'
            )
            /*
            * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);

            //return new Response("User registered");
            //return $this->redirectToRoute('dashboard');
            return $this->render('security/registered.html.twig', array('email' => $user->getEmail()));
        }
        
        return $this->render('security/register.html.twig', array('form' => $form->createView()));
        
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        /*
        if ($request->request->get('email') !== null){
            //return new Response("Email: ".$request->request->get('email'));

            //## validate post data and check db
            $post_email=$request->request->get('email');
            $post_password=$request->request->get('password');

            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email' => $post_email]);

            if (!$user) {
                throw $this->createNotFoundException('No user found for email '.$post_email);
            }else { return new Response("Logged with Email: ".$post_email);}

        };
        return $this->render('Security/login.html.twig', [
            'controller_name' => 'UsersController',
        ]);
        */

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));


    }

     /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/verify_email", name="verify_email")
     */
    public function verify_email(Request $request)
    {
        //return new Response(strlen(base64_encode(random_bytes(10))));
        $email=$request->query->get('email');
        $token=$request->query->get('token');

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Users::class)->findOneByEmail($email);
        $verified=false;

        if ($user){
            if ($token==$user->getTokenVerificationEmail()){
                $verified=true;
                $user->setemailVerified(true);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        
        return $this->render('security/verify_email.html.twig', array(
            'email' => $email, 'verified' => $verified
        ));
    }
    
}
