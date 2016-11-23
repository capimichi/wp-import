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
     * Single constructor.
     * @param \stdClass $obj
     */
    public function __construct($obj)
    {
        $fields = [];
        foreach($obj as $key => $value){
            $fields[] = new Field($key, $value);
        }
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

    public function getFieldValueByName($name){
        return $this->getFields()[$name];
    }

    public function save(){
        if(!isset($this->id)){

        }
    }
}