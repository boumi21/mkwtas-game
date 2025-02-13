<?php
require 'php_includes/head.php';
require 'php_includes/db_connect.php';
require_once 'php_scripts/db_requests.php';
include 'php_includes/modals/rules.php';

$dbRequester = new DatabaseRequests($bdd);
?>

<body class="bg-light">

    <div class="container">
        <header class="d-flex flex-wrap justify-content-end py-3 mb-4 border-bottom">

            <ul class="nav">
                <li class="ms-3"><a href="#" data-bs-toggle="modal" data-bs-target="#rulesModal"><img style="height: 2.7em;" src="assets/img/question-circle.svg" alt="Help" title="How to play"></a>

                </li>
                <li class="ms-3"><a href="#">
                        <img src="assets/img/github.png" style="height: 2.5em" alt="github logo" title="Source code"></a></li>
                <li class="ms-3">
                    <a href="https://mkwtas.com">
                        <img style="height: 2.7em;" src="assets/img/mkwtas.png" alt="mkwtas logo" title="mkwtas website">
                    </a>
                </li>
            </ul>
        </header>
    </div>

    <main>



        <?php
        $players = $dbRequester->getAllPlayers();
        ?>

        <div class="container" x-data="gameApp()">
            <img src="assets/img/logo.png" alt="logo" class="img-fluid w-50 mx-auto d-block" />
            <div class="row mb-3">
                <div class="col-md-6 mx-auto">
                    <form @submit.prevent="guessName" x-show="correctPlayer.name === ''">
                        <div class="input-group-lg">
                            <select
                                id="select"
                                placeholder="Select a TASer..."
                                x-on:change="guessName();"
                                x-init="() => { updatePageInfos() }"
                                x-model="formData.idPlayer"
                                required>
                                <option value="">Select a TASer...</option>
                                <?php
                                foreach ($players as $player) {
                                    echo '<option value="' . $player['id_player'] . '">' . $player['name_player'] . '</option>';
                                }
                                ?>
                            </select>
                            <!-- <button class="btn btn-outline-secondary" type="submit">Button</button> -->
                        </div>
                    </form>
                    <p x-show="correctPlayer.name" class="fw-bold text-center"><a @click="showWinModal" href="#" class="text-success text-decoration-none">Well done. Come back tomorrow for a new challenge!</a></p>
                </div>
            </div>

            <?php
            include 'php_includes/modals/win.php';
            ?>


            <div class="row mb-2" x-cloak x-show="timeOut" x-transition>
                <h2 class="col-md-6 mx-auto text-center">
                    Time is up! New TASer to guess 🕵
                </h2>
            </div>

            <div class="row mb-2">
                <div class="col-md-6 mx-auto text-center">
                    <span x-text="nbrGameCorrectGuesses" class="fw-bold"></span> persons have guessed the TASer #<span x-text="idGame" class="fw-bold"></span>

                </div>

            </div>

            <div id="player-to-guess" class="bg-white card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <a :href="'http://localhost/mkwtas-website/player.php?name=' + correctPlayer.name.replace(/ /g,'_')" target="_blank">
                                <span class="fs-3 fw-bold" x-text="correctPlayer.name"></span>
                            </a>
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
                                    <label for="displayNbrCollabs" class="form-label"># of collabs</label>
                                    <input
                                        type="text"
                                        x-model="correctPlayer.nbrCollabs"
                                        class="form-control"
                                        :class="correctPlayer.nbrCollabs ? 'bg-success' : 'bg-black'"
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
                                <div class="col-md-4">
                                    <span class="fs-3" x-text="player.name.value"></span>
                                </div>
                                <div class="col d-flex justify-content-end align-items-center">
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
                                            <label for="displayNbrCollabs" class="form-label"># of collabs</label>
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


<script src="js/utils/EnumGuessStatus.js"></script>
<script>
    var settings = {};
    new TomSelect('#select', settings);



    function gameApp() {
        return {
            formData: {
                idPlayer: ''
            },
            correctPlayer: Alpine.$persist({
                name: "",
                country: "",
                nbrRecords: "",
                nbrCollabs: "",
                firstRecordYear: "",
                lastTracks: [null, null, null]
            }),
            guessedPlayers: Alpine.$persist([]),
            timeOut: false,
            idGame: Alpine.$persist(""),
            nbrGameCorrectGuesses: "",
            async guessName() {
                var guessedPlayerResult = await fetch('php_scripts/guess.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            idGuessedPlayer: this.formData.idPlayer,
                            nbrTries: this.guessedPlayers.length + 1,
                            idGame: this.idGame
                        })
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            return response.json().then(error => {
                                throw new Error(error.error);
                            });
                        }
                        if (response.status == 205) {
                            return "timeout";
                        }
                        return response.json();
                    })
                    .then(function(guessInfos) {
                        return guessInfos;
                    })
                    .catch((e) => {
                        console.error('er : ', e);
                    })
                if (guessedPlayerResult == "timeout") {
                    this.resetGame();
                    return;
                }
                this.guessedPlayers.push(guessedPlayerResult.guessedPlayer);
                this.analyzeGuess(guessedPlayerResult.guessedPlayer);
                this.updatePageInfos();
            },
            analyzeGuess(guessedPlayer) {
                var guessedPlayerProperties = Object.keys(guessedPlayer);
                // Update correctPlayer with guessedPlayer properties
                guessedPlayerProperties.forEach(property => {
                    this.updateCorrectPlayer(property, guessedPlayer[property]);
                });

                // If win
                if (guessedPlayer.name.status == guessStatus.correct) {
                    this.showWinModal();
                }

            },
            updateCorrectPlayer(guessProperty, guess) {
                if (guessProperty == 'lastTracks') {
                    for (const [i, track] of guess.entries()) {
                        if (track.status == guessStatus.correct) {
                            this.correctPlayer[guessProperty].splice(i, 1, track.value);
                        }
                    }
                } else {
                    if (guess.status == guessStatus.correct) {
                        this.correctPlayer[guessProperty] = guess.value;
                    }
                }
            },
            async updatePageInfos() {
                var pageInfos = await fetch('php_scripts/updatePageInfos.php')
                    .then(function(response) {
                        if (!response.ok) {
                            return response.json().then(error => {
                                throw new Error(error.error);
                            });
                        }
                        return response.json();
                    })
                    .then(function(pageInfos) {
                        return pageInfos;
                    })
                    .catch((e) => {
                        console.error('er : ', e);
                    })
                if (this.idGame && this.idGame != pageInfos.id_game) {
                    this.resetGame();
                }
                this.idGame = pageInfos.id_game;
                this.nbrGameCorrectGuesses = pageInfos.nbr_correct_guesses;
            },
            async shareWin(el) {
                var text = "I guessed the TASer of the day #" + this.idGame + " in " + this.guessedPlayers.length + " " + (this.guessedPlayers.length > 1 ? 'tries' : 'try') + "! \nCan you do better? \n\nhttps://play.mkwtas.com";
                try {
                    await navigator.clipboard.writeText(text);
                    el.innerHTML = "Copied!";
                    el.classList.remove("btn-info");
                    el.classList.add("btn-success");
                    setTimeout(() => {
                        el.innerHTML = "Share my result 📋";
                        el.classList.remove("btn-success");
                        el.classList.add("btn-info");
                    }, 2000);
                } catch (error) {
                    console.error(error.message);
                }
            },
            showWinModal() {
                const winModal = new bootstrap.Modal(document.getElementById('winModal'));
                winModal.show();
            },
            resetGame() {
                this.timeOut = true;
                localStorage.removeItem("_x_correctPlayer");
                localStorage.removeItem("_x_guessedPlayers");
                localStorage.removeItem("_x_idGame");
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        }
    }
</script>

<?php
require 'php_includes/footer.php';
?>