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
    protected $title;

    /**
     * @var bool
     */
    protected $download;

    /**
     * @var string|bool
     */
    protected $taxonomy;

    /**
     * @var bool
     */
    protected $ignore;

    /**
     * @var array
     */
    protected $downloadedImagesMd5;

    /**
     * Field constructor.
     */
    public function __construct()
    {
        $this->setKey("");
        $this->setValue("");
        $this->setPost(null);
        $this->setDownload(false);
        $this->setTaxonomy(false);
        $this->setIgnore(false);
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
        if ($this->isTitle()) {
            wp_update_post(array(
                'ID' => $this->getId(),
                'post_title' => $this->getValue()
            ));
        } else {
            $values = is_array($this->getValue()) ? $this->getValue() : array($this->getValue());
            if(!$this->isIgnore()) {
                if ($this->isDownload()) {
                    $md5List = $this->getDownloadedImagesMd5();
                    foreach($values as $value){
                        $imageContent = file_get_contents($value);
                        $imageMd5 = md5($imageContent);
                        if(!in_array($imageMd5, $md5List)){
                            $md5List[] = $imageMd5;
                            $imageName = sanitize_file_name($value);
                            $file = wp_upload_bits($imageName . ".png", null, $imageContent);
                            $filename = $file['file'];
                            $filetype = wp_check_filetype(basename($filename), null);
                            $wp_upload_dir = wp_upload_dir();
                            $attachment = array(
                                'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                                'post_mime_type' => $filetype['type'],
                                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            $attachId = wp_insert_attachment($attachment, $filename, $this->getId());
                            require_once(ABSPATH . 'wp-admin/includes/image.php');
                            $attach_data = wp_generate_attachment_metadata($attachId, $filename);
                            wp_update_attachment_metadata($attachId, $attach_data);
                            add_post_meta($this->getId(), $this->getKey(), $file['url']);
                        }
                    }
                } else {
                    if($this->getTaxonomy() !== false){
                        wp_set_object_terms( $this->getId(), $values, $this->getTaxonomy());
                        if ($this->isVerbose()) {
                            echo "{$this->getKey()}:\t {$this->getValue()}\n";
                        }
                    } else {
                        delete_post_meta($this->getId(), $this->getKey());
                        foreach ($values as $value) {
                            // TODO: Is downloadable url
                            add_post_meta($this->getId(), $this->getKey(), $value);
                            if ($this->isVerbose()) {
                                echo "{$this->getKey()}:\t {$this->getValue()}\n";
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * @return boolean
     */
    public function isTitle()
    {
        return $this->title;
    }

    /**
     * @param boolean $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return bool|string
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @param bool|string $taxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @return boolean
     */
    public function isIgnore()
    {
        return $this->ignore;
    }

    /**
     * @param boolean $ignore
     */
    public function setIgnore($ignore)
    {
        $this->ignore = $ignore;
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
     * @return array
     */
    public function getDownloadedImagesMd5()
    {
        if(!isset($this->downloadedImagesMd5)){
            $downloadedImagesMd5 = [];
            foreach(get_post_meta($this->getId(), $this->getKey()) as $url){
                $downloadedImagesMd5[] = md5(file_get_contents($url));
            }
            $this->setDownloadedImagesMd5($downloadedImagesMd5);
        }
        return $this->downloadedImagesMd5;
    }

    /**
     * @param array $downloadedImagesMd5
     */
    public function setDownloadedImagesMd5($downloadedImagesMd5)
    {
        $this->downloadedImagesMd5 = $downloadedImagesMd5;
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
    protected function isVerbose()
    {
        return $this->getPost()->isVerbose();
    }
}