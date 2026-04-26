<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';

  use App\Enums\Season;
  use function App\Helpers\getLogo;
  use function App\Helpers\skinSelect;

  $picks = (new App\Services\DraftService)->getDraftPicks(Season::S25_26->value);
?>

<section id="draft" class="section">
  <div class="row">
    <div class="col-12">
      <h2 style="margin-bottom: 0;">Draft recap</h2>
    </div>
    <article class="card col-12">
      <ol style="font-size: 18px;">
        <?php foreach ($picks as $pick): ?>
          <li>
            <code>
              <strong>
                <?= $pick['username']; ?>
              </strong>
            </code>
            selects
            <div class="items-center" style="display: inline;">
              <img style="margin-bottom: -4px"
                   src="<?= getLogo($pick['team_id']); ?>"
                   alt="<?= $pick['team_name']; ?> logo" height="20"/>
              <span><code><strong><?= $pick['team_name'];
                    ?></strong></code></span>
            </div>
            and their <code
              style="color: var(--<?= skinSelect($pick['skin_select']); ?>);">
              <strong>
                <?= $pick['skin_select']; ?>
              </strong>
            </code>
          </li>
        <?php endforeach; ?>
      </ol>
    </article>
  </div>
</section>
