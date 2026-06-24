@extends('admin.setting.index')

@section('setting-content')
    <form class="col-12 col-md-9 d-flex flex-column" action="{{ route('admin.setting.update-aichatbot') }}" method="post">
        @csrf
        <div class="card-body position-relative">
            <h2>{{ __(key: 'AI Chatbot') }}</h2>
            <small class="text-muted">
                {{ __('Please restart the signaling server after making any changes to this section.') }}
            </small>

            @include('include.admin.premium-overlay', [
                'message' => __('Unlock AI Chatbot settings, model options, and message limits by upgrading to the pro version.'),
            ])

            <div class="blur-section">
                <div class="row mb-3 mt-4">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('AI Chatbot') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('Choose the AI chatbot that best suits your needs.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <select name="AI_CHATBOT" class="form-select" placeholder="{{ __('Select Option') }}">
                                <option value="" selected>{{ __('Select Option') }}</option>
                                @foreach (config('ai-chatbots') as $chatbot)
                                    <option value="{{ $chatbot }}" @selected(getSetting('AI_CHATBOT') == $chatbot)>
                                        {{ __($chatbot) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('AI_CHATBOT')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('AI Chatbot API Key') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('You can find the steps to generate an API key in the documentation.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" name="AI_CHATBOT_API_KEY" maxlength="200"
                                    class="form-control @error('AI_CHATBOT_API_KEY') is-invalid @enderror"
                                    value="{{ old('AI_CHATBOT_API_KEY') ?? getSetting('AI_CHATBOT_API_KEY') }}"
                                    placeholder="{{ __('AI Chatbot API Key') }}">
                                @error('AI_CHATBOT_API_KEY')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <span class="input-group-text">
                                    <a class="link-secondary jm-toggle-password" data-bs-toggle="tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('AI Chatbot Model') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('Refer to the documentation to select the model that best suits your needs.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <input type="text" name="AI_CHATBOT_MODEL" maxlength="100"
                                class="form-control @error('AI_CHATBOT_MODEL') is-invalid @enderror"
                                value="{{ old('AI_CHATBOT_MODEL') ?? getSetting('AI_CHATBOT_MODEL') }}"
                                placeholder="{{ __('AI Chatbot Model') }}">
                            @error('AI_CHATBOT_MODEL')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('AI Chatbot Seconds') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('The number of seconds a user must wait before sending another message.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <input type="number" name="AI_CHATBOT_SECONDS" maxlength="3"
                                class="form-control @error('AI_CHATBOT_SECONDS') is-invalid @enderror"
                                value="{{ old('AI_CHATBOT_SECONDS') ?? getSetting('AI_CHATBOT_SECONDS') }}"
                                placeholder="{{ __('AI Chatbot Seconds') }}">
                            @error('AI_CHATBOT_SECONDS')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('AI Chatbot Message Limit') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('The maximum number of messages a user can send during a meeting.') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <input type="number" name="AI_CHATBOT_MESSAGE_LIMIT" maxlength="3"
                                class="form-control @error('AI_CHATBOT_MESSAGE_LIMIT') is-invalid @enderror"
                                value="{{ old('AI_CHATBOT_MESSAGE_LIMIT') ?? getSetting('AI_CHATBOT_MESSAGE_LIMIT') }}"
                                placeholder="{{ __('AI Chatbot Message Limit') }}">
                            @error('AI_CHATBOT_MESSAGE_LIMIT')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('AI Chatbot Maximum Conversation Length') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="info-icon" viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="{{ __('The maximum number of recent messages sent to AI Chatbot') }}">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                </svg>
                            </label>
                            <input type="number" name="AI_CHATBOT_MAX_CONVERSATION_LENGTH" maxlength="3"
                                class="form-control @error('AI_CHATBOT_MAX_CONVERSATION_LENGTH') is-invalid @enderror"
                                value="{{ old('AI_CHATBOT_MAX_CONVERSATION_LENGTH') ?? getSetting('AI_CHATBOT_MAX_CONVERSATION_LENGTH') }}"
                                placeholder="{{ __('AI Chatbot Maximum Conversation Length') }}">
                            @error('AI_CHATBOT_MAX_CONVERSATION_LENGTH')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <div class="btn-list justify-content-end">
                <button type="submit" name="submit" class="btn btn-primary mt-3" disabled>{{ __('Save') }}</button>
            </div>
        </div>
    </form>
@endsection
