<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

final class TaskAdminController extends CRUDController
{

    /**
     * @Route("/generatePhpConfig", name="generatePhpConfig")
     * @return RedirectResponse
     */
    public function generatePhpConfig()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        var_dump($tasks);
        return new RedirectResponse();
    }
}
