<?php

namespace Carto\DonneesBundle\Entity\Humour;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\Humour\RelationRepository")
 */
class Relation
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
	 * @ORM\Column(name="titre", type="string", length=255)
	 */
	private $titre;

	/**
	* liste des triplets dont cette relation est la relation
	*
	* @ORM\OneToMany(targetEntity="Carto\DonneesBundle\Entity\Humour\Triplet",mappedBy="relation")
	*/
	private $triplets;

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
	 * Set titre
	 *
	 * @param string $titre
	 * @return Relation
	 */
	public function setTitre($titre)
	{
		$this->titre = $titre;

		return $this;
	}

	/**
	 * Get titre
	 *
	 * @return string 
	 */
	public function getTitre()
	{
		return $this->titre;
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->triplets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add triplets
     *
     * @param \Carto\DonneesBundle\Entity\Humour\Triplet $triplets
     * @return Relation
     */
    public function addTriplet(\Carto\DonneesBundle\Entity\Humour\Triplet $triplets)
    {
        $this->triplets[] = $triplets;

        return $this;
    }

    /**
     * Remove triplets
     *
     * @param \Carto\DonneesBundle\Entity\Humour\Triplet $triplets
     */
    public function removeTriplet(\Carto\DonneesBundle\Entity\Humour\Triplet $triplets)
    {
        $this->triplets->removeElement($triplets);
    }

    /**
     * Get triplets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTriplets()
    {
        return $this->triplets;
    }
}
