<?php
namespace WpImporter\Single;

use WpImporter\Single\Field\Field;

class Post{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var string
     */
    protected $type;

    /**
     * Post constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getFieldValueByName($name){
        return $this->getFields()[$name];
    }


    public function save(){
        if(!isset($this->id)){
            $id = wp_insert_post(
                array(
                    "post_type" => $this->getType(),
                    "post_title" => $this->getFieldValueByName("post_title"),
                    "post_name" => $this->getFieldValueByName("post_title")
                )
            );
            $this->setId($id);
        }
        foreach($this->getFields() as $field){
            $field->save();
        }
    }
}