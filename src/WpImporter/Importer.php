<?php
namespace WpImporter;
use WpImporter\Single\Field\Field;
use WpImporter\Single\Field\FieldBuilder;
use WpImporter\Single\Post;
use WpImporter\Single\PostBuilder;

/**
 * Class Importer
 * @package WpImporter
 */
class Importer{

    /**
     * @var string
     */
    protected $wploadPath;

    /**
     * @var string
     */
    protected $jsonPath;

    /**
     * @var string
     */
    protected $updateField;

    /**
     * @var bool
     */
    protected $updateEnabled;

    /**
     * @var array
     */
    protected $checkList;

    /**
     * @var string
     */
    protected $postType;

    /**
     * @var array
     */
    protected $items;

    /**
     * Importer constructor.
     */
    public function __construct()
    {
    }

    public function import(){
        require_once $this->getWploadPath();
        $items = $this->getItems();
        foreach ($items as $item){
            $checkList = $this->getCheckList();
            $postId = array_search($item->getFieldValueByName($this->getUpdateField()), $checkList);
            if($postId){
                $item->setId($postId);
            }
            $item->save();
            $checkList[$item->getId()] = $item->getFieldValueByName($this->getUpdateField());
        }
        /*
         * per ogni item
         * controllo se è presente nel db
         * se si all'item gli metto l'id
         * se no all'item non gli metto l'id
         * do all'item il comando di salvare (lui saprà se deve creare o no in base all'id)
         */
    }

    /**
     * @return string
     */
    public function getWploadPath()
    {
        return $this->wploadPath;
    }

    /**
     * @param string $wploadPath
     */
    public function setWploadPath($wploadPath)
    {
        $this->wploadPath = $wploadPath;
    }

    /**
     * @return string
     */
    public function getJsonPath()
    {
        return $this->jsonPath;
    }

    /**
     * @param string $jsonPath
     */
    public function setJsonPath($jsonPath)
    {
        $this->jsonPath = $jsonPath;
    }

    /**
     * @return string
     */
    public function getUpdateField()
    {
        return $this->updateField;
    }

    /**
     * @param string $updateField
     */
    public function setUpdateField($updateField)
    {
        $this->updateField = $updateField;
    }

    /**
     * @param $file
     * @throws \Exception
     */
    protected function checkLoader($file){
        if(!is_readable($file)){
            throw new \Exception("File not readable");
        }
    }

    /**
     * @return boolean
     */
    public function isUpdateEnabled()
    {
        return $this->updateEnabled;
    }

    /**
     * @param boolean $updateEnabled
     */
    public function setUpdateEnabled($updateEnabled)
    {
        $this->updateEnabled = $updateEnabled;
    }

    /**
     * @return array
     */
    public function getCheckList()
    {
        if(!isset($this->checkList)){
            $checkList = [];
            global $wpdb;
            switch ($this->getUpdateField()){
                case "post_title":
                    $results = $wpdb->get_results(
                        $wpdb->prepare("
                        SELECT ID, post_title 
                        FROM {$wpdb->posts} 
                        WHERE post_type = %s", $this->getPostType()),
                        ARRAY_A
                    );
                    break;

                default:
                    $query = $wpdb->prepare("
                        SELECT ID, meta_value 
                        FROM {$wpdb->posts}, {$wpdb->postmeta} 
                        WHERE meta_key = %s
                        AND post_type = %s
                        AND ID = post_id", $this->getUpdateField(), $this->getPostType());
                    $results = $wpdb->get_results(
                        $query,
                        ARRAY_A
                    );
                    break;
            }
            foreach($results as $result){
                $checkList[$result["ID"]] = $result[$this->getUpdateField()];
            }
            $this->setCheckList($checkList);
        }
        return $this->checkList;
    }

    /**
     * @param array $checkList
     */
    public function setCheckList($checkList)
    {
        $this->checkList = $checkList;
    }

    /**
     * @return string
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * @param string $postType
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;
    }

    /**
     * @return array
     */
    protected function getItems(){
        if(!isset($this->items)){
            $json = json_decode(file_get_contents($this->getJsonPath()));
            $items = [];
            foreach($json as $item){
                $postBuilder = (new PostBuilder())
                    ->setType($this->getPostType());
                foreach($item as $key => $value){
                    $field = (new FieldBuilder())
                        ->setKey($key)
                        ->setValue($value)
                        ->build();
                    $postBuilder->addField($field);
                }
                $post = $postBuilder->build();
                foreach($post->getFields() as $field){
                    $field->setPost($post);
                }
                $items[] = $post;
            }
            $this->setItems($items);
        }
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

}