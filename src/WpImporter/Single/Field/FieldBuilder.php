<?php
namespace WpImporter\Single\Field;

use WpImporter\Single\Post;
use WpImporter\Single\PostBuilder;

class FieldBuilder{

    /**
     * @var Field
     */
    protected $field;


    /**
     * FieldBuilder constructor.
     */
    public function __construct()
    {
        $this->field = new Field();
    }


    /**
     * @param string $key
     * @return FieldBuilder $this
     */
    public function setKey($key){
        $this->field->setKey($key);
        return $this;
    }


    /**
     * @param string $value
     * @return FieldBuilder $this
     */
    public function setValue($value){
        $this->field->setValue($value);
        return $this;
    }

    /**
     * @param Post $post
     * @return FieldBuilder $this
     */
    public function setPost($post){
        $this->field->setPost($post);
        return $this;
    }

    /**
     * @param bool $title
     * @return FieldBuilder $this
     */
    public function setTitle($title){
        $this->field->setTitle($title);
        return $this;
    }

    /**
     * @param bool $featured
     * @return FieldBuilder $this
     */
    public function setFeatured($featured){
        $this->field->setFeatured($featured);
        return $this;
    }

    /**
     * @param bool $download
     * @return FieldBuilder $this
     */
    public function setDownload($download){
        $this->field->setDownload($download);
        return $this;
    }

    /**
     * @param string|bool $taxonomy
     * @return FieldBuilder $this
     */
    public function setTaxonomy($taxonomy){
        $this->field->setTaxonomy($taxonomy);
        return $this;
    }

    /**
     * @param bool $ignore
     * @return FieldBuilder $this
     */
    public function setIgnore($ignore){
        $this->field->setIgnore($ignore);
        return $this;
    }

    public function validate(){

    }

    /**
     * @return Field
     */
    public function build(){
        $this->validate();
        return $this->field;
    }
}