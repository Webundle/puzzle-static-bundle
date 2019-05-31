<?php
namespace Puzzle\StaticBundle\Controller;

use Puzzle\MediaBundle\MediaEvents;
use Puzzle\MediaBundle\Event\FileEvent;
use Puzzle\MediaBundle\Util\MediaUtil;
use Puzzle\StaticBundle\Entity\Page;
use Puzzle\StaticBundle\Form\Type\PageCreateType;
use Puzzle\StaticBundle\Form\Type\PageUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Puzzle\StaticBundle\Form\Type\TemplateUpdateType;
use Puzzle\StaticBundle\Form\Type\TemplateCreateType;
use Puzzle\StaticBundle\Entity\Template;

/**
 * @author AGNES Gnagne Cedric <cecenho55@gmail.com>
 */
class AdminController extends Controller
{
    /***
     * List pages
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listPagesAction(Request $request){
        return $this->render("AdminBundle:Static:list_pages.html.twig", array(
            'pages' => $this->getDoctrine()->getRepository(Page::class)->findBy([], ['createdAt' => 'DESC'])
        ));
    }
    
    /***
     * Show page
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showPageAction(Request $request, Page $page){
        return $this->render("AdminBundle:Static:show_page.html.twig", array(
            'page' => $page
        ));
    }
    
    /***
     * Create page
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createPageAction(Request $request) {
        $page = new Page();
        $form = $this->createForm(PageCreateType::class, $page, [
            'method' => 'POST',
            'action' => $this->generateUrl('puzzle_admin_static_page_create')
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() === true && $form->isValid() === true) {
            if ($page->getPicture() !== null) {
                $this->get('event_dispatcher')->dispatch(MediaEvents::COPY_FILE, new FileEvent([
                    'path' => $page->getPicture(),
                    'context' => MediaUtil::extractContext(Page::class),
                    'user' => $this->getUser(),
                    'closure' => function($filename) use ($page) {$page->setPicture($filename);}
                 ]));
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            
            $message = $this->get('translator')->trans('static.page.create.success', [
                '%pageName%' => $page->getName()
            ], 'static');
            
            if ($request->isXmlHttpRequest() === true) {
                return new JsonResponse($message);
            }
            
            $this->addFlash('success', $message);
            return $this->redirectToRoute('puzzle_admin_static_page_update', ['id' => $page->getId()]);
        }
        
        return $this->render("AdminBundle:Static:create_page.html.twig", array(
            'form' => $form->createView()
        ));
    }
    
    /***
     * Update page
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePageAction(Request $request, Page $page) {
        $form = $this->createForm(PageUpdateType::class, $page, [
            'method' => 'POST',
            'action' => $this->generateUrl('puzzle_admin_static_page_update', ['id' => $page->getId()])
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() === true && $form->isValid() === true) {
            if ($page->getPicture() !== null) {
                $this->get('event_dispatcher')->dispatch(MediaEvents::COPY_FILE, new FileEvent([
                    'path' => $page->getPicture(),
                    'context' => MediaUtil::extractContext(Page::class),
                    'user' => $this->getUser(),
                    'closure' => function($filename) use ($page) {$page->setPicture($filename);}
                ]));
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $message = $this->get('translator')->trans('static.page.update.success', [
                '%pageName%' => $page->getName()
            ], 'static');
            
            if ($request->isXmlHttpRequest() === true) {
                return new JsonResponse($message);
            }
            
            $this->addFlash('success', $message);
            return $this->redirectToRoute('puzzle_admin_static_page_update', ['id' => $page->getId()]);
        }
        
        return $this->render("AdminBundle:Static:update_page.html.twig", array(
            'page' => $page,
            'form' => $form->createView()
        ));
    }
    
    /***
     * Delete page
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deletePageAction(Request $request, Page $page) {
        $message = $this->get('translator')->trans('static.page.delete.success', [
            '%pageName%' => $page->getName()
        ], 'static');
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        
        if ($request->isXmlHttpRequest() === true) {
            return new JsonResponse($message);
        }
        
        $this->addFlash('success', $message);
        return $this->redirectToRoute('puzzle_admin_static_page_list');
    }
    
    
    /***
     * List templates
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listTemplatesAction(Request $request) {
        return $this->render("AdminBundle:Static:list_templates.html.twig", array(
            'templates' => $this->getDoctrine()->getRepository(Template::class)->findBy([], ['createdAt' => 'DESC'])
        ));
    }
    
    /***
     * Show template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTemplateAction(Request $request, Template $template){
        return $this->render("AdminBundle:Static:show_template.html.twig", array(
            'template' => $template
        ));
    }
    
    /***
     * Create template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createTemplateAction(Request $request) {
        $template = new Template();
        $form = $this->createForm(TemplateCreateType::class, $template, [
            'method' => 'POST',
            'action' => $this->generateUrl('puzzle_admin_static_template_create')
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() === true && $form->isValid() === true) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($template);
            $em->flush();
            
            $message = $this->get('translator')->trans('static.template.create.success', [
                '%templateName%' => $template->getName()
            ], 'static');
            
            if ($request->isXmlHttpRequest() === true) {
                return new JsonResponse($message);
            }
            
            $this->addFlash('success', $message);
            return $this->redirectToRoute('puzzle_admin_static_template_update', ['id' => $template->getId()]);
        }
        
        return $this->render("AdminBundle:Static:create_template.html.twig", array(
            'form' => $form->createView()
        ));
    }
    
    /***
     * Update template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateTemplateAction(Request $request, Template $template) {
        $form = $this->createForm(TemplateUpdateType::class, $template, [
            'method' => 'POST',
            'action' => $this->generateUrl('puzzle_admin_static_template_update', ['id' => $template->getId()])
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() === true && $form->isValid() === true) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $message = $this->get('translator')->trans('static.template.update.success', [
                '%templateName%' => $template->getName()
            ], 'static');
            
            if ($request->isXmlHttpRequest() === true) {
                return new JsonResponse($message);
            }
            
            $this->addFlash('success', $message);
            return $this->redirectToRoute('puzzle_admin_static_template_update', ['id' => $template->getId()]);
        }
        
        return $this->render("AdminBundle:Static:update_template.html.twig", array(
            'template' => $template,
            'form' => $form->createView()
        ));
    }
    
    /***
     * Delete template
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTemplateAction(Request $request, Template $template) {
        $message = $this->get('translator')->trans('static.template.delete.success', [
            '%templateName%' => $template->getName()
        ], 'static');
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($template);
        $em->flush();
        
        if ($request->isXmlHttpRequest() === true) {
            return new JsonResponse($message);
        }
        
        $this->addFlash('success', $message);
        return $this->redirectToRoute('puzzle_admin_static_template_list');
    }
}
