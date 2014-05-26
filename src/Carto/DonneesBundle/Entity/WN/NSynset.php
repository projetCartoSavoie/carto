<?php
/**
	* Modèle des synsets de type nom
	*
	* rappel : un synset est un ensemble de mots synonymes les uns des autres.
	*
	* @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
	* @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
	* @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
	* @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
	* @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
	*
	* @version 1.0
	*/
namespace Carto\DonneesBundle\Entity\WN;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modèle des synsets de type nom
 *
 * rappel : un synset est un ensemble de mots synonymes les uns des autres.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\WN\NSynsetRepository")
 */
class NSynset
{
	/**
	 * identification dans la base de données
	 *
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * identification dans la base wordnet
	 *
	 * utile uniquement lors de l'import de la base wordnet
	 *
	 * @var string
	 *
	 * @ORM\Column(name="wnid", type="string", length=10)
	 */
	private $wnid;

	/**
	 * Définition du concept représenté par le synset
	 *
	 * @var string
	 *
	 * @ORM\Column(name="definition", type="text")
	 */
	private $definition;

	/**
	 * Liste des mots appartenant à ce synset
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="nsynsets")
	 */
	private $mots;

	/**
	 * Liste des antonymes de ce synset
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset")
	 * @ORM\JoinTable(name="nantonyms")
	 */
	private $antonyms;

	/**
	 * Liste des hypernymes de ce synset (relation de généralisation)
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset",inversedBy="hyponyms")
	 * @ORM\JoinTable(name="nhypernyms")
	 */
	private $hypernyms;

	/**
	 * Liste des hyponymes de ce synset (relation de spécialisation)
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset",mappedBy="hypernyms")
	 */
	private $hyponyms;

	/**
	 * Liste des méronymes de ce synset (relation partitive)
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset",inversedBy="holonyms")
	 * @ORM\JoinTable(name="nmeronyms")
	 */
	private $meronyms;

	/**
	 * Liste des holonymes de ce synset (inverse de la relation partitive)
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset",mappedBy="meronyms")
	 */
	private $holonyms;

	/**
	 * Liste des attributs de ce synset (relation d'attribut entre nom et adjectif)
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\ASynset",inversedBy="isAttributeOf")
	 * @ORM\JoinTable(name="naattributes")
	 */
	private $hasAttribute;

	/**
	 * Constructeur
	 *
	 * @param string $wnid : identificateur du synset dans les fichiers textes téléchargés de WordNet
	 * @param string $def : définition du concept représenté par le synset
	 */
	public function __construct($wnid,$def)
	{
		$this -> setDefinition($def);
		$this -> setWnid($wnid);
	}

	/**
	 * Quel est le type du synset ? 
	 *
	 * @return string : 'N' pour nom
	 */
	public function getType()
	{
		return 'N';
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
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $mots
	 * @return NSynset
	 */
	public function addMot(\Carto\DonneesBundle\Entity\WN\Mot $mots)
	{
			$this->mots[] = $mots;

			return $this;
	}

	/**
	 * Remove mots
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $mots
	 */
	public function removeMot(\Carto\DonneesBundle\Entity\WN\Mot $mots)
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
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $antonyms
	 * @return NSynset
	 */
	public function addAntonym(\Carto\DonneesBundle\Entity\WN\NSynset $antonyms)
	{
			$this->antonyms[] = $antonyms;

			return $this;
	}

	/**
	 * Remove antonyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $antonyms
	 */
	public function removeAntonym(\Carto\DonneesBundle\Entity\WN\NSynset $antonyms)
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
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $hypernyms
	 * @return NSynset
	 */
	public function addHypernym(\Carto\DonneesBundle\Entity\WN\NSynset $hypernyms)
	{
			$this->hypernyms[] = $hypernyms;

			return $this;
	}

	/**
	 * Remove hypernyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $hypernyms
	 */
	public function removeHypernym(\Carto\DonneesBundle\Entity\WN\NSynset $hypernyms)
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
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $hyponyms
	 * @return NSynset
	 */
	public function addHyponym(\Carto\DonneesBundle\Entity\WN\NSynset $hyponyms)
	{
			$this->hyponyms[] = $hyponyms;

			return $this;
	}

	/**
	 * Remove hyponyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $hyponyms
	 */
	public function removeHyponym(\Carto\DonneesBundle\Entity\WN\NSynset $hyponyms)
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
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $meronyms
	 * @return NSynset
	 */
	public function addMeronym(\Carto\DonneesBundle\Entity\WN\NSynset $meronyms)
	{
			$this->meronyms[] = $meronyms;

			return $this;
	}

	/**
	 * Remove meronyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $meronyms
	 */
	public function removeMeronym(\Carto\DonneesBundle\Entity\WN\NSynset $meronyms)
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
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $holonyms
	 * @return NSynset
	 */
	public function addHolonym(\Carto\DonneesBundle\Entity\WN\NSynset $holonyms)
	{
			$this->holonyms[] = $holonyms;

			return $this;
	}

	/**
	 * Remove holonyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $holonyms
	 */
	public function removeHolonym(\Carto\DonneesBundle\Entity\WN\NSynset $holonyms)
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
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $hasAttribute
	 * @return NSynset
	 */
	public function addHasAttribute(\Carto\DonneesBundle\Entity\WN\ASynset $hasAttribute)
	{
			$this->hasAttribute[] = $hasAttribute;

			return $this;
	}

	/**
	 * Remove hasAttribute
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $hasAttribute
	 */
	public function removeHasAttribute(\Carto\DonneesBundle\Entity\WN\ASynset $hasAttribute)
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
