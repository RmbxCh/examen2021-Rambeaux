<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    //---------------------
    // Afficher tous les genres
    //---------------------
    /**
     * @Route("/genre", name="genre")
     */
    public function index(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->findAll();

        return $this->render('genre/index.html.twig', [ 'genres' => $genres
        ]);
    }
    //---------------------
    // Ajouter un genre
    //---------------------
    /**
     * @Route("/genre/create", name="genre_create")
     */
    public function create(EntityManagerInterface $em, Request $request){
        $genre = new Genre();

        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $genre = $form->getData();
            $em->persist($genre);
            $em->flush();

            return $this->redirectToRoute('genre');
        }

        return $this->render('genre/create.html.twig', [ 
            'formGenre' => $form->createView()
        ]);
    }

    //---------------------
    // Modifier un genre
    //---------------------
    /**
     * @Route("/genre/edit/{id}", name="genre_edit")
     */
    public function edit($id, GenreRepository $genreRepository, EntityManagerInterface $em, Request $request){
        $genre = $genreRepository->find($id);

        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $genre = $form->getData();
            $em->persist($genre);
            $em->flush();

            return $this->redirectToRoute('genre');
        }

        return $this->render('genre/create.html.twig', [
            'formGenre' => $form->createView()
        ]);
    }

}
