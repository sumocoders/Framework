# Using pagination

Pagination is a nice way to handle large amounts of data over multiple pages.

## Controller

In the controller the following code should be used. If necessary a custom adapter can be used.

```
<?php
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Pagerfanta\Pagerfanta,
    Pagerfanta\Adapter\DoctrineORMAdapter,
    Pagerfanta\Exception\NotValidCurrentPageException;
class ExampleController extends Controller
{
	/**
	 * @Route("/example/{page}",
         *         name="example",
         *         requirements={"page" = "\d+"},
         *         defaults={"page" = "1"}
         * )
	 * @Template()
	 * 
	 * @param int $page
	 */
	public function exampleAction($page)
	{
		$repo = $this->getDoctrine()->getRepository('AcmeExampleBundle:Example');
		
		// returns \Doctrine\ORM\Query object
		$query = $repo->getExampleQuery();
		
		$pagerfanta = new Pagerfanta(new DoctrineORMAdapter($query));
		$pagerfanta->setMaxPerPage(45);
		
		try {
			$pagerfanta->setCurrentPage($page);
		} catch(NotValidCurrentPageException $e) {
			throw new NotFoundHttpException();
		}
		
		return ['my_pager' => $pagerfanta];
	}
}
```

## View

In the view the following code can be used

```
{% if pager.currentPageResults is not empty %}
    {% for item in my_pager.currentPageResults %}
        <ul>
            <li>{{ item.id }}</li>
        </ul>
    {% endfor %}
{% endif %}

{% if pager.haveToPaginate %}
    <div class="pagerfanta">
        {{ pagerfanta(my_pager, 'twitter_bootstrap3_translated') }}
    </div>
{% endif %}
```
