<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/14/15
 * Time: 8:49 AM
 */

namespace Code_Alchemy\Vendors\Google\Calendar;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Code_Alchemy_Framework;

/**
 * Class Google_Calendar_API
 * @package Code_Alchemy\Vendors\Google\Calendar
 *
 * Google Calendar API
 */
class Google_Calendar_API extends Alchemist{


    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @var string API token
     */
    private $token = '';

    /**
     * @var string Application Name
     */
    private $application_name = 'Code Alchemy Google Calendar API';

    /**
     * @var string Scopes for access
     */
    private $scopes = '';

    /**
     * @var \Google_Service_Calendar
     */
    private $service;

    /**
     * @var string Client secret path
     */
    private $client_secret_path = '';

    /**
     * @var string credentials path
     */
    private $credentials_path = '';

    /**
     * @var string timezone offset when checking availability
     */
    private $timezone_offset = '-05:00';


    /**
     * @param array $options optional for instantiating bean
     */
    public function __construct( array $options = array()){

        // Initialize Scopes
        $this->scopes = implode(' ', array(

                \Google_Service_Calendar::CALENDAR)

        );

        // Initialize client secret
        $this->client_secret_path = Code_Alchemy_Framework::instance()->webroot().

            "/.credentials/client_secret.json";

        // Set user's options
        foreach ( $options as $option => $value )

            if ( property_exists($this,$option))

                $this->$option = $value;

        // Get the API client and construct the service object.
        $this->client = $this->getClient( $this->token );

        // Get service
        $this->service = new \Google_Service_Calendar($this->client);

    }

    /**
     * @param string $calendar_id
     * @param array $optParams
     * @return \Google_Service_Calendar_Events
     */
    public function events( $calendar_id, array $optParams = array() ){

        return $this->service->events->listEvents( $calendar_id , $optParams );

    }

    /**
     * @param $calendar_id
     * @param $event_id
     * @return \Google_Service_Calendar_Event
     */
    public function event( $calendar_id, $event_id ){

        return $this->service->events->get($calendar_id,$event_id);

    }

    /**
     * @return \Google_Service_Calendar_CalendarList
     */
    public function calendars(){

        return $this->service->calendarList->listCalendarList();

    }

    /**
     * Check if a calendar is busy between two dates
     * @param string $google_calendar_id
     * @param string $start_time
     * @param string $end_time
     * @return bool true if busy
     */
    public function is_busy( $google_calendar_id, $start_time, $end_time ){

        // By default, no
        $is_busy = false;

        $freebusy_req = new \Google_Service_Calendar_FreeBusyRequest();

        $freebusy_req->setTimeMin( $start_time.$this->timezone_offset );

        $freebusy_req->setTimeMax( $end_time.$this->timezone_offset );

        $freebusy_req->setTimeZone('America/Bogota');

        $item = new \Google_Service_Calendar_FreeBusyRequestItem();

        $item->setId($google_calendar_id);

        $freebusy_req->setItems(array($item));

        $query = $this->service->freebusy->query($freebusy_req);

        foreach ( $query->getCalendars() as $calendar_id => $calendar ){

            $calendar = $this->freebusy_calendar( $calendar);

            $is_busy |= count($calendar->getBusy()) > 0;
        }

        return $is_busy;

    }

    /**
     * Book an event
     * @param $calendar_id
     * @param \Google_Service_Calendar_Event $event
     */
    public function book( $calendar_id, \Google_Service_Calendar_Event $event ){

        $result = $this->service->events->insert($calendar_id,$event);

        return $result;

    }

    /**
     * @param \Google_Service_Calendar_FreeBusyCalendar $calendar
     * @return \Google_Service_Calendar_FreeBusyCalendar
     */
    private function freebusy_calendar( \Google_Service_Calendar_FreeBusyCalendar $calendar ) {


        return $calendar;
    }


    /**
     * Returns an authorized API client.
     * @return \Google_Client the authorized client object
     */
    function getClient( $token ) {

        $client = new \Google_Client();

        $client->setApplicationName( $this->application_name );

        $client->setScopes( $this->scopes );

        $client->setAuthConfigFile( $this->client_secret_path );

        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory( $this->credentials_path );

        if (file_exists($credentialsPath)) {

            $accessToken = file_get_contents($credentialsPath);

        }  else {


            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);

            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate($token);

            // Store the credentials to disk.
            if(!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, $accessToken);
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, $client->getAccessToken());
        }
        return $client;
    }

    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    function expandHomeDirectory($path) {

        $homeDirectory = getenv('HOME');

        if (empty($homeDirectory)) {

            $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");

        }

        return str_replace('~', realpath($homeDirectory), $path);

    }

    /**
     * @param $calendarId
     * @param $eventId
     * @return bool true if success
     */
    public function cancel_event( $calendarId, $eventId ){

        try {

            $this->service->events->delete( $calendarId, $eventId );

        } catch ( \Google_Service_Exception $gse ){

            $this->error = $gse->getMessage();

            return false;

        }


        return true;

    }

    /**
     * @return string error
     */
    public function error(){ return $this->error; }

}