<?php
  declare(strict_types=1);

  require_once __DIR__ . '/../../vendor/autoload.php';

  use App\Auth\Auth;
  use App\Database\Database;
  use App\Services\DraftService;
  use App\Services\NbaApiService;
  use App\Enums\Season;

  DraftService::createDraftTable();

  $db = Database::connection();
  $teams = NbaApiService::allTeams();
  $user = Auth::user();

  $title = 'Draft';
  ob_start();

  $draft_order = ['test', 'deadmau5', 'zombiekilla'];
  $season = Season::S25_26;
  $DraftService = new DraftService();

  $picked_teams = $db->prepare("SELECT team_id FROM draft_picks WHERE season = ?");
  $picked_teams->execute([$season->value]);
  $picked_teams = $picked_teams->fetchAll(PDO::FETCH_COLUMN) ?? null;
  // https://www.php.net/manual/en/pdostatement.fetchall.php

  if (count($picked_teams) == 30) {
    $draft_complete = true;
  } else {
    $draft_complete = false;
  }

  $last_pick = $DraftService::getLastDrafter($season->value);

  if (!$last_pick) {
    $current_drafter = $draft_order[0];
  } else {
    $last_pick_username = $last_pick['username'];
    $last_drafter_index = array_search($last_pick_username, $draft_order);
    $current_drafter = $draft_order[(array_search($last_pick_username,
        $draft_order) + 1) % count($draft_order)] ?? $draft_order[0];
  }

  $current_user = $user['username'] ?? null;

  /* TODO:
  * - extract JS to TS, add vite?
  * - reset/clear dialog errors when it has been reopened
  */

?>
  <div class="row">
    <?php if ($draft_complete): ?>
      <div class="col-12" style="text-align: center;">
        <p style="font-weight: 800; font-size: 2em;">
          <?= $season->label(); ?> DRAFT COMPLETE
        </p>
      </div>
    <?php endif; ?>
    <div id="draft-ui" class="col-5">
      <div class="vstack" style="position: sticky; top: 1em;">
        <div class="card">
          <header>
            <h3>Draft order</h3>
          </header>
          <ol id="draft-order">
            <?php foreach ($draft_order as $index => $drafter) { ?>
              <li data-drafter="<?= $drafter; ?>">
                <?= $drafter; ?>
                <?= !$draft_complete && $drafter === $current_drafter ?
                  "<span> ←</span>" :
                  ""; ?>
              </li>
            <?php } ?>
          </ol>
        </div>
        <?php if (Auth::check() && !$draft_complete): ?>
          <div class="card" style="height: min-content; <?php if
          ($draft_complete): ?>opacity: 0.5;<?php endif; ?>">
            <header>
              <h3>Pick</h3>
            </header>
            <div data-field>
              <label>Team</label>
              <select
                aria-label="Select an option"
                id="pick-team"
                <?= $draft_complete || $current_user !== $current_drafter ? 'disabled' : '' ?>
              >
                <option value="">Select an option</option>
                <?php foreach ($teams as $team) { ?>
                  <option
                    value="<?= $team['team_id']; ?>"
                    <?= in_array($team['team_id'], $picked_teams) ?
                      'disabled'
                      : '' ?>>
                    <?= $team['name']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <fieldset class="hstack">
              <legend>Selection</legend>
              <label>
                <input type="radio"
                       name="selection"
                       id="selection-wins"
                  <?= $draft_complete || $current_user !== $current_drafter ? 'disabled' : '' ?>
                       value="wins">
                <span class="badge success">WINS</span>
              </label>
              <label>
                <input type="radio"
                       name="selection"
                       id="selection-losses"
                  <?= $draft_complete || $current_user !== $current_drafter ? 'disabled' : '' ?>
                       value="losses">
                <span class="badge danger">LOSSES</span>
              </label>
            </fieldset>
            <footer class="pick-footer hstack">
              <button
                id="commit-button"
                data-pick=""
                <?= $draft_complete || $current_user !== $current_drafter ? 'disabled' : '' ?>
              >
                Commit
              </button>
              <?php if (!$draft_complete): ?>
                <p id="waiting-for" style="opacity: 0.6;">
                  Waiting for
                  <?= $current_drafter === $current_user ? 'you' : $current_drafter; ?>
                  ...
                </p>
              <?php endif; ?>
            </footer>
            <p id="pick-error" style="color: var(--danger); display: none;
            margin: 0; margin-top: 1em;"></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-7">
      <?php include __DIR__ . '/../../src/Views/partials/picks.php'; ?>
    </div>
  </div>
<?php if (Auth::check()): ?>
  <dialog id="commit-dialog" closedby="any">
    <form method="dialog">
      <header>
        <h3>Confirm Pick</h3>
        <p id="dialog-subheading"></p>
      </header>
      <div class="hstack items-center" style="justify-content: center;
      transform: scale(1.2);">
        <img id="dialog-logo" height="50"/>
        <p id="dialog-team" style="font-size: 18px;"></p>
        <span id="dialog-pick" class="badge"></span>
      </div>
      <footer>
        <button type="button" commandfor="commit-dialog" command="close"
                class="outline">Cancel
        </button>
        <button value="confirm" id="dialog-confirm">Confirm</button>
      </footer>
    </form>
  </dialog>
  <script id="dialog-updates">
    const dialog = document.getElementById('commit-dialog');
    const dialogSubhead = document.getElementById('dialog-subheading');
    const dialogLogo = document.getElementById('dialog-logo');
    const dialogTeam = document.getElementById('dialog-team');
    const dialogPick = document.getElementById('dialog-pick');

    const commitBtn = document.getElementById('commit-button');

    commitBtn?.addEventListener('click', (e) => {
      const team = document.getElementById(`pick-team`)?.selectedOptions[0]?.text;
      const teamId = document.getElementById(`pick-team`)?.selectedOptions[0]?.value;
      const selection = document.querySelector
      (`input[name="selection"]:checked`)?.value;
      const pickError = document.getElementById('pick-error');

      if (!teamId || !selection) {
        e.preventDefault();
        pickError.textContent = !teamId
          ? 'Please select a team.'
          : 'Please select Wins or Losses.';
        pickError.style.display = 'block';
        return;
      }
      pickError.style.display = 'none';

      dialogTeam.textContent = team;
      dialogPick.textContent = selection;
      dialogLogo.setAttribute('src', `https://cdn.nba.com/logos/nba/${teamId}/primary/L/logo.svg`);
      if (selection === 'wins') {
        dialogPick.classList.remove('danger');
        dialogPick.classList.add('success');
      } else {
        dialogPick.classList.remove('success');
        dialogPick.classList.add('danger');
      }
      dialog.showModal();
    });
  </script>
  <script id="commit-logic">
    const queue = <?= json_encode($draft_order); ?>;
    const currentUser = '<?= htmlspecialchars($user['username'] ?? '') ?>';
    const currentUserQueuePosition = queue.indexOf(currentUser);
    const currentDraftingUser = queue[0];
    const teamSelectInput = document.getElementById(`pick-team`);
    const teamSelectRadios = document.querySelectorAll(`input[name="selection"]`);

    const commitButton = document.getElementById('commit-button');
    const dialogConfirmButton = document.getElementById('dialog-confirm');

    let currentPick = {};
    let lastQueuePickId = <?= $latestPickId ?? 'null'; ?>;

    // TODO: get this out of auth check
    setInterval(async () => {
      try {
        const res = await fetch('/draft/latest-pick.php');
        const pick = await res.json();

        if (pick.id && pick.id !== lastQueuePickId) {
          lastQueuePickId = pick.id;
          disablePickedTeam(pick.team_id.toString());
          if (!thereArePicksRemaining()) {
            setDraftComplete();
          }
          setNextUserInQueue(pick.username);
        }
      } catch (err) {
        console.error('Polling error:', err);
      }
    }, 5000);

    function disablePickedTeam(teamId) {
      const option = document.querySelector(`#pick-team
      option[value="${teamId}"]`);
      if (option) option.disabled = true;
    }

    function setNextUserInQueue(username) {
      let nextUser;
      if (!thereArePicksRemaining()) {
        activateWaitingFor(null);
        return;
      }
      let prevUserIndex = queue.indexOf(username);
      if (prevUserIndex === queue.length - 1) {
        nextUser = queue[0];
      } else {
        nextUser = queue[prevUserIndex + 1];
      }
      setDraftOrderIndicator(nextUser);
      disableNondraftingUsers(nextUser);
      activateWaitingFor(nextUser);
    }

    function disableNondraftingUsers(username) {
      if (currentUser !== username) {
        teamSelectInput.disabled = true;
        teamSelectInput.value = '';
        teamSelectRadios.forEach(r => {
          r.checked = false;
          r.disabled = true;
        })
        commitButton.disabled = true;
      } else {
        teamSelectInput.disabled = false;
        teamSelectRadios.forEach(r => r.disabled = false)
        commitButton.disabled = false;
      }
    }

    function activateWaitingFor(username) {
      const waitingForElement = document.getElementById('waiting-for');
      if (username === null) {
        waitingForElement.style.display = 'none';
        return;
      }
      if (username === currentUser) {
        waitingForElement.style.display = 'none';
      } else {
        waitingForElement.style.display = 'block';
        waitingForElement.textContent = `Waiting for ${username}...`;
      }
    }

    function setDraftOrderIndicator(username) {
      const drafterElements = document.querySelectorAll('#draft-order li');
      drafterElements.forEach(el => {
        if (el.dataset.drafter === username && !(el.querySelector('span'))) {
          const span = document.createElement('span');
          span.textContent = ' ←';
          el.appendChild(span);
        }
        if (el.dataset.drafter !== username) {
          el.querySelector('span')?.remove();
        }
      })
    }

    function thereArePicksRemaining() {
      const options = document.querySelectorAll('#pick-team option:not([value=""])');
      return Array.from(options).some(option => !option.disabled);
    }

    // TODO: remove waiting for when is current users turn or draft complete
    commitButton?.addEventListener('click', (e) => {
      const teamSelect = document.getElementById(`pick-team`);
      const teamName = teamSelect?.selectedOptions[0]?.text;
      const selection = document.querySelector(`input[name="selection"]:checked`)?.value;

      currentPick = {
        teamId: teamSelect?.value,
        teamName: teamName,
        selection,
        username: '<?= htmlspecialchars($user['username']); ?>',
        season: '<?= Season::S25_26->value; ?>'
      };
    });

    dialogConfirmButton?.addEventListener('click', async () => {
      const confirmBtn = document.getElementById('dialog-confirm');

      confirmBtn.disabled = true;
      confirmBtn.textContent = 'Submitting...';

      try {
        const res = await fetch('/draft/commit.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(currentPick)
        });

        const data = await res.json();

        if (data.success) {
          dialog.close();
          ot.toast(data.message, 'Success', {
              duration: 4000,
              variant: 'success',
              placement: 'top-center'
            }
          );
        } else {
          showDialogError(data.message ?? 'Something went wrong.');
        }
      } catch (err) {
        console.error(err);
        showDialogError('Request failed. Please try again.');
      } finally {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Confirm';
      }
    });

    function showDialogError(message) {
      let err = document.getElementById('dialog-error');
      if (!err) {
        err = document.createElement('p');
        err.id = 'dialog-error';
        err.style.color = 'red';
        document.querySelector('#commit-dialog form').appendChild(err);
      }
      err.textContent = message;
    }

    function setDraftComplete() {
      teamSelectInput.disabled = true;
      teamSelectRadios.forEach(r => r.disabled = true)
      commitButton.disabled = true;
      ot.toast('All teams have been chosen.', 'DRAFT COMPLETE!', {
        duration: 5000,
        variant: 'success',
        placement: 'top-center'
      });
    }
  </script>
<?php endif; ?>
<?php
  $content = ob_get_clean();
  require __DIR__ . '/../../src/Views/layouts/main.php';
