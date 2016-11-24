<?php
namespace WpImporter\Single\Field;

use WpImporter\Single\PostBuilder;
use WpImporter\Single\Post;

class Field
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var bool
     */
    protected $download;

    /**
     * Field constructor.
     */
    public function __construct()
    {
        $this->setKey("");
        $this->setValue("");
        $this->setPost(null);
        $this->setDownload(false);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    public function save()
    {
        if($this->isTitleField()){
            wp_update_post(array(
                'ID' => $this->getId(),
                'post_title' => $this->getValue()
            ));
        } else {
            $values = is_array($this->getValue()) ? $this->getValue() : array($this->getValue());
            if($this->isDownload()){
//                foreach($values as $value){
//                    $content = file_get_contents($value);
//                    $contentMd5 = md5($content);
//                    $contentFound = false;
//                    foreach(get_post_meta($this->getId(), $this->getKey()) as $url){
//                        $contentPresent = file_get_contents($url);
//                        $contentPresentMd5 = md5($contentPresent);
//                        if($contentMd5 == $contentPresentMd5){
//                            $contentFound = true;
//                        }
//                    }
//                    if(!$contentFound){
//
//                    }
//                }
            } else {
                delete_post_meta($this->getId(), $this->getKey());
                foreach ($values as $value) {
                    // TODO: Is downloadable url
                    add_post_meta($this->getId(), $this->getKey(), $value);
                    if($this->isVerbose()){
                        echo "{$this->getKey()}:\t {$this->getValue()}\n";
                    }
                }
            }
        }
    }

    /**
     * @return boolean
     */
    public function isDownload()
    {
        return $this->download;
    }

    /**
     * @param boolean $download
     */
    public function setDownload($download)
    {
        $this->download = $download;
    }

    /**
     * @return int
     */
    protected function getId()
    {
        return $this->getPost()->getId();
    }

    /**
     * @return string
     */
    protected function getStatus()
    {
        return $this->getPost()->getStatus();
    }

    /**
     * @return bool
     */
    protected function isTitleField(){
        return $this->getKey() == $this->getPost()->getTitleField();
    }

    /**
     * @return bool
     */
    protected function isVerbose(){
        return $this->getPost()->isVerbose();
    }
}