<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\PictureFormType;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * TrickController constructor.
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/trick/media/{id}/{statut}", name="picture_new")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, Trick $trick, FileUploader $fileUploader)
    {
        $form = $this->createForm(PictureFormType::class);
        $form->handleRequest($request);
        $statut = $request->get('statut');
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form['picture']->getData();
            $picture = new Picture();
            $pictureFileName = $fileUploader->upload($pictureFile);
            $picture->setName($pictureFileName);
            $picture->setTrick($trick);
            if ($statut === 'main') {
                $picture->setStatut('main');
                $trick->setMainPicture($pictureFileName);
                $this->em->persist($picture);
                $this->em->flush();

                return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
            }
            $this->em->persist($picture);
            $this->em->flush();
        }
        $pictures = $trick->getPicture();

        return $this->render('media/pictures.html.twig', ['pictureForm' => $form->createView(), 'trick' => $trick, 'pictures' => $pictures, 'statut' => $statut]);
    }

    /**
     * @Route("/trick/picture/delete/{id}/{statut}", name="picture_delete")
     * @param Picture $picture
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deletePicture(Request $request, Picture $picture, FileUploader $fileUploader)
    {
        $statut = $request->get('statut');
        $fileUploader->remove($picture->getName());
        if ($statut === 'main') {
            $trick = $picture->getTrick();
            $trick->setMainPicture('default.jpg');
        }
        $this->em->remove($picture);
        $this->em->flush();
        $id = $picture->getTrick()->getId();

        return $this->redirectToRoute('trick_edit', array('id' => $id));
    }

    /**
     * @Route("/trick/picture/edit/{id}/{statut}", name="picture_edit")
     * @param Picture $picture
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePicture(Request $request, Picture $picture, FileUploader $fileUploader)
    {
        $statut = $request->get('statut');
        $form = $this->createForm(PictureFormType::class);
        $form->handleRequest($request);
        $oldName = $picture->getName();
        $trick = $picture->getTrick();
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form['picture']->getData();
            $pictureFileName = $fileUploader->upload($pictureFile);
            if ($statut === 'main') {
                $trick->setMainPicture($pictureFileName);
            }
            $picture->setName($pictureFileName);
            $fileUploader->remove($oldName);
            $this->em->flush();

            return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
        }
        return $this->render('media/pictures.html.twig', ['pictureForm' => $form->createView(), 'trick' => $trick, 'pictures' => $picture, 'statut' => $statut]);
    }
}
