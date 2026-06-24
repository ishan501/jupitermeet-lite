<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAIChatbotSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
           'AI_CHATBOT_API_KEY' => 'nullable|max:200',
            'AI_CHATBOT' => 'nullable|max:100',
            'AI_CHATBOT_MODEL' => 'nullable|max:100',
            'AI_CHATBOT_SECONDS' => 'nullable|string|max:3',
            'AI_CHATBOT_MESSAGE_LIMIT' => 'nullable|string|max:3',
            'AI_CHATBOT_MAX_CONVERSATION_LENGTH' => 'nullable|string|max:3',
        ];
    }

    public function attributes()
    {
        return [
            'AI_CHATBOT_API_KEY' => __('AI Chatbot API Key'),
            'AI_CHATBOT' => __('AI Chatbot'),
            'AI_CHATBOT_MODEL' => __('AI Chatbot Model'),
            'AI_CHATBOT_SECONDS' => __('AI Chatbot Seconds'),
            'AI_CHATBOT_MESSAGE_LIMIT' => __('AI Chatbot Message Limit'),
            'AI_CHATBOT_MAX_CONVERSATION_LENGTH' => __('AI Chatbot Maximum Conversation Length'),
        ];
    }
}