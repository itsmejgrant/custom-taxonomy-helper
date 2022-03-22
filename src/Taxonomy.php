<?php

namespace Itsmejgrant\TaxonomyHelper;

/**
 * A tidy helper class for cleanly creating custom post types
 *
 * @param string $name
 * @param string|string[] $post_types
 * @param array $labels
 * @param array $args
 */
class Taxonomy
{
    /**
     * Load everything
     *
     * @param string $name
     * @param string|array $post_types
     * @param array $labels
     * @param array $args
     */
    public function __construct(string $name = '', mixed $post_types = [], array $labels = [], array $args = [])
    {
        // Bind variables
        $this->name = $name;
        $this->post_types = $post_types;
        $this->labels = $labels;
        $this->args = $args;

        if (empty($this->name)) {
            throw new Exception('A name for the taxonomy is required.');
        }

        // Set defaults if none are passed
        if (empty($this->labels) || !$this->__is_associative_array($this->labels)) {
            $this->__set_default_labels();
        }

        if (empty($this->args) || !$this->__is_associative_array($this->args)) {
            $this->__set_default_args();
        }

        if (gettype($this->post_types) !== 'string' && gettype($this->post_types) !== 'array') {
            throw new Exception('The post types must be a string or an array.');
        }

        $this->__register_taxonomy($this->name, $this->post_types);
    }

    /**
     * Update the labels for post type
     *
     * @throws Exception if the arguments are not valid or the array key does not exist
     *
     * @return void
     */
    public function set_labels(array $labels = []): void
    {
        // Not valid, throw an error
        if (empty($labels) || !$this->__is_associative_array($labels)) {
            throw new Exception('Please provide a valid list arguments');
        }

        foreach ($labels as $key => $label) {
            if (!array_key_exists($key, $this->labels)) {
                throw new Exception('Array key does not exist. Please check you are using a valid key');
            }
        }

        $updated_labels = array_replace($this->labels, $labels);
        $this->set_args(['labels' => $updated_labels]);
    }

    /**
     * If no labels are passed in, set some defaults based on the name
     *
     * @return void
     */
    protected function __set_default_labels(): void
    {
        $theme = get_current_theme();
        $this->labels = array(
            'name' => _x("$this->name", "taxonomy general name", $theme),
            "singular_name" => _x(rtrim($this->name, "s"), "taxonomy singular name", $theme),
            "search_items" => __("Search $this->name", $theme),
            "all_items" => __("All $this->name", $theme),
            "view_item" => __("View Genre", $theme),
            "parent_item" => __("Parent Genre", $theme),
            "parent_item_colon" => __("Parent Genre:", $theme),
            "edit_item" => __("Edit Genre", $theme),
            "update_item" => __("Update Genre", $theme),
            "add_new_item" => __("Add New Genre", $theme),
            "new_item_name" => __("New Genre Name", $theme),
            "not_found" => __("No $this->name Found", $theme),
            "back_to_items" => __("Back to $this->name", $theme),
            "menu_name" => __("$this->name", $theme),
        );
    }

    /**
     * Update the arguments for the post type
     *
     * @throws Exception if the arguments are not valid or the array key does not exist
     *
     * @return void
     */
    public function set_args(array $args = []): void
    {
        // Not valid, throw an error
        if (empty($args) || !$this->__is_associative_array($args)) {
            throw new Exception('Please provide a valid list arguments');
        }

        foreach ($args as $key => $arg) {
            if (!array_key_exists($key, $this->args)) {
                throw new Exception('Array key does not exist. Please check you are using a valid key');
            }
        }

        // Replace and re-register
        $this->args = array_replace($this->args, $args);
        $this->__register_taxonomy();
    }

    /**
     * If no args are passed in, set some defaults
     *
     * @return void
     */
    protected function __set_default_args(): void
    {
        $this->args = array(
            'labels' => $this->labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => rtrim($this->name, "s")),
            'show_in_rest' => true,
        );
    }

    /**
     * Register the custom post type
     *
     * @return void
     */
    protected function __register_taxonomy(): void
    {
        register_taxomony($this->name, $this->post_types, $this->args);
    }

    /**
     * Checks if an array is associative. Return value of 'False' indicates a sequential array.
     *
     * @param array $input_array
     * @return bool
     */
    protected function __is_associative_array(array $input_array = null): bool
    {
        // An empty array is in theory a valid associative array
        // so we return 'true' for empty.
        if ($input_array === []) {
            return true;
        }

        $array_count = count($input_array);
        for ($i = 0; $i < $array_count; $i++) {
            if (!array_key_exists($i, $input_array)) {
                return true;
            }
        }

        // Dealing with a Sequential array
        return false;
    }
}
