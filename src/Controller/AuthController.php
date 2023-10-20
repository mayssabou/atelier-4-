<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{


    #[Route('/authors', name:'author_list')]
    public $authors = [
        [
            'id' => 1,
            'picture' => '/images/Victor-Hugo.jpg',
            'username' => 'Victor Hugo',
            'email' => 'victor.hugo@gmail.com',
            'nb_books' => 100,
        ],
        [
            'id' => 2,
            'picture' => '/images/william-shakespeare.jpg',
            'username' => 'William Shakespeare',
            'email' => 'william.shakespeare@gmail.com',
            'nb_books' => 200,
        ],
        [
            'id' => 3,
            'picture' => '/images/Taha_Hussein.jpg',
            'username' => 'Taha Hussein',
            'email' => 'taha.hussein@gmail.com',
            'nb_books' => 300,
        ],
    ];

    public function list(): Response
    {
        /* $totalBook=0;
        foreach ($this->authors as $author) {
        $totalBook+=$author['nb_books'];
        } ==>il ya les deux methodes pour calculer les sommes des livres*/

         return $this->render('auth/list.html.twig', [
            'authors'=>$this->authors,
            /* 'totalBooks'=> $totalBook */
        ]);
    }



    public function authorDetails($id): Response
    {
        $id--;
        return $this->render('auth/showAuthor.html.twig', [
            
            'author'=>$this->authors[$id],
        ]);
    } 



     public function showAuth($name): Response
    {
        return $this->render('show.html.twig', [
            'controller_name' => 'AuthController','name'=>$name
        ]);
    } 


/******************************** With data base ************************************ */


    #[Route('/authorsDB/get/all', name:'author_list')]
    public function getAll(AuthorRepository $repo){ //faire l'ingection de dependence sans utiliser le constructeur pour faaire l'instance
       $authors=$repo->findAll();
       return $this->render('auth/index.html.twig',['autors'=>$authors]);
    }


    
    #[Route('/authorsDB/add', name:'add')]
    public function addAuthor(ManagerRegistry $manager){ 
       $author=new Author();
       $author->setUsername("ghaith");
       $author->setEmail("ghaith.benothmen@esprit.tn");
       $manager->getManager()->persist($author);
       $manager->getManager()->flush();
       return $this->redirectToRoute('author_list');
    }



    #[Route('/authorsDB/delete/{id}', name:'delete')]
    public function deleteAuthor($id,ManagerRegistry $manager ,AuthorRepository $repo){
       $author=$repo->findOneBy($id);
       $manager->getManager()->remove($author);
       $manager->getManager()->flush();
       return $this->redirectToRoute('author_list');
    }

}
