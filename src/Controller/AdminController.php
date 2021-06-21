<?php

namespace App\Controller;

use App\Entity\user;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_AList')]
    public function Aindex(Request $req, ArticleRepository $art )
    {
        $articles = $art->findAll();
        return $this->render('admin/Annonce.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/admin/Annonce/delete/{id}', name: 'admin_ADelete')]
    public function Adelete( $id, FlashyNotifier $flashy )
    {
        $annonce = $this->getDoctrine()->getRepository('App:Article')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();      
        
        $flashy->success('Annonce Succesfully Removed');

        return $this->redirectToRoute('admin_AList');
    }

    #[Route('/admin/Categories', name: 'admin_CList')]
    public function Cindex(Request $req, CategoryRepository $cat )
    {
        $categories = $cat->findAll();
        return $this->render('admin/Category.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/Users', name: 'admin_UList')]
    public function Uindex(Request $req, UserRepository $user )
    {
        $users = $user->findAll();
        return $this->render('admin/User.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/User/delete/{id}', name: 'admin_UDelete')]
    public function Udelete( $id, FlashyNotifier $flashy )
    {
        $user = $this->getDoctrine()->getRepository('App:User')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();      
        
        $flashy->success('User Succesfully Removed');
        return $this->redirectToRoute('admin_UList');
    }
}
