<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/26/15
 * Time: 11:59 AM
 */

namespace Code_Alchemy\Multi_Language_Support;


use Code_Alchemy\Arrays\Operators\Extract_Member_from_Array_Objects;
use Code_Alchemy\Core\Array_Object;

/**
 * Class MLS_Settings
 * @package Code_Alchemy\Multi_Language_Support
 *
 * Settings for MLS
 */
class MLS_Settings extends Array_Object{

    /**
     * @var array of languages
     */
    private $languages = array();

    /**
     * @var array of language uris supported
     */
    private $language_uris = array();

    /**
     * @var MLS_Language
     */
    private $default_language;

    /**
     * MLS_Settings constructor.
     * @param array $members
     */
    public function __construct(array $members) {

        parent::__construct($members);

        $this->default_language = new MLS_Language(array());

        if ( is_array(@$members['languages']))

            foreach ( $members['languages'] as $aLanguage ){

                $oLanguage = new MLS_Language($aLanguage);

                $this->languages[] = $oLanguage;

                if ( $oLanguage->default )

                    $this->default_language = $oLanguage;

            }


        $this->language_uris =

            (new Extract_Member_from_Array_Objects('uri',$this->languages))

            ->as_array();


    }

    /**
     * @return array of Language URIs
     */
    public function language_uris(){ return $this->language_uris; }

    /**
     * @return string default uri
     */
    public function default_uri(){ return $this->default_language->uri; }

}