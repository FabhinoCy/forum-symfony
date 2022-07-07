<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire/ajout/{id}', name: 'commentaire_create')]
    public function index(ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $article = $articleRepository->findOneBy(['id' => $id]);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($this->getUser());
            $commentaire->setArticle($article);
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('commentaire/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/commentaire/reponse/{id}', name: 'commentaire_answer')]
    public function answer(CommentaireRepository $commentaireRepository, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $commentaire = $commentaireRepository->findOneBy(['id' => $id]);
        $answer = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $answer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setUser($this->getUser());
            $answer->setCommentaire($commentaire);
            $entityManager->persist($answer);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('commentaire/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
