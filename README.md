# Custom Taxonomy Helper

A super simple abstraction to make creating a custom Taxonomies in WordPress a _breeze._

## Installation

You can either a) install with composer or b) copy the main the file and require it your functions file.

### Composer

In your terminal, in the directory where your composer.json is (usually the theme directory):

`composer require itsmejgrant/custom-taxonomy-helper`

Then, in your `function.php` file:

`use Itsmejgrant\TaxonomyHelper\Taxonomy`

That's it, you're ready to go!

### Manual installation

Copy the contents of the `Taxonomy.php` file and put it whereever makes sense in your theme directory.

Require it whereever you will be using it with:

`require 'path/to/your/Taxonomy.php';`

## Usage

To use the helper, assign a new instance to a variable and manipulate as required. The only required arguments are the name of the taxonomy and either a string or array of post types to assign it to.

Examples:

```php
function assigning_to_one_post_type() {
  $regions = new Taxonomy('Regions', 'posts');
}
add_action('init', 'assigning_to_one_post_type');
```

```php
function assigning_to_multiple_post_types() {
  $regions = new Taxonomy('Regions', ['posts', 'pages']);
}
add_action('init', 'assigning_to_multiple_post_types');
```
