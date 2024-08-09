<?php

declare(strict_types=1);

namespace ContactManager\Form;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Filter;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Laminas\Form;
use Limatus\Form\HttpMethodTrait;
use Limatus\FormInterface;

use function strtolower;

final class Contact extends Form\Form implements FormInterface, InputFilterProviderInterface
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
        $options = $this->getOptions();

        $this->setAttribute('id', 'contact-form');

        $this->add([
            'name' => 'list_id',
            'type' => Form\Element\Hidden::class,
        ]);
        if ($this->httpMethod === Http::METHOD_PUT) { // only need this for updating a contact
            $this->add([
                'name' => 'id',
                'type' => Form\Element\Hidden::class,
            ]);
        }
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

    public function getInputFilterSpecification(): array
    {
        $options = $this->getOptions();
        $filter  = [
            'first_name' => [
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            'last_name' => [
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            'email'    => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 320, // true, we may never see an email this length, but they are still valid
                        ],
                    ],
                    // @see EmailAddress for $options
                    ['name' => Validator\EmailAddress::class],
                ],
            ],
        ];

        if ($this->httpMethod === Http::METHOD_PUT) {
            $filter[] = [
                'id' => [
                    'filters' => [
                        ['name' =>  Filter\ToInt::class],
                    ],
                ],
            ];
        }

        return $filter;
    }
}

