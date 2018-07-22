<?php
namespace App\Rest\Response;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BaseResponse implements ContentInterface
{
    protected $data = [];

    protected $errors = [];

    protected $validationErrors = [];

    protected $status = HttpResponse::HTTP_OK;

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function appendData(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    final public function addError(int $code, string $message): self
    {
        $this->errors[$code] = $message;

        return $this;
    }

    final public function addValidationError(string $code, string $message): self
    {
        $this->validationErrors[$code][] = $message;

        return $this;
    }

    /**
     * @param string $code
     * @param string[] $messages
     *
     * @return BaseResponse
     */
    final public function setValidationErrors(string $code, array $messages): self
    {
        $this->validationErrors[$code] = $messages;

        return $this;
    }

    final public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    final public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function hasValidationErrors(): bool
    {
        return !empty($this->hasValidationErrors());
    }

    final public function getStatusCode(): int
    {
        return $this->status;
    }

    final public function setStatusCode(int $code): self
    {
        $this->status = $code;

        return $this;
    }

    final public function isValid(): bool
    {
        return !$this->hasErrors() && !$this->hasValidationErrors();
    }

    public function getAnswer()
    {
        return \response($this->getMap(), $this->getStatusCode());
    }

    public function getMap(): array
    {
        return [
            'status'            => $this->getStatusCode(),
            'errors'            => $this->getErrors(),
            'validation_errors' => $this->getValidationErrors(),
            'data'              => $this->data,
        ];
    }
}