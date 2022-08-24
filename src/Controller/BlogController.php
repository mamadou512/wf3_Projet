<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use App\Security\Voter\BlogPostVoter;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('', name: 'blog_index')]
    public function index(
        BlogPostRepository $blogPostRepository,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
        $pagination = $paginator->paginate(
            $query = $blogPostRepository->findPublicPosts(),
            $request->query->getInt( 'page', 1),
            4
        );

        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/{slug}', name: 'blog_view')]
    #[IsGranted(BlogPostVoter::VIEW, subject: 'post')]
    public function view(BlogPost $post): Response
    {
        // Retourne une 404 si l'article est en Draft
       // if ($post->getStatus() !==Blogpost::STATUS_ACTIVE) {
      //    throw $this->createNotFoundException();
      //  }

        return $this->render('blog/view.html.twig', [
            'post' => $post
            
        ]);
    }
}
