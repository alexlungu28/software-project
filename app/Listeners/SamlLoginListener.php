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
        //ddd($samlUser);

        Log::info('Syncing Saml user to local system', ['attributes' => $samlUser->getAttributes()]);

        $netid = $this->determineNetid($samlUser);
        $surname = $this->determineSurname($samlUser);
        $affiliation = $this->determineAffiliation($samlUser);
        //ddd($affiliation);
        // TODO what if no affiliation.
        // Attribute-listing: https://teams.connect.tudelft.nl/misc/sso/SitePages/Attributen.aspx
        $laravelUser = User::updateOrCreate([
            'org_defined_id' => $netid,
        ], [
            'net_id' => $netid,
            'last_name' => $surname,
            'first_name' => $samlUser->getAttribute('givenName')[0],
            'email' => $samlUser->getAttribute('mail')[0],
            'affiliation' => $affiliation
        ]);

//        // If this is the first time logging in, make sure to mark the email address as verified.
//        if (is_null($laravelUser->email_verified_at)) {
//            $laravelUser->email_verified_at = now();
//            $laravelUser->save();
//        }

//        // Fill additional fields, based on affiliation.
//        if ($laravelUser->affiliation->is(SamlAffiliationType::STUDENT())) {
//            $this->determineStudentDetails($laravelUser, $samlUser);
//        } elseif ($laravelUser->affiliation->is(SamlAffiliationType::EMPLOYEE())) {
//            $this->determineEmployeeDetails($laravelUser, $samlUser);
//        }

        Log::info('Logging in local user', ['user' => $laravelUser->netid]);
        Auth::login($laravelUser);
    }

    /**
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
        }// Default to undetermined.
        return $affiliations[0];
//        $affiliation = "undetermined";
//
//        foreach ($affiliations as $samlAffiliation) {
////            if ($samlAffiliation === 'employee') {
////                $affiliation = SamlAffiliationType::EMPLOYEE();
////            } elseif ($samlAffiliation === 'student') {
////                $affiliation = SamlAffiliationType::STUDENT();
////            }
//            $affiliation = $samlAffiliation;
//        }
//
//        if ($affiliation === 'undetermined') {
//            Log::warning('User with affiliation UNDETERMINED tried logging in.', $affiliations);
//        }
//
//        return $affiliation;
    }

    /**
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

//    /**
//     * Determine details specific to the STUDENT affiliation type and enrich the User model with those.
//     *
//     * @param User $laravelUser
//     * @param Saml2User $samlUser
//     */
//    private function determineStudentDetails(User $laravelUser, Saml2User $samlUser)
//    {
//        $samlStudyProgrammes = $samlUser->getAttribute('nlEduPersonStudyBranch');
//        if (is_null($samlStudyProgrammes) || !is_array($samlStudyProgrammes)) {
//            Log::critical('Could not find studyprogrammes in the SAML response.');
//        } else {
//            // Sync study programmes.
//            $studyProgrammes = [];
//            foreach ($samlStudyProgrammes as $programme) {
//                $studyProgramme = StudyProgramme::firstOrCreate([
//                    'name' => $programme,
//                ]);
//                $studyProgrammes[] = $studyProgramme->id;
//            }
//
//            Log::info('Syncing study programmes.', [$studyProgrammes]);
//
//            $laravelUser->studyProgrammes()->sync($studyProgrammes);
//        } // Sync the student number.
//        $studentNumbers = $samlUser->getAttribute('tudStudentNumber');
//
//        if (is_null($studentNumbers)) {
//            Log::critical('Could not find student number in the SAML response.');
//        } elseif (sizeof($studentNumbers) !== 1) {
//            Log::error(
//                'SAML response did not contain 1 student number, found ' . sizeof($studentNumbers) . ' instead',
//                ['studentNumbers' => $studentNumbers]
//            );
//        } else {
//            $laravelUser->update(['student_number' => $studentNumbers[0]]);
//        }
//    }
//
//    /**
//     * Determine details specific to the EMPLOYEE affiliation type and enrich the User model with those.
//     *
//     * @param User $laravelUser
//     * @param Saml2User $samlUser
//     */
//    private function determineEmployeeDetails(User $laravelUser, Saml2User $samlUser)
//    {
//        // Was formerly "nlEduOrganizationalUnit"
//        // Switching from multivalue "nlEduPersonOrgUnit" to single value "tudOrgDivision"
//        $samlTudOrgDivision = $samlUser->getAttribute('tudOrgDivision');
//
//        if (is_null($samlTudOrgDivision) || !is_array($samlTudOrgDivision)) {
//            Log::critical('Could not find department in the SAML response.');
//        } elseif (sizeof($samlTudOrgDivision) !== 1) {
//            Log::critical('Found unexpected number of values for "tudOrgDivision"!', $samlTudOrgDivision);
//        } else {
//            $orgDivisionCode = $samlTudOrgDivision[0];
//            Log::info('Creating/syncing section: ', [$orgDivisionCode]);
//
//            $section = Section::firstOrCreate([
//                'code' => $orgDivisionCode,
//            ], [
//                'code' => $orgDivisionCode,
//                'department' => '',
//                'section' => $orgDivisionCode,
//                'contact' => $laravelUser->email,
//            ]);
//
//            if (! $laravelUser->sections->contains($section->id)) {
//                Log::info(
//                    'Add user to section (default role)',
//                    ['user' => $laravelUser->id, 'section' => $section->id]
//                );
//                $laravelUser->sections()->syncWithoutDetaching([$section->id => ['role' => SectionRole::DEFAULT()]]);
//            }
//        }
//    }
}
