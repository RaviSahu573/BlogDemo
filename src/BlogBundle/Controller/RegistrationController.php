<?php
namespace BlogBundle\Controller;

use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return
     */
    public function registerAction(Request $request)
    {
        // Create a new blank user and process the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            //print_r( $user->getRoles);exit;
            // Set their role
            $user->setRoles(array('ROLE_ADMIN'));

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'You have successfully added '
                .$user->getUsername().' to the database!');
            return $this->redirect($this->generateUrl('login'));
            //return $this->redirectToRoute('login');
        }

        return $this->render('BlogBundle:security:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}