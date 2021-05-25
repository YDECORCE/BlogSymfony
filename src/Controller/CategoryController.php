<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
      /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request)
    {
        // code pour limiter l'accès à la méthode aux role Admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

    	$category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($category); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete
            $this->addFlash('info','Categorie créée');            
            return $this->redirectToRoute('admin');
        }

    	return $this->render('category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
     /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function edit(Category $category, Request $request)
    {
         // code pour limiter l'accès à la méthode aux role Admin
         $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('info','Catégorie modifiée');            
            return $this->redirectToRoute('admin');
        }

    	return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/category/remove/{id}", name="category_remove")
     */
    public function remove(Category $category)
    {
    	$em=$this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('info','Catégorie supprimée');            
            return $this->redirectToRoute('admin');
    }
}
