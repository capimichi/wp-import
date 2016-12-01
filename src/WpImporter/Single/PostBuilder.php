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
     * @param string $field
     * @return ImporterBuilder $this
     */
    public function setTitleField($field){
        $this->post->setTitleField($field);
        return $this;
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

    /**
     * @param string $status
     * @return PostBuilder $this
     */
    public function setStatus($status){
        $this->post->setStatus($status);
        return $this;
    }

    /**
     * @param bool $verbose
     * @return PostBuilder $this
     */
    public function setVerbose($verbose){
        $this->post->setVerbose($verbose);
        return $this;
    }

    /**
     * @param bool|string $woocommerceType
     */
    public function setWoocommerceType($woocommerceType)
    {
        $this->post->setWoocommerceType($woocommerceType);
        return $this;
    }

    /**
     * @param string $field
     * @return PostBuilder $this
     */
    public function addDownloadField($field){
        $fields = $this->post->getDownloadFields();
        $fields[] = $field;
        $this->post->setDownloadFields($fields);
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