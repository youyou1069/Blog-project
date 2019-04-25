<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Entity\Category;
use App\Entity\Comment;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeExtensionInterface;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        // en passant les arguments dans la fonction et avec l'injection de dependance synfony est cable de chercher directement $repo
        // cette ligne n'est plus necessaire:
        //  $repo=$this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }


    //creation d'une seule methode pour inseret mttre a jour un article
    //il faut deux routes
    //creation d'une seule methode pour inseret mttre a jour un article
    //il faut deux routes
    /**
     * @Route ("/blog/new", name="blog_create")
     * @Route ("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager)
    {
        if (!$article){
            $article = new Article();
        }

        //dump($request);

//        $form=$this->createFormBuilder($article)
//                    ->add('title')
//                    ->add('content')
//                    ->add('image')
//
//                    ->getForm();

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        dump($article);
        if($form->isSubmitted()&& $form->isValid()){
            //$article->setCreatedAt(new \DateTime('now'));
            //constructeur dans la classe article pour ajouter une date
            //dump($article);
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id'=> $article->getId()]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' =>$form->createView(),
            //si l'article exist mode edit (true) sinon (false)
            'editMode'=> $article->getId()!== null
        ]);

    }
    //insertion formulaire et insertion dans bdd
    ///**
     //* @Route ("blog/new", name="blog_create")
    // */
    //public function create (Request $request, ObjectManager $manager)
    //{
        //dump($request);
      //  $article = new Article();
      //  $form=$this->createFormBuilder($article)
      //      ->add('title')
       //     ->add('content')
      //      ->add('image')

      //      ->getForm();

    //    $form->handleRequest($request);
    //    dump($article);
     //   if($form->isSubmitted()&& $form->isValid()){
     //       //$article->setCreatedAt(new \DateTime('now'));
            //constructeur dans la classe article pour ajouter une date
     //       //dump($article);
     //       $manager->persist($article);
     //       $manager->flush();

       //     return $this->redirectToRoute('blog_show', ['id'=> $article->getId()]);
     //   }

     //   return $this->render('blog/create.html.twig', ['formArticle' =>$form->createView()
     //   ]);

    //}

//    /**
//     *  @Route ("blog/{id}", name="blog_show")
//     */
//    public function show (ArticleRepository $repo, $id)
//    {
//         en passant les arguments dans la fonction et avec l'injection de dependance synfony est cable de chercher directement $repo
//         Cette ligne n'est plus necessaire:
//
//
//        $repo=$this->getDoctrine()->getRepository(Article::class);
//
//        $article=$repo->find($id);
//        return $this->render('blog/show.html.twig', [ 'article'=> $article
//            ] );
//
//    }

    // On peut egamelent passer directent à une autre étape -> demander directement à symfony de chercher un article de la Bdd en .
    /**
     *  @Route ("blog/{id}", name="blog_show")
     */
    public function show (Article $article)
    {
        return $this->render('blog/show.html.twig', [ 'article'=> $article   ] );

    }









}
