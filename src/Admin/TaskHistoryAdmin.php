<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Task;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class TaskHistoryAdmin extends AbstractAdmin
{
    public function getTranslationDomain()
    {
        return 'AdminEntityMessages';
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('task', null, [], EntityType::class, [
                'class' => Task::class,
                'choice_label' => 'name',
            ])
            ->add('reportDir')
            ->add('createAt')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('task.name')
            ->add('reportDir')
            ->add('createAt')
            ->add('_action', null, [
                'actions' => [
                    'report' => [
                        'template' => 'actions/list__action_viewreport.html.twig',
                    ],
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('task', EntityType::class, [
                'class' => Task::class,
                'choice_label' => 'name',
            ])
            ->add('reportDir')
            ->add('createAt')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('reportDir')
            ->add('createAt')
            ;
    }
}
