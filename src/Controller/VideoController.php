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

/**
 * Class VideoController
 * @package App\Controller
 */
class VideoController extends AbstractController
{
    /**
     * @var VideoRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $ema;

    /**
     * VideosController constructor.
     * @param VideoRepository $repository
     * @param ObjectManager $ema
     */
    public function __construct(VideoRepository $repository, ObjectManager $ema)
    {
        $this->repository = $repository;
        $this->ema = $ema;
    }

    /**
     * @Route("/admin/video/add/{slug}", name="video_new")
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
                $this->ema->persist($video);
                $this->ema->flush();
                $this->addFlash('success', 'Video correctly added');

                return $this->redirectToRoute('trick_edit', array('slug' => $trick->getSlug()));
            }
            $this->addFlash('danger', 'URL must be from Youtube or DailyMotion');

            return $this->redirectToRoute('video_new', array('slug' => $trick->getSlug()));
        }

        return $this->render('media/videos.html.twig', ['videoForm' => $form->createView(), 'trick' => $trick]);
    }

    /**
     * @Route("/admin/trick/video/delete/{id}", name="video_delete")
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteVideo(Video $video)
    {
        $this->ema->remove($video);
        $this->ema->flush();
        $slug = $video->getTrick()->getId();

        return $this->redirectToRoute('trick_edit', array('slug' => $slug));
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
                $this->ema->flush();
                $this->addFlash('success', 'Video correctly updated');

                return $this->redirectToRoute('trick_edit', array('slug' => $video->getTrick()->getSlug()));
            }
        }
        return $this->render('media/videos.html.twig', ['videoForm' => $form->createView()]);
    }
}
