<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserNoPasswordType;
use App\Form\UserType;
use App\Security\ChangePassword;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            $user->addRole("ROLE_USER");

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'The user has been created.');

            return $this->redirectToRoute('welcome');
        }

        return $this->render('security/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/profile", name="profile")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile(): Response
    {
        return $this->render('security/profile.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileEdit(Request $request): Response
    {
        $form = $this->createForm(UserNoPasswordType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Your profile has been updated.');

            return $this->redirectToRoute('profile');
        }

        return $this->render('security/edit.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/change-password", name="profile_password_edit")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function changeUserPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();

            $encodedPassword = $passwordEncoder->encodePassword($user, $changePasswordModel->getNewPassword());
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('logout');
        }

        return $this->render('security/change_password.html.twig', ['form' => $form->createView()]);
    }

    /*
     * @Route("hidden/{role}", name="profile_add_role")
     * @Security("has_role('ROLE_USER')")
     *
     * @param string $role
     * @return Response
     */
    /*
    public function addRole(string $role): Response
    {
        if($role)
        {
            $user = $this->getUser();
            $user->addRole($role);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The role '.$role.' has been added.');
        }

        return $this->redirectToRoute('profile');
    }
    */
}
