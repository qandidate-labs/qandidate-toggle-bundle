# Changelog

# 1.0.x

# 0.8.x

* [06b3b9e](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/06b3b9e122b2b977a37e4917512be05601ed7603) Add collection_factory config directive to README (krizon)
* [f5f14c4](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/f5f14c4ca26c44dd6fcd082f120be3bfbce9323f) process configuration on the extension (othillo)

# 0.7.x

* [83f8f81](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/83f8f81de640e1122e789a88a81c8cd39f93d75b) Documents the toggle annotation (adev)
* [fb7b1f0](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/fb7b1f07e99f72ebd9a28c263efd1d992390a8bc) Add symfony/security-core dependency (adev)
* [deec1d0](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/deec1d053fdbf3963e567f3c3db02b9304587518) Support invokable controllers in annotation toggle listener (krizon)

# 0.6.x

* [17567ea](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/17567ea75dfe43d608c536b195af8cc1e189294c) Update qandidate/toggle dependency (Roel Philipsen)
* [e9e4a33](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/e9e4a33deb6411d6da20ab932cfd586e55a5ce70) Use newer phpunit version if possible (Alexander)
* [1660b99](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/1660b99b543e0094a2745ade6d9f72d0e5b1ba50) Switch to container based infrastructure on travis (Alexander)
* [2ec79bd](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/2ec79bda7df79c3dae0c34fa4998a55dc862a504) Use phpunit installed by composer (Alexander)
* [ef58675](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/ef58675f75aa767fecd5be35a0e75ff98cc992d4) Update travis-ci build matrix (Alexander)
* [d111883](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/d111883405622e1954a41e4dd23c727a97747d9f) drop support for PHP 5.5 (othillo)
* [6fc0b5a](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/6fc0b5a6f6cb76c9d56ac2f244d7f776bfe24912) use matthiasnoback/symfony-dependency-injection-test for testing the extension (othillo)
* [4c11568](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/4c11568c8fb261e143dae784c24bbad1b0c4391a) Allow yml configuration (othillo)
* [7718e14](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/7718e1443b9b758954d8913bf5ebc94498c906ff) Rename service & inject service instead of calling constructor (othillo)
* [64683d5](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/64683d5ede48eb81d53abea16997f5ed64965cd1) Remove unused usages (othillo)
* [ee0a592](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/ee0a59271426b833fb4cb5dafb310c22398449b0) Remove unnecessary code from phpunit.xml.dist (othillo)
* [895e930](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/895e93080d36da0463fdbd351e96f443ffd87c70) fixed unsupported status (othillo)
* [5f00e7d](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/5f00e7dce3394061e3b75506542c8a2767dfebdd) test the compiled toggle collection (othillo)
* [cf375c5](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/cf375c51a12daa07de13f61352fe2950ca0c5e81) removed necessity of factory (othillo)
* [b779e8a](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/b779e8a540485a5bfe203cc53b74b9754b21653c) updated README (othillo)
* [2c06fd3](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/2c06fd3129961d69414af34b9feeb275beff4584) renamed symfony to config (othillo)

## 0.5.x

* [083a3ae](http://github.com/qandidate-labs/qandidate-toggle-bundle/commit/083a3aeb1b07c59074d8de66b3cf89282e7b991c) Add support for symfony 3 (Martin Parsiegla)

## 0.4.x

- [BC BREAK] Updated the symfony dependency to 2.7
- Added support for collection_factory

## 0.3.x

- [BC BREAK] The twig function has been renamed from `is_active` to `feature_is_active`
- Added a twig test `is active feature`

## 0.2.x

- [@ricbra] added the `@Toggle` annotation, to enable/disable a controller or a specific controller action

[@ricbra]: https://github.com/ricbra
