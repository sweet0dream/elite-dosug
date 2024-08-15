<?php

class CacheHelper
{
    private int $expired;
    private bool $enable;
    private ?string $key = null;
    private ?array $value = null;

    public function __construct(
        int $expired = 3600
    )
    {
        $this->expired = $expired;
        $this->enable = apcu_enabled();
    }

    public function getExpired(): int
    {
        return $this->expired;
    }

    public function setData(
        string $key,
        array $value
    ): array|false
    {
        $this->setKey($key);

        if ($this->isCached()) {
            return $this->getData($key);
        }

        $this->setValue($value);

        return apcu_store(
            $this->key,
            $this->value,
            $this->expired
        ) ? $this->getData($key) : false;
    }

    public function getData(
        ?string $key
    ): array|false
    {
        return apcu_fetch($key);
    }

    public function dropData(string $key): void
    {
        apcu_delete($key);
    }

    public function dropCacheCity(string $domain): void
    {
        foreach (array_map(fn($i) => $i['info'], apcu_cache_info()['cache_list']) as $key) {
            if (explode('-', $key)[0] == $domain) {
                $this->dropData($key);
            }
        }
    }

    private function isCached(): bool
    {
        return apcu_exists($this->key);
    }

    private function getKey(): ?string
    {
        return $this->key;
    }

    private function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }

    private function getValue(): ?array
    {
        return $this->value;
    }

    private function setValue(?array $value): self
    {
        $this->value = $value;

        return $this;
    }
}