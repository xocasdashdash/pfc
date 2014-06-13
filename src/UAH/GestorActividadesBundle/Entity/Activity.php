<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PostPersist;
use Doctrine\ORM\Mapping\PostUpdate;
use Doctrine\ORM\Mapping\PostRemove;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UAH\GestorActividadesBundle\Entity\Degree as Degree;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Activity
 *
 * @Table(name="UAH_GAT_Activity")
 * @Entity()
 * @HasLifecycleCallbacks
 */
class Activity {

    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=500)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="englishName", type="string", length=500)
     */
    private $englishName;

    /**
     * @var \stdClass
     * @ManyToOne(targetEntity="User", inversedBy="activities")
     * @JoinColumn(name="organizer", referencedColumnName="id", nullable=false)
     */
    private $Organizer;

    /**
     * @var float
     *
     * @Column(name="numberOfECTSCreditsMin", type="float")
     */
    private $numberOfECTSCreditsMin;

    /**
     * @var float
     *
     * @Column(name="numberOfECTSCreditsMax", type="float")
     */
    private $numberOfECTSCreditsMax;

    /**
     * @var float
     *
     * @Column(name="numberOfCreditsMin", type="float")
     */
    private $numberOfCreditsMin;

    /**
     * @var float
     *
     * @Column(name="numberOfCreditsMax", type="float")
     */
    private $numberOfCreditsMax;

    /**
     * @var array
     *
     * @Column(name="celebrationDates", type="json_array")
     */
    private $celebrationDates;

    /**
     * @var boolean
     *
     * @Column(name="hasAdditionalWorkload", type="boolean", nullable=true)
     */
    private $hasAdditionalWorkload;

    /**
     * @var float
     *
     * @Column(name="numberOfHours", type="float")
     */
    private $numberOfHours;

    /**
     * @ManyToMany(targetEntity="Degree")
     * @JoinTable(name="UAH_GAT_Activity_Degree", 
     *          joinColumns={@JoinColumn(name="activity_id", referencedColumnName="id")},
     *          inverseJoinColumns={@JoinColumn(name="degree_id", referencedColumnName="id")})
     * */
    private $studentProfile;

    /**
     * @var string
     *
     * @Column(name="assistanceControl", type="string")
     */
    private $assistanceControl;

    /**
     * @var date
     * Tiempo en unix (segundos desde el 1 de enero de 1970)
     * @Column(name="publicityStartDate", type="datetime", nullable=true)
     */
    private $publicityStartDate;

    /**
     * @var boolean
     *
     * @Column(name="registrationManagement", type="boolean", nullable=true)
     */
    private $registrationManagement;

    /**
     * @var boolean
     *
     * @Column(name="extraInformationFile", type="blob",nullable=true)
     */
    private $extraInformationFile;

    /**
     * @var string
     *
     * @Column(name="url", type="string")
     */
    private $url;

    /**
     * @var string
     *
     * @Column(name="slug", type="string")
     */
    private $slug;

    /**
     * @var integer
     *
     * @Column(name="numberOfPlacesOffered", type="integer")
     */
    private $numberOfPlacesOffered;

    /**
     * @var integer
     *
     * @Column(name="numberOfPlacesOccupied", type="integer")
     */
    private $numberOfPlacesOccupied;

    /**
     * @var boolean
     *
     * @Column(name="approvedByComitee", type="boolean")
     */
    private $approvedByComitee;

    /**
     * @var float
     *
     * @Column(name="cost", type="float")
     */
    private $cost;

    /**
     * @var int Estado del registro
     * @ManyToOne(targetEntity="Statusactivity")
     * @JoinColumn(name="status_activity", referencedColumnName="id")
     */
    private $status;

    /**
     * @var 
     * 
     * @Column(name="description", type="text")
     */
    private $description;

    /**
     * @var 
     * 
     * @Column(name="image_path", type="string")
     */
    private $image_path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $image_blob;

    /**
     *
     * @var type
     * @OneToMany(targetEntity="Enrollment", mappedBy="activity")
     */
    private $activities;

    /**
     * Constructor
     */
    public function __construct() {
        $this->studentProfile = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Activity
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set englishName
     *
     * @param string $englishName
     * @return Activity
     */
    public function setEnglishName($englishName) {
        $this->englishName = $englishName;

        return $this;
    }

    /**
     * Get englishName
     *
     * @return string 
     */
    public function getEnglishName() {
        return $this->englishName;
    }

    /**
     * Set numberOfECTSCredits
     *
     * @param float $numberOfECTSCredits
     * @return Activity
     */
    public function setNumberOfECTSCredits($numberOfECTSCredits) {
        $this->numberOfECTSCredits = $numberOfECTSCredits;

        return $this;
    }

    /**
     * Get numberOfECTSCredits
     *
     * @return float 
     */
    public function getNumberOfECTSCredits() {
        return $this->numberOfECTSCredits;
    }

    /**
     * Set celebrationDates
     *
     * @param array $celebrationDates
     * @return Activity
     */
    public function setCelebrationDates($celebrationDates) {
        $this->celebrationDates = $celebrationDates;

        return $this;
    }

    /**
     * Get celebrationDates
     *
     * @return array 
     */
    public function getCelebrationDates() {
        return $this->celebrationDates;
    }

    /**
     * Set hasAdditionalWorkload
     *
     * @param boolean $hasAdditionalWorkload
     * @return Activity
     */
    public function setHasAdditionalWorkload($hasAdditionalWorkload) {
        $this->hasAdditionalWorkload = $hasAdditionalWorkload;

        return $this;
    }

    /**
     * Get hasAdditionalWorkload
     *
     * @return boolean 
     */
    public function getHasAdditionalWorkload() {
        return $this->hasAdditionalWorkload;
    }

    /**
     * Set numberOfHours
     *
     * @param float $numberOfHours
     * @return Activity
     */
    public function setNumberOfHours($numberOfHours) {
        $this->numberOfHours = $numberOfHours;

        return $this;
    }

    /**
     * Get numberOfHours
     *
     * @return float 
     */
    public function getNumberOfHours() {
        return $this->numberOfHours;
    }

    /**
     * Set assistanceControl
     *
     * @param string $assistanceControl
     * @return Activity
     */
    public function setAssistanceControl($assistanceControl) {
        $this->assistanceControl = $assistanceControl;

        return $this;
    }

    /**
     * Get assistanceControl
     *
     * @return string 
     */
    public function getAssistanceControl() {
        return $this->assistanceControl;
    }

    /**
     * Set publicityStartDate
     *
     * @param \DateTime $publicityStartDate
     * @return Activity
     */
    public function setPublicityStartDate(\DateTime $publicityStartDate) {
        //Guardo el tiempo con un entero de unix
        $this->publicityStartDate = $publicityStartDate;
        return $this;
    }

    /**
     * Get publicityStartDate
     *
     * @return \DateTime 
     */
    public function getPublicityStartDate() {
        return $this->publicityStartDate;
    }

    /**
     * Set registrationManagement
     *
     * @param boolean $registrationManagement
     * @return Activity
     */
    public function setRegistrationManagement($registrationManagement) {
        $this->registrationManagement = $registrationManagement;

        return $this;
    }

    /**
     * Get registrationManagement
     *
     * @return boolean 
     */
    public function getRegistrationManagement() {
        return $this->registrationManagement;
    }

    /**
     * Set extraInformationFile
     *
     * @param string $extraInformationFile
     * @return Activity
     */
    public function setExtraInformationFile($extraInformationFile) {
        $this->extraInformationFile = $extraInformationFile;

        return $this;
    }

    /**
     * Get extraInformationFile
     *
     * @return string 
     */
    public function getExtraInformationFile() {
        return $this->extraInformationFile;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Activity
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Activity
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set numberOfPlacesOffered
     *
     * @param integer $numberOfPlacesOffered
     * @return Activity
     */
    public function setNumberOfPlacesOffered($numberOfPlacesOffered) {
        $this->numberOfPlacesOffered = $numberOfPlacesOffered;

        return $this;
    }

    /**
     * Get numberOfPlacesOffered
     *
     * @return integer 
     */
    public function getNumberOfPlacesOffered() {
        return $this->numberOfPlacesOffered;
    }

    /**
     * Set numberOfPlacesOccupied
     *
     * @param integer $numberOfPlacesOccupied
     * @return Activity
     */
    public function setNumberOfPlacesOccupied($numberOfPlacesOccupied) {
        $this->numberOfPlacesOccupied = $numberOfPlacesOccupied;

        return $this;
    }

    /**
     * Get numberOfPlacesOccupied
     *
     * @return integer 
     */
    public function getNumberOfPlacesOccupied() {
        return $this->numberOfPlacesOccupied;
    }

    /**
     * Set approved_by_comitee
     *
     * @param boolean $approvedByComitee
     * @return Activity
     */
    public function setApprovedByComitee($approvedByComitee) {
        $this->approvedByComitee = $approvedByComitee;

        return $this;
    }

    /**
     * Get approved_by_comitee
     *
     * @return boolean 
     */
    public function getApprovedByComitee() {
        return $this->approvedByComitee;
    }

    /**
     * Set cost
     *
     * @param float $cost
     * @return Activity
     */
    public function setCost($cost) {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float 
     */
    public function getCost() {
        return $this->cost;
    }

    /**
     * Set Organizer
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $organizer
     * @return Activity
     */
    public function setOrganizer(\UAH\GestorActividadesBundle\Entity\User $organizer) {
        $this->Organizer = $organizer;

        return $this;
    }

    /**
     * Get Organizer
     *
     * @return \UAH\GestorActividadesBundle\Entity\User 
     */
    public function getOrganizer() {
        return $this->Organizer;
    }

    /**
     * Add studentProfile
     *
     * @param \UAH\GestorActividadesBundle\Entity\Degree $studentProfile
     * @return Activity
     */
    public function addStudentProfile(\UAH\GestorActividadesBundle\Entity\Degree $studentProfile) {
        $this->studentProfile[] = $studentProfile;

        return $this;
    }

    /**
     * Remove studentProfile
     *
     * @param \UAH\GestorActividadesBundle\Entity\Degree $studentProfile
     */
    public function removeStudentProfile(\UAH\GestorActividadesBundle\Entity\Degree $studentProfile) {
        $this->studentProfile->removeElement($studentProfile);
    }

    /**
     * Get studentProfile
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudentProfile() {
        return $this->studentProfile;
    }

    /**
     * Set numberOfECTSCreditsMin
     *
     * @param float $numberOfECTSCreditsMin
     * @return Activity
     */
    public function setNumberOfECTSCreditsMin($numberOfECTSCreditsMin) {
        $this->numberOfECTSCreditsMin = $numberOfECTSCreditsMin;

        return $this;
    }

    /**
     * Get numberOfECTSCreditsMin
     *
     * @return float 
     */
    public function getNumberOfECTSCreditsMin() {
        return $this->numberOfECTSCreditsMin;
    }

    /**
     * Set numberOfECTSCreditsMax
     *
     * @param float $numberOfECTSCreditsMax
     * @return Activity
     */
    public function setNumberOfECTSCreditsMax($numberOfECTSCreditsMax) {
        $this->numberOfECTSCreditsMax = $numberOfECTSCreditsMax;

        return $this;
    }

    /**
     * Get numberOfECTSCreditsMax
     *
     * @return float 
     */
    public function getNumberOfECTSCreditsMax() {
        return $this->numberOfECTSCreditsMax;
    }

    /**
     * Set numberOfCreditsMin
     *
     * @param float $numberOfCreditsMin
     * @return Activity
     */
    public function setNumberOfCreditsMin($numberOfCreditsMin) {
        $this->numberOfCreditsMin = $numberOfCreditsMin;

        return $this;
    }

    /**
     * Get numberOfCreditsMin
     *
     * @return float 
     */
    public function getNumberOfCreditsMin() {
        return $this->numberOfCreditsMin;
    }

    /**
     * Set numberOfCreditsMax
     *
     * @param float $numberOfCreditsMax
     * @return Activity
     */
    public function setNumberOfCreditsMax($numberOfCreditsMax) {
        $this->numberOfCreditsMax = $numberOfCreditsMax;

        return $this;
    }

    /**
     * Get numberOfCreditsMax
     *
     * @return float 
     */
    public function getNumberOfCreditsMax() {
        return $this->numberOfCreditsMax;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Activity
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Activity
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set image_path
     *
     * @param string $imagePath
     * @return Activity
     */
    public function setImagePath($imagePath) {
        $this->image_path = $imagePath;

        return $this;
    }

    /**
     * Get image_path
     *
     * @return string 
     */
    public function getImagePath() {
        return $this->getImageWebPath(); //image_path;
    }

    /**
     * Set image_blob
     *
     * @param string $imageBlob
     * @return Activity
     */
    public function setImageBlob(UploadedFile $imageBlob) {
        $this->image_blob = $imageBlob;

        return $this;
    }

    /**
     * Get image_blob
     *
     * @return string 
     */
    public function getImageBlob() {
        return $this->image_blob;
    }

    public function getAbsolutePath() {
        return null === $this->image_path ? null : $this->getUploadRootDir() . '/' . $this->image_path;
    }

    public function getImageWebPath() {
        return null === $this->image_path ? null : $this->getUploadDir() . '/' . $this->image_path;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload/images';
    }

    /**
     * @PrePersist
     * @PreUpdate
     */
    public function preUpload(LifecycleEventArgs $event) {

        // Genero un slug para cada actividad
        // replace non letter or digits by -
        $slug = preg_replace('~[^\\pL\d]+~u', '-', $this->getName());
        // trim
        $slug = trim($slug, '-');
        // transliterate
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        // lowercase
        $slug = strtolower($slug);
        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        if (empty($slug)) {
            $slug = 'n-a';
        }
        $this->setSlug($slug);

        if (null !== $this->getImageBlob()) {
            // do whatever you want to generate a unique name
            $filename = sha1($this->getSlug() . uniqid(mt_rand(), true));
            $this->image_path = $filename . '.' . $this->getImageBlob()->guessExtension();
        }
        if (is_null($this->getStatus())) {
            $em = $event->getEntityManager();
            $default_status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getDefault();
            $this->setStatus($default_status);
        }
    }

    /**
     * @PostPersist()
     * @PostUpdate()
     */
    public function upload() {
        if (null === $this->getImageBlob()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getImageBlob()->move($this->getUploadRootDir(), $this->image_path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->image_blob = null;
    }

    /**
     * @PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

}
