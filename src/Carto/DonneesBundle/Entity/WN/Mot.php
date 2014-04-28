<?php
/**
	* Modèle des mots wordnet
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
 * Modèle des mots
 *
 * rappel : un mot n'est pas typé (adjectif, nom, verbe ou adverbe)
 * car il peut avoir plusieurs types selon le contexte
 * ex : update your computer (verb) / I've uploaded an update (nom)
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\WN\MotRepository")
 */
class Mot
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
	 * valeur du mot
	 *
	 * la chaine de caractère qui le représente
	 *
	 * @var string
	 *
	 * @ORM\Column(name="mot", type="string", length=255)
	 */
	private $mot;

	/**
	* les synsets de type nom auxquels il appartient
	* 
	* Relation ManyToMany entre un mot et un NSynset
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset",inversedBy="mots",cascade={"persist"})
	*/
	private $nsynsets;

	/**
	* les synsets de type adjectif auxquels il appartient
	* 
	* Relation ManyToMany entre un mot et un ASynset
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\ASynset",inversedBy="mots",cascade={"persist"})
	*/
	private $asynsets;

	/**
	* les synsets de type adverbe auxquels il appartient
	* 
	* Relation ManyToMany entre un mot et un RSynset
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\RSynset",inversedBy="mots",cascade={"persist"})
	*/
	private $rsynsets;

	/**
	* les synsets de type verbe auxquels il appartient
	* 
	* Relation ManyToMany entre un mot et un VSynset
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",inversedBy="mots",cascade={"persist"})
	*/
	private $vsynsets;

	/**
	* dérivation
	* 
	* Lien grammatical entre un nom et un verbe ou un adjectif
	* (ex : kindness dérive de kind)
	* Relation ManyToMany entre deux mots
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",inversedBy="deriveTo",cascade={"persist"})
	* @ORM\JoinTable(name="derivation")
	*/
	private $deriveFrom;

	/**
	* dérivation
	* 
	* Lien grammatical entre un nom et un verbe ou un adjectif
	* (ex : kind dérive en kindness)
	* Relation inverse de DeriveFrom
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="deriveFrom")
	*/
	private $deriveTo;

	/**
	* participle
	* 
	* Lien grammatical entre un adjectif dont la forme est le participe passé d'un verbe
	* (ex : avenged est le participe passé de avenge)
	* Relation OneToOne entre deux mots
	* 
	* @ORM\OneToOne(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",inversedBy="participleTo",cascade={"persist"})
	* @ORM\JoinColumn(name="participle")
	*/
	private $participleOf;

	/**
	* participle
	* 
	* Lien grammatical entre un adjectif dont la forme est le participe passé d'un verbe
	* (ex : avenge a pour participe passé avenged)
	* Relation inverse de participleOf
	* 
	* @ORM\OneToOne(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="participleOf")
	*/
	private $participleTo;

	/**
	* pertainym
	* 
	* Lien "faire partie de" entre un adjectif et un nom
	* (ex : dramatique signifie "qui fait partie des drames")
	* Relation ManyToMany entre deux mots
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",inversedBy="pertainTo",cascade={"persist"})
	* @ORM\JoinTable(name="pertainym")
	*/
	private $pertainFrom;

	/**
	* pertainym
	* 
	* Lien "faire partie de" entre un adjectif et un nom
	* (ex : dramatique signifie "qui fait partie des drames")
	* Relation inverse de pertainFrom
	* 
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="pertainFrom")
	*/
	private $pertainTo;

	/**
	* build
	* 
	* Relation grammatical entre un adjectif et un adverbe construit sur la base de cet adjectif 
	* (ex : l'adverbe sensibly est construit à partir de l'adjectif sensible)
	* Relation OneToOne entre deux mots
	* 
	* @ORM\OneToOne(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",inversedBy="build",cascade={"persist"})
	* @ORM\JoinColumn(name="builtFrom")
	*/
	private $builtFrom;

	/**
	* build
	* 
	* Relation grammatical entre un adjectif et un adverbe construit sur la base de cet adjectif 
	* (ex : l'adjectif sensible se transforme en l'adverbe sensibly )
	* Relation inverse de builtFrom
	* 
	* @ORM\OneToOne(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="builtFrom")
	*/
	private $build;

	/**
	* Constructeur 
	*
	* @param string $mot le mot trouvé dans les fichiers wordnet
	*/
	public function __construct($mot)
	{
		$this -> setMot($mot);
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
	 * Set mot
	 *
	 * @param string $mot
	 * @return Mot
	 */
	public function setMot($mot)
	{
		$this->mot = $mot;

		return $this;
	}

	/**
	 * Get mot
	 *
	 * @return string 
	 */
	public function getMot()
	{
		return $this->mot;
	}

	/**
	 * Add nsynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $nsynsets
	 * @return Mot
	 */
	public function addNsynset(\Carto\DonneesBundle\Entity\WN\NSynset $nsynsets)
	{
		$this->nsynsets[] = $nsynsets;

		return $this;
	}

	/**
	 * Remove nsynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $nsynsets
	 */
	public function removeNsynset(\Carto\DonneesBundle\Entity\WN\NSynset $nsynsets)
	{
		$this->nsynsets->removeElement($nsynsets);
	}

	/**
	 * Get nsynsets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getNsynsets()
	{
		return $this->nsynsets;
	}

	/**
	 * Add asynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $asynsets
	 * @return Mot
	 */
	public function addAsynset(\Carto\DonneesBundle\Entity\WN\ASynset $asynsets)
	{
		$this->asynsets[] = $asynsets;

		return $this;
	}

	/**
	 * Remove asynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $asynsets
	 */
	public function removeAsynset(\Carto\DonneesBundle\Entity\WN\ASynset $asynsets)
	{
		$this->asynsets->removeElement($asynsets);
	}

	/**
	 * Get asynsets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getAsynsets()
	{
		return $this->asynsets;
	}

	/**
	 * Add rsynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\RSynset $rsynsets
	 * @return Mot
	 */
	public function addRsynset(\Carto\DonneesBundle\Entity\WN\RSynset $rsynsets)
	{
		$this->rsynsets[] = $rsynsets;

		return $this;
	}

	/**
	 * Remove rsynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\RSynset $rsynsets
	 */
	public function removeRsynset(\Carto\DonneesBundle\Entity\WN\RSynset $rsynsets)
	{
		$this->rsynsets->removeElement($rsynsets);
	}

	/**
	 * Get rsynsets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getRsynsets()
	{
		return $this->rsynsets;
	}

	/**
	 * Add vsynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\VSynset $vsynsets
	 * @return Mot
	 */
	public function addVsynset(\Carto\DonneesBundle\Entity\WN\VSynset $vsynsets)
	{
		$this->vsynsets[] = $vsynsets;

		return $this;
	}

	/**
	 * Remove vsynsets
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\VSynset $vsynsets
	 */
	public function removeVsynset(\Carto\DonneesBundle\Entity\WN\VSynset $vsynsets)
	{
		$this->vsynsets->removeElement($vsynsets);
	}

	/**
	 * Get vsynsets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVsynsets()
	{
		return $this->vsynsets;
	}

	/**
	 * Add deriveFrom
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $deriveFrom
	 * @return Mot
	 */
	public function addDeriveFrom(\Carto\DonneesBundle\Entity\WN\Mot $deriveFrom)
	{
		$this->deriveFrom[] = $deriveFrom;

		return $this;
	}

	/**
	 * Remove deriveFrom
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $deriveFrom
	 */
	public function removeDeriveFrom(\Carto\DonneesBundle\Entity\WN\Mot $deriveFrom)
	{
		$this->deriveFrom->removeElement($deriveFrom);
	}

	/**
	 * Get deriveFrom
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getDeriveFrom()
	{
		return $this->deriveFrom;
	}

	/**
	 * Add deriveTo
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $deriveTo
	 * @return Mot
	 */
	public function addDeriveTo(\Carto\DonneesBundle\Entity\WN\Mot $deriveTo)
	{
		$this->deriveTo[] = $deriveTo;

		return $this;
	}

	/**
	 * Remove deriveTo
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $deriveTo
	 */
	public function removeDeriveTo(\Carto\DonneesBundle\Entity\WN\Mot $deriveTo)
	{
		$this->deriveTo->removeElement($deriveTo);
	}

	/**
	 * Get deriveTo
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getDeriveTo()
	{
		return $this->deriveTo;
	}

	/**
	 * Set participleOf
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $participleOf
	 * @return Mot
	 */
	public function setParticipleOf(\Carto\DonneesBundle\Entity\WN\Mot $participleOf = null)
	{
		$this->participleOf = $participleOf;

		return $this;
	}

	/**
	 * Get participleOf
	 *
	 * @return \Carto\DonneesBundle\Entity\WN\Mot 
	 */
	public function getParticipleOf()
	{
		return $this->participleOf;
	}

	/**
	 * Set participleTo
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $participleTo
	 * @return Mot
	 */
	public function setParticipleTo(\Carto\DonneesBundle\Entity\WN\Mot $participleTo = null)
	{
		$this->participleTo = $participleTo;

		return $this;
	}

	/**
	 * Get participleTo
	 *
	 * @return \Carto\DonneesBundle\Entity\WN\Mot 
	 */
	public function getParticipleTo()
	{
		return $this->participleTo;
	}

	/**
	 * Add pertainFrom
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $pertainFrom
	 * @return Mot
	 */
	public function addPertainFrom(\Carto\DonneesBundle\Entity\WN\Mot $pertainFrom)
	{
		$this->pertainFrom[] = $pertainFrom;

		return $this;
	}

	/**
	 * Remove pertainFrom
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $pertainFrom
	 */
	public function removePertainFrom(\Carto\DonneesBundle\Entity\WN\Mot $pertainFrom)
	{
		$this->pertainFrom->removeElement($pertainFrom);
	}

	/**
	 * Get pertainFrom
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getPertainFrom()
	{
		return $this->pertainFrom;
	}

	/**
	 * Add pertainTo
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $pertainTo
	 * @return Mot
	 */
	public function addPertainTo(\Carto\DonneesBundle\Entity\WN\Mot $pertainTo)
	{
		$this->pertainTo[] = $pertainTo;

		return $this;
	}

	/**
	 * Remove pertainTo
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $pertainTo
	 */
	public function removePertainTo(\Carto\DonneesBundle\Entity\WN\Mot $pertainTo)
	{
		$this->pertainTo->removeElement($pertainTo);
	}

	/**
	 * Get pertainTo
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getPertainTo()
	{
		return $this->pertainTo;
	}

	/**
	 * Set builtFrom
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $builtFrom
	 * @return Mot
	 */
	public function setBuiltFrom(\Carto\DonneesBundle\Entity\WN\Mot $builtFrom = null)
	{
		$this->builtFrom = $builtFrom;

		return $this;
	}

	/**
	 * Get builtFrom
	 *
	 * @return \Carto\DonneesBundle\Entity\WN\Mot 
	 */
	public function getBuiltFrom()
	{
		return $this->builtFrom;
	}

	/**
	 * Set build
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\Mot $build
	 * @return Mot
	 */
	public function setBuild(\Carto\DonneesBundle\Entity\WN\Mot $build = null)
	{
		$this->build = $build;

		return $this;
	}

	/**
	 * Get build
	 *
	 * @return \Carto\DonneesBundle\Entity\WN\Mot 
	 */
	public function getBuild()
	{
		return $this->build;
	}
}
