<?php
namespace WpImporter\Single\Field;

use WpImporter\Single\PostBuilder;
use WpImporter\Single\Post;

class Field
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var Post
     */
    protected $post;

    /**
     * Field constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    public function save()
    {
        switch ($this->getKey()) {
            case "post_title":
                wp_update_post(array(
                    'ID' => $this->getId(),
                    'post_title' => $this->getValue()
                ));
                break;
            default:
                if (!is_array($this->getValue())) {
                    $this->setValue(array($this->getValue()));
                }
                delete_post_meta($this->getId(), $this->getKey());
                foreach ($this->getValue() as $value) {
                    add_post_meta($this->getId(), $this->getKey(), $value);
                }
                break;
        }
    }

    protected function getId()
    {
        return $this->getPost()->getId();
    }

}