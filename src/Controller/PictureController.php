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

/**
 * Class PictureController
 * @package App\Controller
 */
class PictureController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $ema;

    /**
     * TrickController constructor.
     * @param ObjectManager $ema
     */
    public function __construct(ObjectManager $ema)
    {
        $this->ema = $ema;
    }

    /**
     * @Route("/trick/media/{slug}/{statut}", name="picture_new")
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
            $statut = $request->get('statut');
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form['picture']->getData();
            $picture = new Picture();
            $pictureFileName = $fileUploader->upload($pictureFile);
            $picture->setName($pictureFileName);
            $picture->setTrick($trick);
            if ($statut === 'main') {
                $picture->setStatut('main');
                $trick->setMainPicture($pictureFileName);
                $this->ema->persist($picture);
                $this->ema->flush();

                return $this->redirectToRoute('trick_edit', array('slug' => $trick->getSlug()));
            }
            $this->ema->persist($picture);
            $this->ema->flush();
        }
        $pictures = $trick->getPicture();

        return $this->render('media/pictures.html.twig', ['pictureForm' => $form->createView(), 'trick' => $trick, 'pictures' => $pictures, 'statut' => $statut]);
    }

    /**
     * @Route("/admin/trick/picture/delete/{id}/{statut}", name="picture_delete")
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
        $this->ema->remove($picture);
        $this->ema->flush();
        $id = $picture->getTrick()->getId();

        return $this->redirectToRoute('trick_edit', array('id' => $id));
    }

    /**
     * @Route("/admin/trick/picture/edit/{id}/{statut}", name="picture_edit")
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
            $this->ema->flush();

            return $this->redirectToRoute('trick_edit', array('slug' => $trick->getSlug()));
        }
        return $this->render('media/pictures.html.twig', ['pictureForm' => $form->createView(), 'trick' => $trick, 'pictures' => $picture, 'statut' => $statut]);
    }
}
