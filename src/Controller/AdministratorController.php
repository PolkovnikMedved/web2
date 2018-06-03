<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministratorController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/users", name="admin_users")
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    public function getUsers(UserRepository $userRepository) :Response
    {
        return $this->render('admin/users.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/admin/{userId}/user", name="admin_user")
     *
     * @param UserRepository $userRepository
     * @param int $userId
     * @return Response
     */
    public function getOneUser(int $userId, UserRepository $userRepository) :Response
    {
        return $this->render('admin/user.html.twig', ['user' => $userRepository->find($userId)]);
    }

    /**
     * @Route("/admin/{userId}/last-comments", name="admin_user_comments")
     *
     * @param UserRepository $userRepository
     * @param CommentRepository $commentRepository
     * @param int $userId
     * @return Response
     */
    public function getLastUserComments(int $userId, UserRepository $userRepository, CommentRepository $commentRepository) :Response
    {
        $user = $userRepository->find($userId);

        if(!$user){
            throw $this->createNotFoundException("The user that you are looking for doesn't exists.");
        }

        $comments = $commentRepository->getLastModified($user);

        return $this->render('admin/user-comments.html.twig', ['comments' => $comments]);
    }

    /**
     * @Route("/admin/{userId}/set-blacklisted", name="admin_user_blacklist")
     *
     * @param int $userId
     * @param UserRepository $userRepository
     * @return Response
     */
    public function setBlacklisted(int $userId, UserRepository $userRepository) :Response
    {
        $user = $userRepository->find($userId);

        if(!$user){
            throw $this->createNotFoundException("The user that you are looking for doesn't exists.");
        }

        if(!$user->getBlacklisted())
        {
            $user->setBlacklisted(true);
        } else {
            $user->setBlacklisted(false);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_users');
    }
}