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