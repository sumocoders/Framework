# Using date pickers

Although Symfony provides a nice integration of selecting dates, we have 
extended the functionality a bit.

When passing the `datepicker`-option as true, a nice date picker will be shown 
in the form.

```php
<?php
// ...

$defaultData = array(
    'date_example' => new \DateTime(),
);

$form = $this->createFormBuilder($defaultData)
    ->add(
        'date_example',
        'date',
        array(
            'widget' => 'single_text',
            'datepicker' => true,
        )
    )
// ...
```

As you will see this will only work when the `widget`-option has the value 
`single_text`.

## Different types

In most case you will have some constraints to the date, and this should be 
reflected in the UI, therefore we have 4 different types:

* normal
* start
* until
* range

The type can be passed through the `date_type`-option. For some types extra
options have to be set.

### Normal

This is just the normal behaviour, and through the UI any date can be selected.
The type can be passed, but it is not required.

<?php
// ...
$form = $this->createFormBuilder()
    ->add(
        'date_example_normal',
        'date',
        array(
            'widget' => 'single_text',
            'datepicker' => true,
        )
    )
// ...
```

### Start

With this type only dates that are later then the given minimum date can be 
selected.

<?php
// ...
$form = $this->createFormBuilder()
    ->add(
        'date_example_start',
        'date',
        array(
            'widget' => 'single_text',
            'datepicker' => true,
            'date_type' => 'start',
            'minimum_date' => new \DateTime('last monday'),
        )
    )
// ...
```

### Until

With this type only dates that are before then the given maximum date can be 
selected.

<?php
// ...
$form = $this->createFormBuilder()
    ->add(
        'date_example_start',
        'date',
        array(
            'widget' => 'single_text',
            'datepicker' => true,
            'date_type' => 'until',
            'maximum_date' => new \DateTime('next friday'),
        )
    )
// ...
```

### Range

With this type only dates between the given minimum and maximum date can be  
selected.

<?php
// ...
$form = $this->createFormBuilder()
    ->add(
        'date_example_start',
        'date',
        array(
            'widget' => 'single_text',
            'datepicker' => true,
            'date_type' => 'range',
            'minimum_date' => new \DateTime('last monday'),
            'maximum_date' => new \DateTime('next friday'),
        )
    )
// ...
```

## More examples

A more detailed example is available in the 
[FrameworkExampleBundle](https://github.com/sumocoders/FrameworkExampleBundle).
