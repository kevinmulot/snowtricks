<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoFormType;
use App\Repository\VideoRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @var VideoRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * VideosController constructor.
     * @param VideoRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(VideoRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/video/add/{id}", name="video_new")
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addVideo(Trick $trick, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $video = new Video();

        $form = $this->createForm(VideoFormType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $video->getUrl();
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $youtubeMatch);
            preg_match('%(?:https?://)?(?:www\.)?(?:dai\.ly/|dailymotion\.com(?:/video/|/embed/|/embed/video/))([^^&?/ ]{7})%i', $url, $dailymotionMatch);
            if (!empty($youtubeMatch) || !empty($dailymotionMatch)) {
                if (!empty($youtubeMatch)) {
                    $newUrl = "https://www.youtube.com/embed/$youtubeMatch[1]";
                } elseif (!empty($dailymotionMatch)) {
                    $newUrl = "https://www.dailymotion.com/embed/video/$dailymotionMatch[1]";
                }
                $video->setUrl($newUrl);
                $video->setTrick($trick);
                $this->em->persist($video);
                $this->em->flush();
                $this->addFlash('success', 'Video correctly update');
                return $this->redirectToRoute('trick_edit', array('id' => $trick->getId()));
            }
            $this->addFlash('danger', 'URL must be from Youtube or DailyMotion');
            return $this->redirectToRoute('video_new', array('id' => $trick->getId()));
        }

        return $this->render('media/videos.html.twig', ['videoForm' => $form->createView(), 'trick' => $trick]);
    }

    /**
     * @Route("/trick/video/delete/{id}", name="video_delete")
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteVideo(Video $video)
    {
        $this->em->remove($video);
        $this->em->flush();
        $id = $video->getTrick()->getId();

        return $this->redirectToRoute('trick_edit', array('id' => $id));
    }

    /**
     * @Route("/trick/video/edit/{id}", name="video_edit")
     * @param Video $video
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateVideo(Request $request, Video $video)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(VideoFormType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $video->getUrl();
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $youtubeMatch);
            preg_match('%(?:https?://)?(?:www\.)?(?:dai\.ly/|dailymotion\.com(?:/video/|/embed/|/embed/video/))([^^&?/ ]{7})%i', $url, $dailymotionMatch);
            if (!empty($youtubeMatch) || !empty($dailymotionMatch)) {
                if (!empty($youtubeMatch)) {
                    $newUrl = "https://www.youtube.com/embed/$youtubeMatch[1]";
                } elseif (!empty($dailymotionMatch)) {
                    $newUrl = "https://www.dailymotion.com/embed/video/$dailymotionMatch[1]";
                }
                $video->setUrl($newUrl);
                $this->em->flush();
                $this->addFlash('success', 'Video correctly update');
                return $this->redirectToRoute('trick_edit', array('id' => $video->getTrick()->getId()));
            }
        }
        return $this->render('media/videos.html.twig', ['videoForm' => $form->createView()]);
    }
}
