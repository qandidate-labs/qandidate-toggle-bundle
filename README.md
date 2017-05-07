# Qandidate Toggle Symfony Bundle

This Bundle provides the integration with [our toggle library]. It provides the
services and configuration you need to implement feature toggles in your
application.

[![Build Status](https://travis-ci.org/qandidate-labs/qandidate-toggle-bundle.svg?branch=master)](https://travis-ci.org/qandidate-labs/qandidate-toggle-bundle)

[our toggle library]: https://github.com/qandidate-labs/qandidate-toggle

## About

Read the our blog post series about this repository at:
- http://labs.qandidate.com/blog/2014/08/18/a-new-feature-toggling-library-for-php/
- http://labs.qandidate.com/blog/2014/08/19/open-sourcing-our-feature-toggle-api-and-ui/

## Installation

Add the bundle to your composer.json

```bash
$ composer require qandidate/toggle-bundle ~0.1
```

Add the bundle to your Kernel

```php
$bundles = array(
    // ..
    new Qandidate\Bundle\ToggleBundle\QandidateToggleBundle(),
);
```
## Configuration

```yaml
qandidate_toggle:
    persistence: in_memory|redis|factory|config
    context_factory: null|your.context_factory.service.id
    redis_namespace: toggle_%kernel.environment% # default, only required when persistence = redis
    redis_client: null|your.redis_client.service.id # only required when persistence = redis
```

## Sample Configuration for Symfony

```yaml
qandidate_toggle:
    persistence: config
    toggles:
      always-active-feature:
        name: always-active-feature
        status: always-active
      inactive-feature:
        name: inactive-feature
        status: inactive
        conditions: 
      conditionally-active:
        name: conditionally-active
        status: conditionally-active
        conditions:
         - name: operator-condition
           key: user_id
           operator:
               name: greater-than
               value: 42
```

## Example usage

Usage can vary on your application. This example uses the supplied
`UserContextFactory`, but you probably need to create your own factory.

```xml
<!-- services.xml -->

<service id="acme.controller" class="Acme\Controller">
    <argument type="service" id="qandidate.toggle.manager" />
    <argument type="service" id="qandidate.toggle.user_context_factory" />
</service>
```

```php
// Acme\Controller

public function __construct(
    /* ArticleRepository, Templating, ..*/ 
    ToggleManager $manager, 
    ContextFactory $contextFactory
) {
    // ..
    $this->manager = $manager;
    $this->context = $contextFactory->createContext();
}

// ..

public function articleAction(Request $request)
{
    $this->article = $this->repository->findBySlug($request->request->get('slug'));

    return $this->templating->render('article.html.twig', array(
        'article'        => $article,
        'enableComments' => $this->manager->active('comments', $this->context),
    ));
}
```

## Twig usage

If you use Twig you can also use the function:

```jinja
{% if feature_is_active('comments') %}
    {# Awesome comments #}
{% endif %}
```
Or the Twig test:

```jinja
{% if 'comments' is active feature %}
    {# Awesome comments #}
{% endif %}
```

Both are registered in the [ToggleTwigExtension](Twig/ToggleTwigExtension.php).

## Testing

To run PHPUnit tests:

```bash
$ ./vendor/bin/phpunit
```

## License

MIT, see LICENSE.
