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
class Importer
{

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
    protected $titleField;

    /**
     * @var string|bool
     */
    protected $updateField;

    /**
     * @var array
     */
    protected $checkList;

    /**
     * @var string
     */
    protected $postType;

    /**
     * @var string
     */
    protected $postStatus;

    /**
     * @var bool
     */
    protected $verbose;

    /**
     * @var array
     */
    protected $downloadFields;

    /**
     * @var array
     */
    protected $items;

    /**
     * Importer constructor.
     */
    public function __construct()
    {
        $this->setTitleField("post_title");
        $this->setWploadPath("wp-load.php");
        $this->setJsonPath("items.json");
        $this->setUpdateField(false);
        $this->setPostType("post");
        $this->setPostStatus("publish");
        $this->setVerbose(false);
        $this->setDownloadFields(array());
    }

    public function import()
    {
        require_once $this->getWploadPath();
        $items = $this->getItems();
        $countItems = count($items);
        foreach ($items as $key => $item) {
            if ($this->isVerbose()) {
                echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
                $progress = round(($key / $countItems) * 100, 0);
                echo "{$key} / {$countItems} - {$progress}%\n";
                echo "Title:\t" . $item->getFieldValueByName($this->getTitleField()) . "\n";
            }
            if ($this->getUpdateField() !== false) {
                $checkList = $this->getCheckList();
                $postId = array_search($item->getFieldValueByName($this->getUpdateField()), $checkList);
                if ($postId) {
                    $item->setId($postId);
                    if ($this->isVerbose()) {
                        echo "Status:\t Already present\n";
                    }
                }
            }
            $item->save();
            if ($this->getUpdateField() !== false) {
                $checkList[$item->getId()] = $item->getFieldValueByName($this->getUpdateField());
            }
        }

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
     * @return string|bool
     */
    public function getUpdateField()
    {
        return $this->updateField;
    }

    /**
     * @param string|bool $updateField
     */
    public function setUpdateField($updateField)
    {
        $this->updateField = $updateField;
    }

    /**
     * @param $file
     * @throws \Exception
     */
    protected function checkLoader($file)
    {
        if (!is_readable($file)) {
            throw new \Exception("File not readable");
        }
    }

    /**
     * @return array
     */
    public function getCheckList()
    {
        if (!isset($this->checkList)) {
            $checkList = [];
            global $wpdb;
            switch ($this->getUpdateField()) {
                case $this->getTitleField():
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
            foreach ($results as $result) {
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
    protected function getItems()
    {
        if (!isset($this->items)) {
            $json = json_decode(file_get_contents($this->getJsonPath()));
            $items = [];
            foreach ($json as $item) {
                $postBuilder = (new PostBuilder())
                    ->setVerbose($this->isVerbose())
                    ->setType($this->getPostType());
                foreach ($item as $key => $value) {
                    $field = (new FieldBuilder())
                        ->setKey($key)
                        ->setValue($value);
                    if(in_array($key, $this->getDownloadFields())){
                        $field->setDownload(true);
                    }
                    $field = $field->build();
                    $postBuilder->addField($field);
                }
                $post = $postBuilder->build();
                foreach ($post->getFields() as $field) {
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

    /**
     * @return string
     */
    public function getPostStatus()
    {
        return $this->postStatus;
    }

    /**
     * @param string $postStatus
     */
    public function setPostStatus($postStatus)
    {
        $this->postStatus = $postStatus;
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

    /**
     * @return array
     */
    public function getDownloadFields()
    {
        return $this->downloadFields;
    }

    /**
     * @param array $downloadFields
     */
    public function setDownloadFields($downloadFields)
    {
        $this->downloadFields = $downloadFields;
    }


}