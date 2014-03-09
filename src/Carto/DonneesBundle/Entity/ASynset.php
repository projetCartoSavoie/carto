<?php

namespace Carto\DonneesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ASynset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\ASynsetRepository")
 */
class ASynset
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
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\Mot",mappedBy="asynsets")
	*/
	private $mots;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\NSynset",mappedBy="hasAttribute")
	*/
	private $isAttributeOf;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\ASynset")
	* @ORM\JoinTable(name="aantonyms")
	*/
	private $antonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\ASynset")
	* @ORM\JoinTable(name="asimilars")
	*/
	private $similars;

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
     * @return ASynset
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
     * @return ASynset
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
     * @return ASynset
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
     * Add isAttributeOf
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $isAttributeOf
     * @return ASynset
     */
    public function addIsAttributeOf(\Carto\DonneesBundle\Entity\NSynset $isAttributeOf)
    {
        $this->isAttributeOf[] = $isAttributeOf;

        return $this;
    }

    /**
     * Remove isAttributeOf
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $isAttributeOf
     */
    public function removeIsAttributeOf(\Carto\DonneesBundle\Entity\NSynset $isAttributeOf)
    {
        $this->isAttributeOf->removeElement($isAttributeOf);
    }

    /**
     * Get isAttributeOf
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIsAttributeOf()
    {
        return $this->isAttributeOf;
    }

    /**
     * Add antonyms
     *
     * @param \Carto\DonneesBundle\Entity\ASynset $antonyms
     * @return ASynset
     */
    public function addAntonym(\Carto\DonneesBundle\Entity\ASynset $antonyms)
    {
        $this->antonyms[] = $antonyms;

        return $this;
    }

    /**
     * Remove antonyms
     *
     * @param \Carto\DonneesBundle\Entity\ASynset $antonyms
     */
    public function removeAntonym(\Carto\DonneesBundle\Entity\ASynset $antonyms)
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
     * Add similars
     *
     * @param \Carto\DonneesBundle\Entity\ASynset $similars
     * @return ASynset
     */
    public function addSimilar(\Carto\DonneesBundle\Entity\ASynset $similars)
    {
        $this->similars[] = $similars;

        return $this;
    }

    /**
     * Remove similars
     *
     * @param \Carto\DonneesBundle\Entity\ASynset $similars
     */
    public function removeSimilar(\Carto\DonneesBundle\Entity\ASynset $similars)
    {
        $this->similars->removeElement($similars);
    }

    /**
     * Get similars
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSimilars()
    {
        return $this->similars;
    }
}
