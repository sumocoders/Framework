# Symfony Forms Fieldset Type

based on https://github.com/adamquaile/AdamQuaileFieldsetBundle

## Custom options

* legend => The text in the legend, if left empty or not defined no legend will be added
* fieldset_class => The class on the fieldset element
* legend_class => The class on the legend element
* open_row => Will add ```<div class="row>``` so you can place multiple fieldsets next to each other useing the bootstrap grid system
* close_row => Will add ```<\div>``` to close the opened row

## Use with normal form builder methods:

```php

    <?php

    $builder->add(
        'my_group_example_one',
        'fieldset',
        [
            'legend' => 'Your fieldset legend',
            'fields' => function(FormBuilderInterface $builder) {
                $builder->add('
                    first_name',
                    'text',
                    [
                        'label' => 'First Name'
                    ]
                );
                $builder->add(
                    'last_name',
                    'text',
                    [
                        'required'  => false,
                        'label'     => 'Surname'
                    ]
                );
            }
        ]
    );

```

## A fieldset with your fields defined in an array

```php

    <?php

    $builder->add(
        'my_group_example_two',
        'fieldset',
        [
            'legend' => 'Your fieldset legend',
            'fields' => [
                [
                    'name'  => 'first_name',
                    'type'  => 'text',
                    'attr'  => [
                        'label' => 'First Name'
                    ]
                ],
                [
                    'name'  => 'last_name',
                    'type'  => 'text',
                    'attr'  => [
                        'required'  => false,
                        'label'     => 'Surname'
                    ]
                ]
            ]
        ]
    );

```
