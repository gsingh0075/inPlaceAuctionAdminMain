<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MattDaneshvar\Survey\Models\Survey;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $survey = Survey::create(['name' => 'Equipment Inspection','settings' => ['limit-per-participant' => -1,'accept-guest-entries' => true]]);



        $one = $survey->sections()->create(['name' => 'Pre-Inspection']);

        $one->questions()->create([
            'content' => 'Was the provided telephone number correct?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $one->questions()->create([
            'content' => 'Was the inspection conducted at the provided address?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $one->questions()->create([
            'content' => 'Did the contact confoirm at the time of scheduling that the equipment (every time) was in use/running?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $two = $survey->sections()->create(['name' => 'Inspection Observation']);

        $two->questions()->create([
            'content' => 'Please provide location type',
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Did the business name viewed at the location match the provided business name?',
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Signage',
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Neighborhood description',
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Building type',
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Appearance',
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Number of employees viewed',
            'rules' => ['required','numeric']
        ]);

        $two->questions()->create([
            'content' => 'Did you see any signage posted at the site indicating the business has changed or is in transition?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $two->questions()->create([
            'content' => 'Is any of the equipment affixed to the property?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $three = $survey->sections()->create(['name' => 'Contact Interview']);

        $three->questions()->create([
            'content' => 'Primary contact at the inspection site ( Name/ Title)',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'Secondary contact at the inspection site ( Name/ Title)',
        ]);

        $three->questions()->create([
            'content' => 'Age of business (yrs/months)',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'How long at this location (yrs/months)',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'Does the business run under any other names?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'Additional locations?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'Total number of people employed by this business',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'When was the equipment delivered?',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'When was the equipment installed?',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'What is the age of equipment?',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'Is the contact satisfied with the equipment?',
            'rules' => ['required']
        ]);

        $three->questions()->create([
            'content' => 'Is the contact satisfied with the equipment?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $four = $survey->sections()->create(['name' => 'Affirmations']);

        $four->questions()->create([
            'content' => 'In your opinion, does this equipment and location make sense for this type of business?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $four->questions()->create([
            'content' => 'Does the company share space with other business?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $four->questions()->create([
            'content' => 'Did you see or hear anything that would make you think this business is not what it reports to be?',
            'type' => 'radio',
            'options' => ['Yes', 'No'],
            'rules' => ['required']
        ]);

        $five = $survey->sections()->create(['name' => 'Inspection Comments']);

        $five->questions()->create([
            'content' => 'Comments',
            'rules' => ['required']
        ]);


    }
}
