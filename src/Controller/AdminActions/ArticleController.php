<?php

namespace App\Controller\AdminActions;

use App\Controller\NotificationsController;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\RatingRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/article')]
class ArticleController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findBy([],[
                'CreatedAt' => 'desc'
            ]),
        ]);
    }

    #[Route('/me', name: 'article_me', methods: ['GET'])]
    public function Meindex(ArticleRepository $articleRepository): Response
    {
        return $this->render('home/index2.html.twig', [
            'articles' => $articleRepository->findBy(['User' => $this->getUser()],[
                'CreatedAt' => 'desc'
            ]),
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotificationsController $notifie, UserRepository $user): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new DateTime());
            
            $file = $form->get('Picture')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('Article_directory'), $fileName);

            $article->setPicture($fileName);
            $article->setUser($this->security->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $target = $article->getUser()->getName()." ".$article->getUser()->getPrename();
            $NotificationContent = " Has Upload New Mission ".$article->getTitle();
            foreach ($article->getUser()->getFollowers() as $value) {
                if ($value != null) {
                    $notifie->sendNotification($user->find($value), $NotificationContent, $target);
                }
            }

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        if($form->isSubmitted() && !$form->isValid()){
            return new Response($this->renderView('article/new.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
            ]),Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET', 'POST'])]
    public function show(Article $art, Request $req, $id, RatingRepository $rat): Response
    {
        $qp = $rat->findBySum($art);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
    
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

            $comment->setCreatedAt(new DateTime());
            $comment->setArticle($art);
            $comment->setUser( $this->security->getUser() );
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            
            return $this->redirectToRoute("article_show", ['id' => $art->getId() ], Response::HTTP_SEE_OTHER);
        }
        if($form->isSubmitted() && !$form->isValid()){
            return new Response($this->renderView('article/show.html.twig', [
                'article' => $art,
                'forms' => $form->createView(),
            ]),Response::HTTP_UNPROCESSABLE_ENTITY);
        }
       $cont ="";
        if ((int)$qp > 0) {
            $averageRate = (int)$qp / $rat->count([ 'Article' => $art]);
            $msg = number_format((float)$averageRate,1,'.','');
            $cont = "Rate: ".$msg."/5";
        }else{
            $cont = "Be the first Rate";
        }
        return $this->render('article/show.html.twig', [
            'article' => $art,
            'Average' => $cont,
            'forms' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, NotificationsController $notifie, UserRepository $user): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Picture')->getData();
            if($file == null){
                $fileName = $article->getPicture();    
            }else{
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('Article_directory'), $fileName);
            }

            $article->setPicture($fileName);

            $this->getDoctrine()->getManager()->flush();
            $target = $article->getUser()->getName()." ".$article->getUser()->getPrename();
            $NotificationContent = " Has Updated his Mission ".$article->getTitle();
            foreach ($article->getUser()->getFriends() as $value) {
                if ($value != null) {
                    $notifie->sendNotification($user->find($value), $NotificationContent, $target);
                }
            }

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        if($form->isSubmitted() && !$form->isValid()){
            return new Response($this->renderView('user/new.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
            ]),Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
