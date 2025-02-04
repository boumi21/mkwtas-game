<div class="modal fade" id="winModal" tabindex="-1" aria-labelledby="winModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h1 class="modal-title fs-5" id="winModalLabel">Congratulations</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>You won! You guessed the TASer of the day in <span x-text="guessedPlayers.length" class="fw-bold"></span> <span x-text="guessedPlayers.length > 1 ? 'tries' : 'try'"></span>.</p>
        <p>Come back tomorrow for a new challenge.</p>
        <div class="">
          <a href="#" @click="shareWin($el)" class="btn w-50 btn-info">Share my result 📋</a>
        </div>
      </div>
    </div>
  </div>
</div>
