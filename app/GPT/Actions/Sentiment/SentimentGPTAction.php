<?php

namespace App\GPT\Actions\Sentiment;

use MalteKuhr\LaravelGPT\GPTAction;
use Closure;

class SentimentGPTAction extends GPTAction
{
    /**
     * The message which explains the assistant what to do and which rules to follow.
     *
     * @return string|null
     */
    public function systemMessage(): ?string
    {
        return null;
    }

    /**
     * Specifies the function to be invoked by the model. The function is implemented as a
     * Closure which may take parameters that are provided by the model. If extra arguments
     * are included in the documentation to optimize model's performance (by allowing it more
     * thinking time), these can be disregarded by not including them within the Closure
     * parameters.
     *
     * @return Closure
     */
    public function function(): Closure
    {
        return function (): mixed {
            // TODO: Implement Closure.
        };
    }

    /**
     * Defines the rules for input validation and JSON schema generation. Override this
     * method to provide custom validation rules for the function. The documentation will
     * have the same order as the rules are defined in this method.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}