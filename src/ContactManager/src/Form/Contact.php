<?php

declare(strict_types=1);

namespace ContactManager\Form;

use ContactManager\Filter\Contact as ContactFilter;
use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Filter;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Validator;
use Laminas\Form;
use Limatus\Form\HttpMethodTrait;
use Limatus\FormInterface;

use function strtolower;

final class Contact extends Form\Form implements FormInterface
{
    use HttpMethodTrait;

    protected $attributes = [];
    /**
     * @param string $name
     * @param array $options
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct($name = 'contact-form', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        // attach the custom input filter
        $this->setInputFilterByName(ContactFilter::class);
        $this->setAttribute('id', 'contact-form');

        $this->add([
            'name' => 'list_id',
            'type' => Form\Element\Hidden::class,
        ]);
        $this->add([
            'name' => 'id',
            'type' => Form\Element\Hidden::class,
        ]);
        $this->add([
            'name' => 'first_name',
            'type' => Form\Element\Text::class,
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'First Name',
            ],
            'options' => [
                // 'label' => 'First Name',
                // 'label_attributes'     => [
                //     'class' => 'col-sm-2 col-form-label'
                // ],
                'bootstrap_attributes' => [
                    'class' => 'input-group mb-3',
                ],
                'horizontal_attributes' => [
                    //'class' => 'col-lg-6',
                ],
                // 'help'            => 'Your email address.',
                // 'help_attributes' => [
                //     //'class' => 'form-text text-muted col-sm-10 offset-sm-2',
                // ],
            ],
        ]);
        $this->add([
            'name' => 'last_name',
            'type' => Form\Element\Text::class,
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Last Name',
            ],
            'options' => [
                // 'label' => 'First Name',
                // 'label_attributes'     => [
                //     'class' => 'col-sm-2 col-form-label'
                // ],
                'bootstrap_attributes' => [
                    'class' => 'input-group mb-3',
                ],
                'horizontal_attributes' => [
                    //'class' => 'col-lg-6',
                ],
                // 'help'            => 'Your email address.',
                // 'help_attributes' => [
                //     //'class' => 'form-text text-muted col-sm-10 offset-sm-2',
                // ],
            ],
        ]);
        $this->add([
            'name' => 'email',
            'type' => Form\Element\Text::class,
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Email',
            ],
            'options' => [
                // 'label' => 'First Name',
                // 'label_attributes'     => [
                //     'class' => 'col-sm-2 col-form-label'
                // ],
                'bootstrap_attributes' => [
                    'class' => 'input-group mb-3',
                ],
                'horizontal_attributes' => [
                    //'class' => 'col-lg-6',
                ],
                // 'help'            => 'Your email address.',
                // 'help_attributes' => [
                //     //'class' => 'form-text text-muted col-sm-10 offset-sm-2',
                // ],
            ],
        ]);
    }
}

