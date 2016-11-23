<?php
namespace WpImporter;

/**
 * Class ImporterBuilder
 * @package WpImporter
 */
class ImporterBuilder{

    /**
     * @var Importer
     */
    protected $importer;

    /**
     * ImporterBuilder constructor.
     */
    public function __construct()
    {
        $this->importer = new Importer();
    }

    /**
     * @param string $path
     * @return ImporterBuilder $this
     */
    public function setWploadPath($path){
        $this->importer->setWploadPath($path);
        return $this;
    }

    /**
     * @param string $path
     * @return ImporterBuilder $this
     */
    public function setJsonPath($path){
        $this->importer->setJsonPath($path);
        return $this;
    }

    /**
     * @param string $field
     * @return ImporterBuilder $this
     */
    public function setUpdateField($field){
        $this->importer->setUpdateField($field);
        return $this;
    }

    /**
     * @param $type
     * @return ImporterBuilder $this
     */
    public function setPostType($type){
        $this->importer->setPostType($type);
        return $this;
    }

    public function validate(){

    }

    /**
     * @return Importer
     */
    public function build(){
        $this->validate();
        return $this->importer;
    }
}