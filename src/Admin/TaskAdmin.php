<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TaskAdmin extends AbstractAdmin
{
    public static $status = [
        '采集中' => 'run',
        '禁用' => 'stop',
        '生成报告中' => 'process',
    ];

    public function getTranslationDomain()
    {
        return 'AdminEntityMessages';
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('host')
            ->add('dir')
            ->add('status', null, [], ChoiceType::class, [
                'choices' => self::$status,
            ])
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('host')
            ->add('dir')
            ->add('status', 'choice', [
                'choices' => array_flip(self::$status),
            ])
            ->add('_action', null, [
                'actions' => [
                    'report' => [
                        'template' => 'actions/list__action_report.html.twig',
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
            ->add('name')
            ->add('host')
            ->add('dir')
            ->add('status', ChoiceType::class, [
                'choices' => self::$status,
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('host')
            ->add('dir')
            ->add('status', 'choice', [
                'choices' => array_flip(self::$status),
            ])
            ;
    }
}
