<?php

namespace Carto\DonneesBundle\Entity\Humour;

use Doctrine\ORM\Mapping as ORM;

/**
 * Triplet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\Humour\TripletRepository")
 */
class Triplet
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
	 * @ORM\ManyToOne(targetEntity="Carto\DonneesBundle\Entity\Humour\Objet",inversedBy="triplets")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $sujet;

 /**
	 * @ORM\ManyToOne(targetEntity="Carto\DonneesBundle\Entity\Humour\Relation")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $relation;

 /**
	 * @ORM\ManyToOne(targetEntity="Carto\DonneesBundle\Entity\Humour\Objet")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $objet;

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
     * Set sujet
     *
     * @param \Carto\DonneesBundle\Entity\Humour\Objet $sujet
     * @return Triplet
     */
    public function setSujet(\Carto\DonneesBundle\Entity\Humour\Objet $sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return \Carto\DonneesBundle\Entity\Humour\Objet 
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set relation
     *
     * @param \Carto\DonneesBundle\Entity\Humour\Relation $relation
     * @return Triplet
     */
    public function setRelation(\Carto\DonneesBundle\Entity\Humour\Relation $relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Get relation
     *
     * @return \Carto\DonneesBundle\Entity\Humour\Relation 
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set objet
     *
     * @param \Carto\DonneesBundle\Entity\Humour\Objet $objet
     * @return Triplet
     */
    public function setObjet(\Carto\DonneesBundle\Entity\Humour\Objet $objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return \Carto\DonneesBundle\Entity\Humour\Objet 
     */
    public function getObjet()
    {
        return $this->objet;
    }
}
