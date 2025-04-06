<?php
require 'php_includes/head.php';
require 'php_includes/db_connect.php';
require_once 'php_scripts/db_requests.php';
include 'php_includes/modals/rules.php';

$dbRequester = new DatabaseRequests($bdd);
?>

<body class="bg-light">


    <!-- Navigation menu -->
    <div class="container">
        <header class="d-flex flex-wrap justify-content-end py-3 mb-4 border-bottom">

            <ul class="nav">
                <li class="ms-3"><a href="#" data-bs-toggle="modal" data-bs-target="#rulesModal"><img width="40" src="assets/img/question-circle.svg" alt="Help" title="How to play"></a>

                </li>
                <li class="ms-3"><a href="https://github.com/boumi21/mkwtas-game">
                        <img src="assets/img/github.png" width="40" alt="github logo" title="Source code"></a></li>
                <li class="ms-3">
                    <a href="https://mkwtas.com">
                        <img width="40" src="assets/img/mkwtas.png" alt="mkwtas logo" title="mkwtas website">
                    </a>
                </li>
            </ul>
        </header>
    </div>



    <!-- Main content -->
    <main>
        <div class="container" x-data="gameApp()">
            <img src="assets/img/logo_opti.avif" alt="logo" class="img-fluid w-50 mx-auto d-block" />

            <!-- Guess form dropdown with TomSelect -->
            <div class="row mb-3">
                <div class="col-md-6 mx-auto">
                    <form @submit.prevent="guessName" x-show="correctPlayer.name === ''">
                        <div class="input-group-lg">
                            <select
                                id="select"
                                placeholder="Choose a TASer..."
                                x-on:change="guessName();"
                                x-init="() => { updatePageInfos() }"
                                x-model="formData.idPlayer"
                                required>
                                <option value="">Select a TASer...</option>
                                <?php
                                $players = $dbRequester->getAllPlayers();
                                foreach ($players as $player) {
                                    echo '<option value="' . $player['id_player'] . '">' . $player['name_player'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                    <!-- Do not move the include modal. Needs to be in x-data -->
                    <?php include 'php_includes/modals/win.php'; ?>
                    <p x-show="correctPlayer.name" class="fw-bold text-center"><a @click="showWinModal" href="#" class="text-success text-decoration-none">Well done. Come back tomorrow for a new challenge!</a></p>
                </div>
            </div>


            <!-- div to show only before switching to a new game -->
            <div class="row mb-2" x-cloak x-show="timeOut" x-transition>
                <h2 class="col-md-6 mx-auto text-center">
                    Time is up! New TASer to guess 🕵
                </h2>
            </div>

            <!-- div that shows number of correct guesses for current game -->
            <div class="row mb-2">
                <div class="col-md-6 mx-auto text-center">
                    <span x-text="nbrGameCorrectGuesses" class="fw-bold"></span> people have guessed the TASer #<span x-text="idGame" class="fw-bold"></span>

                </div>
            </div>


            <!-- Card for the current player to guess -->
            <div id="player-to-guess" class="bg-white card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <a :href="'https://mkwtas.com/player.php?name=' + correctPlayer.name.replace(/ /g,'_')" aria-label="TASer profile's page" target="_blank">
                                <span class="fs-3 fw-bold" x-text="correctPlayer.name"></span>
                            </a>
                            <span x-show=correctPlayer.name class="fs-3">✅</span>
                            <input
                                class="form-control bg-black"
                                x-show="correctPlayer.name === ''"
                                disabled
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-6 order-1">
                                    <label for="displayCountry" class="form-label">Country</label>
                                    <input
                                        type="text"
                                        x-model="correctPlayer.country"
                                        class="form-control"
                                        :class="correctPlayer.country ? 'bg-success' : 'bg-black'"
                                        id="displayCountry"
                                        disabled
                                        readonly>
                                </div>
                                <div class="col-6 order-3">
                                    <label for="displayFirstYear" class="form-label">First TAS year</label>
                                    <input
                                        type="text"
                                        x-model="correctPlayer.firstRecordYear"
                                        class="form-control"
                                        :class="correctPlayer.firstRecordYear ? 'bg-success' : 'bg-black'"
                                        id="displayFirstYear"
                                        disabled
                                        readonly>
                                </div>
                                <div class="col-6 order-2">
                                    <label for="displayNbrTas" class="form-label"># of TAS</label>
                                    <input
                                        type="text"
                                        x-model="correctPlayer.nbrRecords"
                                        class="form-control"
                                        :class="correctPlayer.nbrRecords ? 'bg-success' : 'bg-black'"
                                        id="displayNbrTas"
                                        disabled
                                        readonly>
                                </div>
                                <div class="col-6 order-4">
                                    <label for="displayNbrCollabs" class="form-label"># of Collabs</label>
                                    <input
                                        type="text"
                                        x-model="correctPlayer.nbrCollabs"
                                        class="form-control"
                                        :class="correctPlayer.nbrCollabs !== '' ? 'bg-success' : 'bg-black'"
                                        id="displayNbrCollabs"
                                        disabled
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last 3 TAS</label>
                            <input
                                type="text"
                                x-model="correctPlayer.lastTracks[0]"
                                class="form-control"
                                :class="correctPlayer.lastTracks[0] ? 'bg-success' : 'bg-black'"
                                id="displayLastTrack1"
                                disabled
                                readonly>
                            <input
                                type="text"
                                x-model="correctPlayer.lastTracks[1]"
                                class="form-control"
                                :class="correctPlayer.lastTracks[1] ? 'bg-success' : 'bg-black'"
                                id="displayLastTrack2"
                                disabled
                                readonly>
                            <input
                                type="text"
                                x-model="correctPlayer.lastTracks[2]"
                                class="form-control"
                                :class="correctPlayer.lastTracks[2] ? 'bg-success' : 'bg-black'"
                                id="displayLastTrack3"
                                disabled
                                readonly>
                        </div>
                    </div>
                </div>
            </div>



            <hr>


            <!-- Container that adds a card every time a guess is made -->
            <div class="d-flex flex-column-reverse">
                <template x-for="(player, index) in guessedPlayers">
                    <div
                        class="card bg-white mb-3"
                        x-data="{ show: false }"
                        x-init="$nextTick(() => { show = true })"
                        x-show="show"
                        x-transition.duration.500ms>
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <span class="fs-3" x-text="player.name.value"></span>
                                </div>
                                <div class="col-2 d-flex justify-content-end align-items-center">
                                    <span x-text="'#' + (index + 1)"></span>

                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6 order-md-1">
                                            <label for="displayCountry" class="form-label">Country</label>
                                            <input
                                                type="text"
                                                x-model="player.country.value"
                                                class="form-control"
                                                :class="getGuessStatusBackground(player.country.status)"
                                                disabled
                                                readonly>
                                        </div>
                                        <div class="col-md-6 order-md-3">
                                            <label for="displayFirstYear" class="form-label">First TAS year</label>
                                            <input
                                                type="text"
                                                x-model="player.firstRecordYear.value"
                                                class="form-control"
                                                :class="getGuessStatusBackground(player.firstRecordYear.status)"
                                                disabled
                                                readonly>
                                        </div>
                                        <div class="col-md-6 order-md-2">
                                            <label for="displayNbrTas" class="form-label"># of TAS</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    x-model="player.nbrRecords.value"
                                                    class="form-control"
                                                    :class="getGuessStatusBackground(player.nbrRecords.status)"
                                                    disabled
                                                    readonly>
                                                <span
                                                    class="input-group-text"
                                                    :class="getGuessStatusText(player.nbrRecords.status)"
                                                    x-text="getGuessStatusIcon(player.nbrRecords.status)">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 order-md-4">
                                            <label for="displayNbrCollabs" class="form-label"># of Collabs</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    x-model="player.nbrCollabs.value"
                                                    class="form-control"
                                                    :class="getGuessStatusBackground(player.nbrCollabs.status)"
                                                    disabled
                                                    readonly>
                                                <span
                                                    class="input-group-text"
                                                    :class="getGuessStatusText(player.nbrCollabs.status)"
                                                    x-text="getGuessStatusIcon(player.nbrCollabs.status)">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last 3 TAS</label>
                                    <input
                                        type="text"
                                        x-model="player.lastTracks[0] === undefined ? '' : player.lastTracks[0].value"
                                        class="form-control"
                                        :class="player.lastTracks[0] === undefined ? '' : getGuessStatusBackground(player.lastTracks[0].status)"
                                        disabled
                                        readonly>
                                    <input
                                        type="text"
                                        x-model="player.lastTracks[1] === undefined ? '' : player.lastTracks[1].value"
                                        class="form-control"
                                        :class="player.lastTracks[1] === undefined ? '' : getGuessStatusBackground(player.lastTracks[1].status)"
                                        disabled
                                        readonly>
                                    <input
                                        type="text"
                                        x-model="player.lastTracks[2] === undefined ? '' : player.lastTracks[2].value"
                                        class="form-control"
                                        :class="player.lastTracks[2] === undefined ? '' : getGuessStatusBackground(player.lastTracks[2].status)"
                                        disabled
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>



    </main>
</body>


<!-- JS scripts imports -->
<script src="js/utils/EnumGuessStatus.js"></script>
<script src="js/TomSelect.js"></script>
<script src="js/GameApp.js"></script>

<?php
require 'php_includes/footer.php';
?>