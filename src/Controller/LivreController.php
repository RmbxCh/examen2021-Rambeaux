<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LivreController extends AbstractController
{
    //---------------------
    // Afficher un livre
    //---------------------
    /**
     * @Route("/livre/show/{id}", name="livre_show")
     */
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', ['livre' => $livre]);
    }

    //---------------------
    // Ajouter un livre
    //---------------------
    /**
     * @Route("/livre/create", name="livre_create")
     */
    public function create(EntityManagerInterface $em, Request $request)
    {
        $livre = new Livre();
        $livre->setDateAjout(new DateTime('now'));
        $livre->setVotes(0);

        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $livre = $form->getData();

            $em->persist($livre);
            $em->flush();

            return $this->redirectToRoute('livre_show', ['id' => $livre->getId()]);

        }

        return $this->render('livre/create.html.twig', [ 'formLivre' => $form->createView()]);
    }

    //---------------------
    // Modifier un livre
    //---------------------
    /**
     * @Route("/livre/edit/{id}", name="livre_edit")
     */
    public function edit(Livre $livre, EntityManagerInterface $em, Request $request){

        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $livre = $form->getData();

            $em->persist($livre);
            $em->flush();

            return $this->redirectToRoute('livre_show', ['id' => $livre->getId()]);
        }

        return $this->render('livre/create.html.twig', [
            'formLivre' => $form->createView()
        ]);
    }

    //---------------------
    // Supprimer un livre
    //---------------------
    /**
     * @Route("/livre/delete/{id}", name="livre_delete")
     */
    public function delete(Livre $livre, EntityManagerInterface $em){
        $em->remove($livre);
        $em->flush();

        return $this->redirectToRoute('home');
    }

}
