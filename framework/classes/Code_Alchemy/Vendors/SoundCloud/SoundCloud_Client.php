<?php
namespace Code_Alchemy\Vendors\SoundCloud;


use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Configuration_File;

class SoundCloud_Client {

    /**
     * @var \Services_Soundcloud client
     */
    private $client = null;

    /**
     * Construct the Bean
     */
    public function __construct(){

        $this->client = $this->get_client();

    }


    /**
     * @param string $track_id to download
     */
    public function download_track( $track_id ){

        //\FB::log("Delete SOundCloud track $track_id");
        // Configuring curl options
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
        );

        $get = $this->api()->download($track_id, array(), $options);
        //$get = $this->api()->download($track_id, array(), array());
        $track = json_decode($get, true);

        //\FB::log("Result from API is ".$get);

        return $track;

    }



    public function tracks( $link ){

        // Configuring curl options
        $options = array(
            CURLOPT_FOLLOWLOCATION =>true,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );

        $get = $this->api()->get($link, array('url' => $link),$options);

        $track = json_decode($get, true);

        //\FB::log("Result from API is ".$get);

        return $track;
    }



    /**
     * Resolve a link
     * @param $link
     * @return mixed
     */
    public function resolve( $link ){

        // Configuring curl options
        $options = array(
            CURLOPT_FOLLOWLOCATION =>true,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );



        $get = $this->api()->get('resolve', array('url' => $link),$options);

        $track = json_decode($get, true);

        //\FB::log("Result from API is ".$get);

        return $track;
    }

    /**
     * @param string $track_id to delete
     */
    public function delete_track( $track_id ){

        //\FB::log("Delete SOundCloud track $track_id");

        $this->api()->delete('tracks/'.$track_id) ;

    }

    /**
     * @return \Services_Soundcloud client
     */
    private function get_client(){

        $soundcloud = (new Configuration_File())->find('soundcloud',true);

        // create client object with app credentials
        $client = new \Services_Soundcloud(
            (string)$soundcloud->client_id,
            (string)$soundcloud->client_secret,
            (string)$soundcloud->redirect_url
        );

        return $client;
    }

    /**
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function set_user_flow( $username, $password ){

        $credentialsFlow = $this->client->credentialsFlow( $username, $password);

        //\FB::log($credentialsFlow);

        return $credentialsFlow;

    }

    /**
     * @return \Services_Soundcloud API client
     */
    public function api(){

        return $this->client;

    }

}