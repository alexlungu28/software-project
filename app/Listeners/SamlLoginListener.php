<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Saml2User;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SamlLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Saml2LoginEvent  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        // $messageId = $event->getSaml2Auth()->getLastMessageId();
        // TODO: Add your own code preventing reuse of a $messageId to stop replay attacks

        $samlUser = $event->getSaml2User();

        Log::info('Syncing Saml user to local system', ['attributes' => $samlUser->getAttributes()]);

        $netid = $this->determineNetid($samlUser);
        $surname = $this->determineSurname($samlUser);
        $affiliation = $this->determineAffiliation($samlUser);

        // TODO what if no affiliation.
        // Attribute-listing: https://teams.connect.tudelft.nl/misc/sso/SitePages/Attributen.aspx
        $orgDefinedId = $netid;
        if ($affiliation === 'student') {
            $orgDefinedId = $this->determineStudentNumber($samlUser);
        }
        $laravelUser = User::updateOrCreate([
            'org_defined_id' => $orgDefinedId,
        ], [
            'net_id' => $netid,
            'last_name' => $surname,
            'first_name' => $samlUser->getAttribute('givenName')[0],
            'email' => $samlUser->getAttribute('mail')[0],
            'affiliation' => $affiliation
        ]);

        Log::info('Logging in local user', ['user' => $laravelUser->netid]);
        Auth::login($laravelUser);
    }

    /**
     * Determines the users' affiliation.
     *
     * @param Saml2User $samlUser
     * @return string
     */
    public function determineAffiliation(Saml2User $samlUser): string
    {
        $affiliations = $samlUser->getAttribute('urn:mace:dir:attribute-def:eduPersonAffiliation');

        if (is_null($affiliations)) {
            // Fallback for TUD login-test.
            $affiliations = $samlUser->getAttribute('eduPersonAffiliation');
        }

        if (is_null($affiliations) || !is_array($affiliations)) {
            Log::critical('Could not find affiliations in the SAML response.');
            return "undetermined";
        } elseif (sizeof($affiliations) > 1) {
            Log::warning('SAML Auth response contained multiple affiliations when creating a new user.');
        }
        return $affiliations[0];
    }

    /**
     * Determines the users' surname.
     *
     * @param Saml2User $samlUser
     * @return mixed|string
     */
    public function determineSurname(Saml2User $samlUser)
    {
        $surnamePrefix = $samlUser->getAttribute('tudPrefix');
        $surname = $samlUser->getAttribute('sn')[0];

        $surname = (!is_null($surnamePrefix) && sizeof($surnamePrefix) === 1)
            ? $surnamePrefix[0] . ' ' . $surname
            : $surname;
        return $surname;
    }

    /**
     * Determines the users' netid.
     *
     * @param Saml2User $samlUser
     * @return array|mixed|null
     */
    public function determineNetid(Saml2User $samlUser)
    {
        $netid = $samlUser->getAttribute('urn:mace:dir:attribute-def:uid');

        if (is_null($netid)) {
            // On login-test the UID is apparently passed as "uid".
            Log::warning('Falling back to the "uid" attribute.');
            $netid = $samlUser->getAttribute('uid');
        }

        if (is_null($netid)) {
            Log::critical('Could not find a NetID in the SAML response.');
        }

        if (sizeof($netid) !== 1) {
            Log::warning('SAML Auth response contained multiple UIDs');
        } else {
            $netid = $netid[0];
        }
        return $netid;
    }

    /**
     * Determines the users' student number.
     *
     * @param Saml2User $samlUser
     * @return array|mixed|null
     */
    public function determineStudentNumber(Saml2User $samlUser)
    {
        $studentNumber = $samlUser->getAttribute('urn:mace:dir:attribute-def:tudStudentNumber');

        if (is_null($studentNumber)) {
            Log::warning('Falling back to the "tudStudentNumber" attribute.');
            $studentNumber = $samlUser->getAttribute('tudStudentNumber');
        }

        if (is_null($studentNumber)) {
            Log::critical('Could not find a Student Number in the SAML response.');
        }

        if (sizeof($studentNumber) !== 1) {
            Log::warning('SAML Auth response contained multiple student numbers.');
        } else {
            $studentNumber = $studentNumber[0];
        }
        return $studentNumber;
    }
}
