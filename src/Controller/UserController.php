<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;
class UserController extends AbstractController
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/dist/img/';

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/allusers", name="allusers")
     */
    public function ShowUsers(): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $list = $repository->findAll();
        return $this->render('user/list.html.twig', [
            'liste' => $list,
        ]);
    }

    /**
     * @Route("/CreateUser", name="CreateUser")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $brochureFile = $form->get('photo')->getData();

                $Filename = md5(uniqid()) . '.' . $brochureFile->guessExtension();

                $entityManager = $this->getDoctrine()->getManager();
                $brochureFile->move($this->getParameter('directory'), $Filename);
                $user->setPhoto($Filename);
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setEtat("actif");
                $user->setIsVerified(true);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('info','User ajouté.');
                return $this->redirect('/allusers');
            }



        return $this->render(
            'user/index.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/UpdateUser/{id}", name="UpdateUser")
     */
    public function modifier(Request $request, string $id,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App\Entity\User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'There are no articles with the following id: ' . $id
            );
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('photo')->getData();

            $Filename = md5(uniqid()) . '.' . $brochureFile->guessExtension();

            $entityManager = $this->getDoctrine()->getManager();
            $brochureFile->move($this->getParameter('directory'), $Filename);
            $user->setPhoto($Filename);

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('info','User modifié.');
            return $this->redirect('/allusers');
        }

        return $this->render(
            'user/index.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/DeleteUser/{id}", name="DeleteUser")
     */

    public function DeleteUser($id)
    {
        $iduser = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($iduser);
        $em->flush();
        $this->get('session')->getFlashBag()->add('info','User supprimé.');

        return $this->redirect('/allusers');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findAll();
        if($request->isMethod('post'))
        {
            $key=$request->get('searchkey');
            $user=$em->getRepository(User::class)->findBy(array('id'=>$key));
          //  $user=$em->getRepository(User::class)->search($key);
        }
        return $this->render("user/search.html.twig",array('liste' => $user));

    }

    /**
     * @Route("/blocked", name="blocked")
     */
    public function blocked(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->findBy(array('etat'=>'off'));
        return $this->render("user/search.html.twig",array('liste' => $user));

    }
    /**
     * @Route("/trier", name="trier")
     */

    public function trie_croissant(UserRepository $repository)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->triecroissant();
        return $this->render('user/list.html.twig', [
            "liste"=>$users
        ]);
    }

    /**
     * @Route("/trierdown", name="trierdown")
     */

    public function trie_decroissant(UserRepository $repository)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->triedecroissant();
        return $this->render('user/list.html.twig', [
            "liste"=>$users
        ]);
    }


}