<?php

class Technicien extends User
{
    protected ?int $niveau_support = null;

    protected function hydrate(array $data): static
    {
        parent::hydrate($data);
        $this->niveau_support = isset($data['niveau_support']) ? (int) $data['niveau_support'] : null;
        return $this;
    }

    public function getNiveauSupport(): ?int
    {
        return $this->niveau_support;
    }

    public function getLibelleRole(): string
    {
        return 'Technicien N' . ($this->niveau_support ?? 1);
    }
}
