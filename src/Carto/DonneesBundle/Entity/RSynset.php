<?php

namespace Carto\DonneesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RSynset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\RSynsetRepository")
 */
class RSynset
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
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\Mot",mappedBy="rsynsets")
	*/
	private $mots;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\RSynset")
	* @ORM\JoinTable(name="rantonyms")
	*/
	private $antonyms;

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
     * @return RSynset
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
     * @return RSynset
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
     * @return RSynset
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
     * @param \Carto\DonneesBundle\Entity\RSynset $antonyms
     * @return RSynset
     */
    public function addAntonym(\Carto\DonneesBundle\Entity\RSynset $antonyms)
    {
        $this->antonyms[] = $antonyms;

        return $this;
    }

    /**
     * Remove antonyms
     *
     * @param \Carto\DonneesBundle\Entity\RSynset $antonyms
     */
    public function removeAntonym(\Carto\DonneesBundle\Entity\RSynset $antonyms)
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
}
