<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 *@Route("/blog" ,name="blog")
 */
class BlogController extends AbstractController
{



    /**
     * @Route("/{page}", name="blog_list", requirements={"page"="\d+"},defaults={"page"=1})
     */
    public function list($page=1,Request $request)
    {
        $limit = $request->get('limit',10);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();
        return $this->json(array(
            'page' => $page,
            'limit'=>$limit,
            'data' => array_map(function (BlogPost $item) {
                return $this->generateUrl('blogblog_by_slug', ['slug' => $item->getSlug()]);
            }, $items)
        ));
    }

    /**
     * @Route("/post/{post}", name="blog_by_id", requirements={"post"="\d+"},methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post)
    {
        
        return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug" ,methods={"GET"})
     *  @ParamConverter("post", class="App:BlogPost", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug($post)
    {

        return $this->json($post);
    }

    /**
     * @Route("/add", name="add_post",methods={"POST"})
     */
    public function add(Request $request){
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();
        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="delete_post",methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

