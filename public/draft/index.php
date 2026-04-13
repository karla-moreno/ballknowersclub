<?php
	declare(strict_types=1);

	require_once __DIR__ . '/../../vendor/autoload.php';

	use App\Auth\Auth;
	use App\Services\DraftService;
	use App\Services\NbaApiService;
	use App\Enums\Season;

	DraftService::createDraftTable();

	$teams = NbaApiService::allTeams();
	$user = Auth::user();

	$title = 'Draft';
	ob_start();

	$num_picks = 5;
?>
<?php include __DIR__ . '/../../src/Views/partials/picks.php'; ?>
<?php if (Auth::check()): ?>
	<div class="row">
		<?php
			for ($x = 0; $x <= $num_picks; $x++) {
				$pick = $x + 1;
				$disabled = $x > 0 ? 'disabled' : '';
				?>
				<article class="card col-4">
					<header>
						<h3>Team <?= $pick; ?></h3>
					</header>
					<div data-field>
						<label>Team</label>
						<select
							aria-label="Select an option"
							id="team-pick-<?= $pick; ?>"
							<?= $disabled; ?>
						>
							<option value="">Select an option</option>
							<?php foreach ($teams as $team) { ?>}
								<option value="<?= $team['team_id']; ?>">
									<?= $team['name']; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<fieldset
						class="hstack"
						<?= $disabled; ?>
					>
						<legend>Selection</legend>
						<label>
							<input type="radio"
										 name="pick-<?= $pick; ?>"
										 value="wins">
							<span class="badge success">WINS</span>
						</label>
						<label>
							<input type="radio"
										 name="pick-<?= $pick; ?>"
										 value="losses">
							<span class="badge danger">LOSSES</span>
						</label>
					</fieldset>
					<footer class="hstack">
						<button commandfor="commit-dialog"
										command="show-commit-confirmation"
										id="commit-<?= $pick; ?>"
										data-pick="<?= $pick ?>"
							<?= $disabled; ?>
						>
							Commit
						</button>
					</footer>
				</article>
			<?php } ?>
	</div>
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

		document.querySelectorAll('[data-pick]').forEach(button => {
			button.addEventListener('click', () => {
				const pick = button.dataset.pick;
				const team = document.getElementById(`team-pick-${pick}`)?.selectedOptions[0]?.text;
				const teamId = document.getElementById(`team-pick-${pick}`)?.selectedOptions[0]?.value;
				const selection = document.querySelector(`input[name="pick-${pick}"]:checked`)?.value;

				dialogSubhead.textContent = `Team ${pick}`;
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
			});
		});
	</script>
	<script id="commit-logic">
		let currentPick = {};

		document.querySelectorAll('[data-pick]').forEach(button => {
			button.addEventListener('click', () => {
				const pick = button.dataset.pick;
				const teamSelect = document.getElementById(`team-pick-${pick}`);
				const teamName = teamSelect?.selectedOptions[0]?.text;
				const selection = document.querySelector(`input[name="pick-${pick}"]:checked`)?.value;

				currentPick = {
					number: pick,
					teamId: teamSelect?.value,
					teamName: teamName,
					selection,
					username: '<?= htmlspecialchars($user['username']); ?>',
					season: '<?= Season::S25_26->value; ?>'
				};
			});
		});

		document.getElementById('dialog-confirm').addEventListener('click', async () => {
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
					// TODO: enable next pick card, disable last pick
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
	</script>
<?php endif; ?>
<?php
	$content = ob_get_clean();
	require __DIR__ . '/../../src/Views/layouts/main.php';
