<?php

declare(strict_types=1);

namespace ContactManager\Filter;

use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

class Contact extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'    => 'toList',
                'allow_empty' => true,
                'filters' => [
                    ['name' => Filter\ToInt::class],
                    ['name' => Filter\ToNull::class],

                ],
            ]
        )->add(
            [
                'name'    => 'fromList',
                'allow_empty' => true,
                'filters' => [
                    ['name' => Filter\ToInt::class],
                    ['name' => Filter\ToNull::class],
                ],
            ]
        )->add(
            [
                'name'    => 'id',
                'allow_empty' => true,
                'filters' => [
                    ['name' => Filter\ToInt::class],
                    ['name' => Filter\ToNull::class],
                ],
            ]
        )->add(
            [
                'name' => 'first_name',
                'allow_empty' => true,
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
            ]
        )->add(
            [
                'name' => 'last_name',
                'allow_empty' => true,
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
            ]
        )->add(
            [
                'name' => 'email',
                'allow_empty' => true,
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
            ]
        );
    }
}
