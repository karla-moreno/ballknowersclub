<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';
  global $season;
  global $date;

  use App\Services\DraftConfigService;
  use App\Auth\Auth;

  $DraftConfigService = new DraftConfigService();

  $drafters = $DraftConfigService->getDrafters($season->value);
  try {
    $draft_order = $DraftConfigService->getDraftOrder($season->value);
  } catch (Exception $e) {
    $draft_order = [];
  }
?>
  <style>
      .lottery-flip-card {
          perspective: 800px;
          height: 60px;
      }

      .lottery-flip-inner {
          position: relative;
          width: 100%;
          height: 100%;
          transition: transform 0.6s ease;
          transform-style: preserve-3d;
      }

      .lottery-flip-card.revealed .lottery-flip-inner {
          transform: rotateY(180deg);
      }

      .lottery-flip-front,
      .lottery-flip-back {
          position: absolute;
          width: 100%;
          height: 100%;
          backface-visibility: hidden;
          display: flex;
          align-items: center;
          justify-content: center;
          margin: 0;
          overflow: hidden;
          box-sizing: border-box;
      }

      .lottery-flip-front svg {
          width: 30px;
          height: 30px;
      }

      .lottery-flip-back {
          transform: rotateY(180deg);
          background: linear-gradient(135deg, #1a1a2e, #16213e);
          border: 1px solid orange;
      }

      .lottery-name {
          font-size: 1.1rem;
          font-weight: 700;
          color: orange;
          text-transform: uppercase;
          letter-spacing: 0.05em;
      }

      .lottery-flip-card.revealed .lottery-flip-inner {
          animation: lottery-pop 0.6s ease;
      }

      .animate-spin {
          animation: spin 1.5s linear infinite;
      }

      @keyframes lottery-pop {
          0% {
              transform: rotateY(0deg) scale(1);
          }
          50% {
              transform: rotateY(90deg) scale(1.08);
          }
          100% {
              transform: rotateY(180deg) scale(1);
          }
      }

      @keyframes spin {
          0% {
              transform: rotate(0deg);
          }
          100% {
              rotate: 360deg;
          }
      }
  </style>
  <div class="col-12 hstack">
    <button
      type="submit"
      id="draft-order-reveal"
      <?= count($draft_order) == 0 ? 'disabled' : ''; ?>
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="20"
           width="20" class="animate-spin" style="display:none;">
        <!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
        <path fill="rgb(248, 129, 88)"
              d="M92.7 58.8l78.4 78.4c18.2-25 28.9-55.9 28.9-89.2 0-14.1-1.9-27.7-5.5-40.6-38 9.4-72.6 27.2-101.8 51.4zM58.8 92.7c-24.2 29.2-42 63.8-51.4 101.8 12.9 3.6 26.5 5.5 40.6 5.5 33.3 0 64.1-10.7 89.2-28.9L58.8 92.7zM256 0c-4.6 0-9.2 .1-13.7 .4 3.7 15.3 5.7 31.2 5.7 47.6 0 46.6-15.9 89.4-42.6 123.4L256 222.1 419.3 58.8C374.9 22.1 318.1 0 256 0zM48 248c-16.4 0-32.4-2-47.6-5.7-.2 4.5-.4 9.1-.4 13.7 0 62.1 22.1 118.9 58.8 163.3L222.1 256 171.4 205.4C137.4 232.1 94.6 248 48 248zm463.6 21.7c.2-4.5 .4-9.1 .4-13.7 0-62.1-22.1-118.9-58.8-163.3L289.9 256 340.6 306.6c34-26.7 76.9-42.6 123.4-42.6 16.4 0 32.4 2 47.6 5.7zm-7.1 47.8c-12.9-3.6-26.5-5.5-40.6-5.5-33.3 0-64.1 10.7-89.2 28.9l78.4 78.4c24.2-29.2 42-63.8 51.4-101.8zM340.9 374.8c-18.2 25-28.9 55.9-28.9 89.2 0 14.1 1.9 27.7 5.5 40.6 38-9.4 72.6-27.2 101.8-51.4l-78.4-78.4zm-34.3-34.3L256 289.9 92.7 453.2c44.3 36.7 101.2 58.8 163.3 58.8 4.6 0 9.2-.1 13.7-.4-3.7-15.3-5.7-31.2-5.7-47.6 0-46.6 15.9-89.4 42.6-123.4z"/>
      </svg>
      Reveal draft order
    </button>
    <?php if (Auth::role() === 'admin'): ?>
      <button type="submit"
              id="draft-order-gen" <?= count($draft_order) > 0 ? 'disabled' : ''; ?>>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
             height="20"
             width="20" class="animate-spin" style="display:none;">
          <!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
          <path fill="rgb(248, 129, 88)"
                d="M92.7 58.8l78.4 78.4c18.2-25 28.9-55.9 28.9-89.2 0-14.1-1.9-27.7-5.5-40.6-38 9.4-72.6 27.2-101.8 51.4zM58.8 92.7c-24.2 29.2-42 63.8-51.4 101.8 12.9 3.6 26.5 5.5 40.6 5.5 33.3 0 64.1-10.7 89.2-28.9L58.8 92.7zM256 0c-4.6 0-9.2 .1-13.7 .4 3.7 15.3 5.7 31.2 5.7 47.6 0 46.6-15.9 89.4-42.6 123.4L256 222.1 419.3 58.8C374.9 22.1 318.1 0 256 0zM48 248c-16.4 0-32.4-2-47.6-5.7-.2 4.5-.4 9.1-.4 13.7 0 62.1 22.1 118.9 58.8 163.3L222.1 256 171.4 205.4C137.4 232.1 94.6 248 48 248zm463.6 21.7c.2-4.5 .4-9.1 .4-13.7 0-62.1-22.1-118.9-58.8-163.3L289.9 256 340.6 306.6c34-26.7 76.9-42.6 123.4-42.6 16.4 0 32.4 2 47.6 5.7zm-7.1 47.8c-12.9-3.6-26.5-5.5-40.6-5.5-33.3 0-64.1 10.7-89.2 28.9l78.4 78.4c24.2-29.2 42-63.8 51.4-101.8zM340.9 374.8c-18.2 25-28.9 55.9-28.9 89.2 0 14.1 1.9 27.7 5.5 40.6 38-9.4 72.6-27.2 101.8-51.4l-78.4-78.4zm-34.3-34.3L256 289.9 92.7 453.2c44.3 36.7 101.2 58.8 163.3 58.8 4.6 0 9.2-.1 13.7-.4-3.7-15.3-5.7-31.2-5.7-47.6 0-46.6 15.9-89.4 42.6-123.4z"/>
        </svg>
        Generate draft order
      </button>
    <?php endif; ?>
  </div>
  <div id="draft-order-grid" class="col-12"
       style="display: grid;
       grid-template-columns: 1fr;
       grid-gap: 1em;
       margin-top: 1em;
       grid-template-rows: auto;">
    <?php foreach ($drafters as $i => $drafter): ?>
      <div class="flex" style="gap: 1rem;">
        <div class="flex items-center justify-center">
        <span style="font-size: 25px; font-weight: 700;"><?= $i + 1;
          ?></span>
        </div>
        <div class="lottery-flip-card" id="lottery-card-<?= $i + 1; ?>"
             style="width: 100%;">
          <div class="lottery-flip-inner">
            <div class="lottery-flip-front card">
              <div style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                  <path fill="rgb(248, 129, 88)"
                        d="M92.7 58.8l78.4 78.4c18.2-25 28.9-55.9 28.9-89.2 0-14.1-1.9-27.7-5.5-40.6-38 9.4-72.6 27.2-101.8 51.4zM58.8 92.7c-24.2 29.2-42 63.8-51.4 101.8 12.9 3.6 26.5 5.5 40.6 5.5 33.3 0 64.1-10.7 89.2-28.9L58.8 92.7zM256 0c-4.6 0-9.2 .1-13.7 .4 3.7 15.3 5.7 31.2 5.7 47.6 0 46.6-15.9 89.4-42.6 123.4L256 222.1 419.3 58.8C374.9 22.1 318.1 0 256 0zM48 248c-16.4 0-32.4-2-47.6-5.7-.2 4.5-.4 9.1-.4 13.7 0 62.1 22.1 118.9 58.8 163.3L222.1 256 171.4 205.4C137.4 232.1 94.6 248 48 248zm463.6 21.7c.2-4.5 .4-9.1 .4-13.7 0-62.1-22.1-118.9-58.8-163.3L289.9 256 340.6 306.6c34-26.7 76.9-42.6 123.4-42.6 16.4 0 32.4 2 47.6 5.7zm-7.1 47.8c-12.9-3.6-26.5-5.5-40.6-5.5-33.3 0-64.1 10.7-89.2 28.9l78.4 78.4c24.2-29.2 42-63.8 51.4-101.8zM340.9 374.8c-18.2 25-28.9 55.9-28.9 89.2 0 14.1 1.9 27.7 5.5 40.6 38-9.4 72.6-27.2 101.8-51.4l-78.4-78.4zm-34.3-34.3L256 289.9 92.7 453.2c44.3 36.7 101.2 58.8 163.3 58.8 4.6 0 9.2-.1 13.7-.4-3.7-15.3-5.7-31.2-5.7-47.6 0-46.6 15.9-89.4 42.6-123.4z"/>
                </svg>
              </div>
            </div>
            <div class="lottery-flip-back card">
              <span id="lottery-slot-<?= $i + 1; ?>"
                    class="lottery-name"></span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <p class="text-light" style="font-size: 12px; margin-bottom: 0; margin-top:
1em;">
    <i>Generated with
      <a href="https://www.random.org/integers/" rel="nofollow">https://www.random
        .org/integers/</a>
    </i>
  </p>
  <script>
    const draftOrder = <?= json_encode($draft_order); ?>;
    const revealKey = 'draft-revealed-<?= $season->value ?>';
    const revealBtn = document.getElementById('draft-order-reveal');
    revealBtn.addEventListener('click', function () {
      console.log(revealBtn);
      if (!draftOrder.length) return;

      this.disabled = true;
      this.querySelector('svg').style.display = 'block';

      for (let i = 1; i <= draftOrder.length; i++) {
        document.getElementById(`lottery-card-${i}`).classList.remove('revealed');
        document.getElementById(`lottery-slot-${i}`).textContent = '';
      }

      for (let i = 0; i < draftOrder.length; i++) {
        document.getElementById(`lottery-slot-${i + 1}`).textContent = draftOrder[i];
      }

      const revealDelay = 1500;
      for (let i = draftOrder.length; i >= 1; i--) {
        const delay = (draftOrder.length - i) * revealDelay;
        setTimeout(() => {
          document.getElementById(`lottery-card-${i}`).classList.add('revealed');
        }, delay);
      }

      setTimeout(() => {
        this.disabled = false;
        this.querySelector('svg').style.display = 'none';
        localStorage.setItem(revealKey, '1');
      }, draftOrder.length * revealDelay);
    });

    // if previously revealed, show cards immediately
    if (localStorage.getItem(revealKey) && draftOrder.length) {
      for (let i = 0; i < draftOrder.length; i++) {
        document.getElementById(`lottery-slot-${i + 1}`).textContent = draftOrder[i];
        document.getElementById(`lottery-card-${i + 1}`).classList.add('revealed');
      }
    }
  </script>
<?php if (Auth::role() === 'admin'): ?>
  <script id="gen-draft">
    const drafters = <?= json_encode($drafters); ?>;
    const draftOrder = <?= json_encode($draft_order); ?>;
    const generateDraftOrderBtn = document.getElementById('draft-order-gen');
    const generateDraftOrderBtnSvg = generateDraftOrderBtn.querySelector('svg');
    const revealDelay = 1500;

    async function getRandomIndex(max) {
      const url = `https://www.random.org/integers/?num=1&min=1&max=${max}&col=1&base=10&format=plain&rnd=new`;
      const response = await fetch(url);
      const text = await response.text();
      return parseInt(text.trim(), 10);
    }

    async function generateDraftOrder() {
      const max = drafters.length;
      const draftOrder = new Array(max).fill(null);
      const placed = new Set();

      for (let i = 1; i <= max; i++) {
        document.getElementById(`lottery-card-${i}`).classList.remove('revealed');
        document.getElementById(`lottery-slot-${i}`).textContent = '';
      }

      for (const drafter of drafters) {
        let index;
        do {
          index = await getRandomIndex(max);
        } while (draftOrder[index - 1] !== null);

        draftOrder[index - 1] = drafter;
        placed.add(drafter);
        console.log(`${drafter} → position ${index}`);

        document.getElementById(`lottery-slot-${index}`).textContent = drafter;
      }

      console.log('Final draft order:', draftOrder);
      try {
        const res = await fetch('/draft/save-draft-order.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(draftOrder)
        });

        const data = await res.json();

        if (data.success) {
          ot.toast(data.message, 'Success', {
              duration: 4000,
              variant: 'success',
              placement: 'top-center'
            }
          );
        } else {
          ot.toast(data.message, 'Error', {
              duration: 4000,
              variant: 'danger',
              placement: 'top-center'
            }
          );
          return false;
        }
      } catch (err) {
        console.error(err);
        ot.toast(`Failed to save draft order: ${err}`, 'Error', {
            duration: 4000,
            variant: 'error',
            placement: 'top-center'
          }
        );
        return false;
      }

      // reveal from last pick to first
      for (let i = max; i >= 1; i--) {
        const delay = (max - i) * revealDelay;
        setTimeout(() => {
          document.getElementById(`lottery-card-${i}`).classList.add('revealed');
        }, delay);
      }
      setTimeout(() => {
        localStorage.setItem(revealKey, '1');
      }, max * revealDelay);

      return true;
    }

    generateDraftOrderBtn.addEventListener('click', function () {
      this.disabled = true;
      this.querySelector('svg').style.display = 'block';

      generateDraftOrder().then(succeeded => {
        const wait = succeeded ? drafters.length * revealDelay : 0;
        setTimeout(() => {
          this.disabled = false;
          this.querySelector('svg').style.display = 'none';
        }, wait);
      });
    });
  </script>
<?php endif; ?>