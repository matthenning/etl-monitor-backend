<?php

namespace App\EtlMonitor\Common\Models\Interfaces;

interface ElasticsearchModelInterface
{

    public function delete();

    public function enrich(): self;

    public function transform(): array;

}
