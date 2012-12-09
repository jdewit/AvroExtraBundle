<?php
namespace Avro\ExtraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity
 * @ORM\Table(name="asset_image")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * Upload root directory
     */
    public $uploadRootDir;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isUpdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isDeleted = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\PrePersist
     */
    public function PrePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    public function setUploadRootDir($uploadRootDir)
    {
        $this->uploadRootDir = $uploadRootDir;
    }

    protected function getUploadRootDir() {
        return $this->uploadRootDir;
    }

    protected function getUploadDir()
    {
        return 'uploads/images';
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $this->setIsUpdated(false);

        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $this->setPath(uniqid().'.'.$this->file->guessExtension());
        }

       $this->updatedAt= new \DateTime('now');
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $imageDir = $this->getUploadRootDir();
        $this->file->move($imageDir, $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set isUpdated
     *
     * @param datetime $isUpdated
     */
    public function setIsUpdated($isUpdated)
    {
        $this->isUpdated = $isUpdated;
    }

    /**
     * Get isUpdated
     *
     * @return datetime $isUpdated
     */
    public function getIsUpdated()
    {
        return $this->isUpdated;
    }
    /**
    * Set createdAt
    *
    * @param datetime $createdAt
    */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }
    /**
     * Set deletedAt
     *
     * @param datetime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * Get deletedAt
     *
     * @return datetime $deletedAt
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }


}

