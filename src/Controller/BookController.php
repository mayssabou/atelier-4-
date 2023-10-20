<?php

namespace App\Controller;


use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{

    /* #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    } */

    #[Route('/book/get/all', name:'app_book')]
    public function getAllBook(BookRepository $repo){ 
        $totalBookPublished=0;
        $totalBookUnpublished=0;
       $books=$repo->findAll();
       
        
        foreach ($books as $book) {
            if($book->isPublished()){
                $totalBookPublished+=1;
            }else{
                $totalBookUnpublished+=1;
            }
        
        } 
        return $this->render('book/index.html.twig',['books'=>$books,'totalBooksPub'=> $totalBookPublished,
        'totalBooksUnpub'=> $totalBookUnpublished ]);
    }


    #[Route('/addbook', name: 'app_book_add')]
    public function addBook(Request $request,ManagerRegistry $manager)
    {
        $book=new Book();
        $form=$this->createForm(BookType::class,$book);
        $form->handleRequest($request);

        //$book->setRef($form->getData()->getRef()); 

        if($form->isSubmitted()){
            $book->setPublished(true);
        $manager->getManager()->persist($book);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_book');
        }
        return $this->render('book/addBook.html.twig', [  //create vue b renderForm
            'f'=>$form->createView(),            //create vue bch ybadel form l html ou renderForm
        ]);
    }

    #[Route('/book/delete/{ref}', name:'app_book_delete')]
    public function deleteAuthor($ref,ManagerRegistry $manager ,BookRepository $repo){
       $book=$repo->find($ref);
       $manager->getManager()->remove($book);
       $manager->getManager()->flush();
       return $this->redirectToRoute('app_book');
    }

    #[Route('book/update/{ref}',name:'app_book_update')]
    public function updateBook(ManagerRegistry $manager,$ref,BookRepository $rep,Request $req){
        $book = $rep->find($ref);
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_book');
        }
        return $this->render('book/addBook.html.twig',['f'=>$form->createView()]); //create vue bch ybadel form l html ou renderForm
    }

    #[Route('book/show/{ref}',name:'app_book_show')]
    public function authorDetails($ref,BookRepository $rep): Response
    {
        $book = $rep->find($ref);
        return $this->render('book/showBook.html.twig', [
            
            'book'=>$book,
        ]);
    } 
}
