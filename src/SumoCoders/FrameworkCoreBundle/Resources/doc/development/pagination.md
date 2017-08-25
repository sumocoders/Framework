# Using pagination

Pagination is a nice way to handle large amounts of data over multiple pages.

## Controller

In the controller the following code should be used. If necessary a custom adapter can be used.

```
$adapter = new DoctrineORMAdapter($queryBuilder);
$pagerfanta = new Pagerfanta($adapter);

return $this->render('@YourApp/Main/example.html.twig', [
    'my_pager' => $pagerfanta,
]);
```

## View

In the view the following code can be used

```
{% for item in my_pager.currentPageResults %}
    <ul>
        <li>{{ item.id }}</li>
    </ul>
{% endfor %}

<div class="pagerfanta">
    {{ pagerfanta(my_pager, 'twitter_bootstrap3') }}
</div>
``
