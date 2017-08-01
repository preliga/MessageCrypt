<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-07-27
 * Time: 20:05
 */
namespace AppBundle\Table;

use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use JGM\TableBundle\Table\DataSource\EntityDataSource;
use JGM\TableBundle\Table\Filter\FilterBuilder;
use JGM\TableBundle\Table\Filter\Type\FilterTypeInterface;
use JGM\TableBundle\Table\Pagination\Type\PaginationTypeInterface;
use JGM\TableBundle\Table\Row\Row;
use JGM\TableBundle\Table\TableBuilder;
use JGM\TableBundle\Table\Type\AbstractTableType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FriendsSearchTableType extends AbstractTableType implements FilterTypeInterface, PaginationTypeInterface
{
    // Table
    public function buildTable(TableBuilder $builder)
    {
        $builder
            ->add('image', 'avatar', ['label' => 'Avatar', 'attr' => ['class' => 'goProfile']])
            ->add('text', 'name', ['label' => 'Name', 'attr' => ['class' => 'goProfile']])
            ->add('text', 'lastName', ['label' => 'Last name', 'attr' => ['class' => 'goProfile']])
            ->add('date', 'birthDate', ['label' => 'Birth date', 'format' => 'Y-m-d','attr' => ['class' => 'goProfile']])
            ->add('text', 'username', ['label' => 'Username', 'attr' => ['class' => 'goProfile']])
            ->add('text', 'email', ['label' => 'Email', 'attr' => ['class' => 'goProfile']])
            ->add('button', 'id', ['label' => '', 'label_btn' => '<i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send invitation', 'attr_btn' => ['class' => 'btn btn-info']])
            ;
    }

    public function getRowAttributes(Row $row)
    {
        return ['id' => $row->getEntity()->getId(), 'class' => 'searchTableRow'];
    }

    public function getDataSource(ContainerInterface $container)
    {
        return new EntityDataSource('AppBundle:User');
    }

    public function getName()
    {
        return 'user_table';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'head_attr' => ['class' => 'table-head', 'style' => 'text-align: center;', 'align' => 'center'],
            'attr' => ['class' => 'table table-striped table-hover', 'style' => 'cursor: pointer; text-align: center;'],
            'empty_value' => 'There is no user...'
        ]);
    }
    ///////

    // Filter
    public function buildFilter(FilterBuilder $builder)
    {
        $builder
            ->add('text', 'name', ['label' => 'Name', 'attr' => ['placeholder' => 'Name', 'class' => 'form-control', 'style' => 'width: 150px'] ])
            ->add('text', 'lastName', ['label' => 'Last name', 'attr' => ['placeholder' => 'Last name','class' => 'form-control', 'style' => 'width: 150px'] ])
            ->add('date', 'birthDate', ['label' => 'Birth date', 'attr' => ['placeholder' => 'Birth date','class' => 'form-control js-datepicker', 'style' => 'width: 150px'] ])
            ->add('text', 'username', ['label' => 'Username', 'attr' => ['placeholder' => 'Username','class' => 'form-control', 'style' => 'width: 150px'] ])
            ->add('text', 'email', ['label' => 'Email', 'attr' => ['placeholder' => 'Email','class' => 'form-control', 'style' => 'width: 150px'] ])
        ;
    }

    public function configureFilterButtonOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_attr' => ['class' => 'form-inline'],
            'submit_label' => 'Search',
            'reset_label' => 'Clear filter',
            'submit_attr' => ['class' => 'btn btn-default'],
            'reset_attr' => ['class' => 'btn btn-danger']
        ]);
    }
    ///////

    // Pagination
    public function configurePaginationOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'rows_per_page' => 20,
            'max_pages' => 10,
        ]);
    }
    ///////
}