<?php

namespace Carto\DonneesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NSynset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\NSynsetRepository")
 */
class NSynset
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="wnid", type="string", length=10)
	 */
	private $wnid;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="definition", type="text")
	 */
	private $definition;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\Mot",mappedBy="nsynsets")
	*/
	private $mots;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\NSynset")
	* @ORM\JoinTable(name="nantonyms")
	*/
	private $antonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\NSynset",inversedBy="hyponyms")
	* @ORM\JoinTable(name="nhypernyms")
	*/
	private $hypernyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\NSynset",mappedBy="hypernyms")
	*/
	private $hyponyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\NSynset",inversedBy="holonyms")
	* @ORM\JoinTable(name="nmeronyms")
	*/
	private $meronyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\NSynset",mappedBy="meronyms")
	*/
	private $holonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\ASynset",inversedBy="isAttributeOf")
	* @ORM\JoinTable(name="naattributes")
	*/
	private $hasAttribute;


	public function __construct($wnid,$def)
	{
		$this -> setDefinition($def);
		$this -> setWnid($wnid);
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set wnid
	 *
	 * @param string $wnid
	 * @return NSynset
	 */
	public function setWnid($wnid)
	{
		$this->wnid = $wnid;

		return $this;
	}

	/**
	 * Get wnid
	 *
	 * @return string 
	 */
	public function getWnid()
	{
		return $this->wnid;
	}

	/**
	 * Set definition
	 *
	 * @param string $definition
	 * @return NSynset
	 */
	public function setDefinition($definition)
	{
		$this->definition = $definition;

			return $this;
	}

	/**
	 * Get definition
	 *
	 * @return string 
	 */
	public function getDefinition()
	{
			return $this->definition;
	}

    /**
     * Add mots
     *
     * @param \Carto\DonneesBundle\Entity\Mot $mots
     * @return NSynset
     */
    public function addMot(\Carto\DonneesBundle\Entity\Mot $mots)
    {
        $this->mots[] = $mots;

        return $this;
    }

    /**
     * Remove mots
     *
     * @param \Carto\DonneesBundle\Entity\Mot $mots
     */
    public function removeMot(\Carto\DonneesBundle\Entity\Mot $mots)
    {
        $this->mots->removeElement($mots);
    }

    /**
     * Get mots
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMots()
    {
        return $this->mots;
    }

    /**
     * Add antonyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $antonyms
     * @return NSynset
     */
    public function addAntonym(\Carto\DonneesBundle\Entity\NSynset $antonyms)
    {
        $this->antonyms[] = $antonyms;

        return $this;
    }

    /**
     * Remove antonyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $antonyms
     */
    public function removeAntonym(\Carto\DonneesBundle\Entity\NSynset $antonyms)
    {
        $this->antonyms->removeElement($antonyms);
    }

    /**
     * Get antonyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAntonyms()
    {
        return $this->antonyms;
    }

    /**
     * Add hypernyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $hypernyms
     * @return NSynset
     */
    public function addHypernym(\Carto\DonneesBundle\Entity\NSynset $hypernyms)
    {
        $this->hypernyms[] = $hypernyms;

        return $this;
    }

    /**
     * Remove hypernyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $hypernyms
     */
    public function removeHypernym(\Carto\DonneesBundle\Entity\NSynset $hypernyms)
    {
        $this->hypernyms->removeElement($hypernyms);
    }

    /**
     * Get hypernyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHypernyms()
    {
        return $this->hypernyms;
    }

    /**
     * Add hyponyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $hyponyms
     * @return NSynset
     */
    public function addHyponym(\Carto\DonneesBundle\Entity\NSynset $hyponyms)
    {
        $this->hyponyms[] = $hyponyms;

        return $this;
    }

    /**
     * Remove hyponyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $hyponyms
     */
    public function removeHyponym(\Carto\DonneesBundle\Entity\NSynset $hyponyms)
    {
        $this->hyponyms->removeElement($hyponyms);
    }

    /**
     * Get hyponyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHyponyms()
    {
        return $this->hyponyms;
    }

    /**
     * Add meronyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $meronyms
     * @return NSynset
     */
    public function addMeronym(\Carto\DonneesBundle\Entity\NSynset $meronyms)
    {
        $this->meronyms[] = $meronyms;

        return $this;
    }

    /**
     * Remove meronyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $meronyms
     */
    public function removeMeronym(\Carto\DonneesBundle\Entity\NSynset $meronyms)
    {
        $this->meronyms->removeElement($meronyms);
    }

    /**
     * Get meronyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMeronyms()
    {
        return $this->meronyms;
    }

    /**
     * Add holonyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $holonyms
     * @return NSynset
     */
    public function addHolonym(\Carto\DonneesBundle\Entity\NSynset $holonyms)
    {
        $this->holonyms[] = $holonyms;

        return $this;
    }

    /**
     * Remove holonyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $holonyms
     */
    public function removeHolonym(\Carto\DonneesBundle\Entity\NSynset $holonyms)
    {
        $this->holonyms->removeElement($holonyms);
    }

    /**
     * Get holonyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHolonyms()
    {
        return $this->holonyms;
    }

    /**
     * Add hasAttribute
     *
     * @param \Carto\DonneesBundle\Entity\ASynset $hasAttribute
     * @return NSynset
     */
    public function addHasAttribute(\Carto\DonneesBundle\Entity\ASynset $hasAttribute)
    {
        $this->hasAttribute[] = $hasAttribute;

        return $this;
    }

    /**
     * Remove hasAttribute
     *
     * @param \Carto\DonneesBundle\Entity\ASynset $hasAttribute
     */
    public function removeHasAttribute(\Carto\DonneesBundle\Entity\ASynset $hasAttribute)
    {
        $this->hasAttribute->removeElement($hasAttribute);
    }

    /**
     * Get hasAttribute
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHasAttribute()
    {
        return $this->hasAttribute;
    }
}
