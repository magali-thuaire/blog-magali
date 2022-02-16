<?php

namespace Core\Trait;

trait HydrateTrait
{
    public function hydrate(array $data = []): static
    {
        foreach ($data as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set' . ucfirst($key);

            // Si le setter correspondant existe.
            if (method_exists($this, $method)) {
                // On appelle le setter.
                $this->$method($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }
}
