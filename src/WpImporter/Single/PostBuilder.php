<?php
namespace WpImporter\Single;


use WpImporter\ImporterBuilder;
use WpImporter\Single\Field\Field;

class PostBuilder{

    /**
     * @var Post
     */
    protected $post;

    /**
     * ImporterBuilder constructor.
     */
    public function __construct()
    {
        $this->post = new Post();
    }

    /**
     * @param string $type
     * @return PostBuilder $this
     */
    public function setType($type){
        $this->post->setType($type);
        return $this;
    }

    /**
     * @param Field $field
     * @return PostBuilder $this
     */
    public function addField($field){
        $fields = $this->post->getFields();
        $fields[] = $field;
        $this->post->setFields($fields);
        return $this;
    }

    public function validate(){

    }

    /**
     * @return Post
     */
    public function build(){
        $this->validate();
        return $this->post;
    }
}