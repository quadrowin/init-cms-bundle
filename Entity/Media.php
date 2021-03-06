<?php
/**
 * This file is part of the Networking package.
 *
 * (c) net working AG <info@networking.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Networking\InitCmsBundle\Entity;


use Doctrine\ORM\Mapping as ORM,
    Sonata\MediaBundle\Entity\BaseMedia as BaseMedia,
    Networking\InitCmsBundle\Entity\Tag,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Yorkie Chadwick <y.chadwick@networking.ch>
 *
 * Networking\InitCmsBundle\Entity\Media
 *
 * @ORM\Table(name="media__media")
 * @ORM\Entity()
 */
class Media extends BaseMedia
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @var ArrayCollection $tags
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="media_tags",
     *      joinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $tags;

    /**
     * @var ArrayCollection $galleryHasMedias
     *
     * @ORM\OneToMany(targetEntity="Networking\InitCmsBundle\Entity\GalleryHasMedia", mappedBy="media", orphanRemoval=true)
     */
    protected $galleryHasMedias;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add tags
     *
     * @param \Networking\InitCmsBundle\Entity\Tag  $tag
     * @return Page
     */
    public function addTags(Tag $tag)
    {
        $this->tags->add($tag);

        return $this;
    }

    /**
     * @param  \Doctrine\Common\Collections\ArrayCollection $tags
     * @return Page
     */
    public function setTags(ArrayCollection $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }
}