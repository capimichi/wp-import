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
    protected $titleField;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var bool
     */
    protected $verbose;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->setFields(array());
        $this->setType("post");
        $this->setStatus("publish");
        $this->setVerbose(false);
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
    public function getTitleField()
    {
        return $this->titleField;
    }

    /**
     * @param string $titleField
     */
    public function setTitleField($titleField)
    {
        $this->titleField = $titleField;
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
        foreach($this->getFields() as $field){
            if($field->getKey() == $name){
                return $field->getValue();
            }
        }
        return false;
    }

    /**
     * Save to db
     */
    public function save(){
        if(!isset($this->id)){
            $id = wp_insert_post(
                array(
                    "post_type" => $this->getType(),
                    "post_title" => $this->getFieldValueByName($this->getTitleField()),
                    "post_name" => $this->getFieldValueByName($this->getTitleField()),
                    "post_status" => $this->getStatus()
                )
            );
            $this->setId($id);
        } else {
            wp_update_post(array(
                "ID" => $this->getId(),
                "post_status" => $this->getStatus()
            ));
        }
        foreach($this->getFields() as $field){
            $field->save();
        }
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * @param boolean $verbose
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }
}