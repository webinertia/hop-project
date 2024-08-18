<?php

declare(strict_types=1);

namespace UserManager\Form;

use Laminas\Filter;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Laminas\Form;

use function strtolower;

final class Login extends Form\Form implements InputFilterProviderInterface
{
    protected $attributes = ['class' => 'login-form', 'method' => 'POST'];

    /** @inheritDoc */
    public function __construct($name = 'login-form', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this->add([
            'name' => 'username',
            'type' => Form\Element\Text::class,
            'attributes' => [
                //'placeholder' => 'User Name',
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => Form\Element\Password::class,
            'attributes' => [
                //'placeholder' => 'Email',
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'username' => [
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
        ];
    }
}
