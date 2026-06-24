<?php

return [
    '11' => [
        'name' => 'JupiterMeet AI Meeting Assistant Addon',
        'foldername' => 'AIMeetingAssistant',
        'description' => 'AI-powered meeting transcription, summaries, live captions, and real-time translation.',
        'buy_url' => 'https://codecanyon.net/item/jupitermeet-ai-meeting-assistant-addon/62482848?s_rank=1',
        "image" => "ai-meeting-thumbnail.png",
        'settings' => [
            'TRANSCRIPTION_PROVIDER' => [
                'label' => 'Transcription Provider',
                'type' => 'select',
                'options' => [
                    'openai' => 'OpenAI',
                ],
                'required' => true,
                'description' => 'Select the transcription service provider you want to use for generating meeting transcripts.',
            ],
            'TRANSCRIPTION_KEY' => [
                'label' => 'Transcription API Key',
                'type' => 'password',
                'placeholder' => 'Enter transcription API key',
                'minlength' => 10,
                'maxlength' => 255,
                'required' => true,
                'description' => 'Get your API key from your transcription service provider and enter it here.',
            ],
            'SUMMARY_KEY' => [
                'label' => 'Summary API Key',
                'type' => 'password',
                'placeholder' => 'Enter summary API key',
                'description' => 'Get your API key from your summary service provider and enter it here.',
                'minlength' => 10,
                'maxlength' => 255,
                'required' => true,
            ],
            'SUMMARY_STATUS' => [
                'label' => 'Summary Status',
                'type' => 'select',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
                'required' => true,
                'description' => 'Enable or disable the summary feature for meetings.',
            ],
        ],
    ],
];
