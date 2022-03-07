<?php

namespace App\Controller\Admin;

use App\App;
use App\Controller\AppController;
use App\Entity\PostEntity;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminPostController extends AppController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index()
    {
        $posts = $this->postManager->findAllOrderedByNewest($this->getUser());
        $app = $this->getMessage();

        $this->render('admin/post/index.twig', [
            'posts' => $posts,
            'app' => $app
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function show(int $id)
    {
        /** @var PostEntity|null $posts */
        $post = $this->postManager->findOneByIdIfIsGranted($id, $this->getUser());

        if (!$post) {
            return App::getInstance()->notFound();
        }

        $this->render('post/show.twig', [
            'post' => $post,
            'admin' => true
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function confirmDelete(int $id)
    {
        $post = $this->postManager->findOneById($id);
        $form = $this->initForm('post-delete');

        $form->action = App::$config['R_ADMIN_POST_DELETE'] . $post->getId();
        $form->message = App::$config['ADMIN_POST_DELETED_MODAL_MESSAGE'];

        return $this->render('admin/post/_modal.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id)
    {
        $form = $this->createForm('post-delete');

        try {
            $post = $this->postManager->findOneById($id);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isDeleted = $this->postManager->delete($post, $this->getUser());

            if ($isDeleted) {
                $form->setSuccess(App::$config['ADMIN_POST_DELETED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_POST_DELETED_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN']);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function change(int $id)
    {
        $form = $this->initForm('post-update');

        try {
            $post = $this->postManager->findOneByIdIfIsGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if ($form->hasError()) {
            $this->addMessage($form);
            header('Location: ' . App::$config['R_ADMIN']);
        }

        $this->render('admin/post/update.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(int $id)
    {
        $form = $this->createForm('post-update');

        try {
            $post = $this->postManager->findOneByIdIfIsGranted($id, $this->getUser());
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $isPublishedChange = (property_exists($form, 'published') !== $post->isPublished());
            $post
                ->setTitle($form->title)
                ->setHeader($form->header)
                ->setContent($form->content)
                ->setPublished(property_exists($form, 'published'))
            ;
            $isUpdated = $this->postManager->update($post, $isPublishedChange);

            if ($isUpdated) {
                $form->setSuccess(App::$config['ADMIN_POST_UPDATED_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_POST_UPDATE_ERROR_MESSAGE']);
            }
        }

        $this->addMessage($form);
        header('Location: ' . App::$config['R_ADMIN']);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function create()
    {
        $form = $this->initForm('post-new');

        $this->render('admin/post/new.twig', [
            'form' => $form
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function new()
    {
        $form = $this->createForm('post-new');
        try {
            // Création du commentaire
            $post = new PostEntity();
            $post->setAuthor($this->getUser());
            $post->hydrate((array) $form);
        } catch (Exception $e) {
            $form->setError($e->getMessage());
        }

        if (!$form->hasError()) {
            $post->setPublished(property_exists($form, 'published'));
            $isCreated = $this->postManager->new($post);

            if ($isCreated) {
                $form->setSuccess(App::$config['ADMIN_POST_NEW_SUCCESS_MESSAGE']);
            } else {
                $form->setError(App::$config['ADMIN_POST_NEW_ERROR_MESSAGE']);
            }

            $this->addMessage($form);
            header('Location: ' . App::$config['R_ADMIN']);
        } else {
            $this->addMessage($form);
            $app = $this->getMessage();

            $this->render('admin/post/new.twig', [
                'post' => $post,
                'form' => $form,
                'app' => $app
            ]);
        }
    }
}
