<?php

declare(strict_types=1);

namespace ContactManager\Form;

use ContactManager\Filter\Contact as ContactFilter;
use Htmx\Form\HttpMethodTrait;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form;

use function strtolower;

final class Contact extends Form\Form
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

        $this->setAttributes([
            'id' => 'contact-form',
            'class' => 'pico',
            'data-theme' => 'light',
        ]);

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
                'placeholder' => 'First Name',
            ],
        ]);
        $this->add([
            'name' => 'last_name',
            'type' => Form\Element\Text::class,
            'attributes' => [
                'placeholder' => 'Last Name',
            ],
        ]);
        $this->add([
            'name' => 'email',
            'type' => Form\Element\Text::class,
            'attributes' => [
                'placeholder' => 'Email',
            ],
        ]);
    }
}

