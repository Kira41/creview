<?php
function getCasinos(): array {
    return [
        [
            'name' => 'Sample Casino',
            'rating' => 4.5,
            'bonus' => '100% up to $500',
            'affiliate_url' => '#'
        ]
    ];
}

function renderCasinoCard(array $casino): string {
    $name = htmlspecialchars($casino['name']);
    $rating = htmlspecialchars((string)$casino['rating']);
    $bonus = htmlspecialchars($casino['bonus']);
    $link = htmlspecialchars($casino['affiliate_url']);

    return <<<HTML
<div class="casino-card">
  <h2>{$name}</h2>
  <p>Rating: {$rating}</p>
  <p>{$bonus}</p>
  <a href="{$link}" class="btn">Play Now</a>
</div>
HTML;
}
