<?php
require 'php_includes/head.php';
require 'php_includes/db_connect.php';
require_once 'php_scripts/db_requests.php';

$dbRequester = new DatabaseRequests($bdd);
?>

<header>
    <!-- place navbar here -->
</header>

<body class="bg-light">
    <main>



        <button class="btn btn-primary d-md-none" x-on:click="showContainer = !showContainer">Toggle Container</button>

        <div class="sticky-top d-md-block col-md-2" x-show="showContainer">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sticky Container</h5>
                    <button type="button" class="btn-close" x-on:click="showContainer = false"></button>
                </div>
                <div class="card-body">
                    <!-- Content for the container -->
                </div>
            </div>
        </div>



        <?php
        $players = $dbRequester->getAllPlayers();
        ?>

        <div class="container" x-data="gameApp()">
            <img src="assets/img/logo.png" alt="logo" class="img-fluid w-50 mx-auto d-block" />
            <div class="row mb-3">
                <div class="col-md-6 mx-auto">
                    <form @submit.prevent="guessName">
                        <div class="input-group">
                            <select
                                id="select"
                                placeholder="Select a person..."
                                x-on:change="guessName"
                                x-model="formData.idPlayer"
                                required>
                                <option value="">Select a person...</option>
                                <?php
                                foreach ($players as $player) {
                                    echo '<option value="' . $player['id_player'] . '">' . $player['name_player'] . '</option>';
                                }
                                ?>
                            </select>
                            <!-- <button class="btn btn-outline-secondary" type="submit">Button</button> -->
                        </div>
                    </form>
                </div>
            </div>



            <div id="player-to-guess" class="bg-white card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="displayName" class="form-label">Name</label>
                            <input
                                type="text"
                                x-model="correctPlayer.name"
                                class="form-control"
                                :class="correctPlayer.name ? 'bg-success' : 'bg-black'"
                                id="displayName"
                                disabled
                                readonly>
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
                                        x-model="correctPlayer.country"
                                        class="form-control"
                                        :class="correctPlayer.country ? 'bg-success' : 'bg-black'"
                                        id="displayCountry"
                                        disabled
                                        readonly>
                                </div>
                                <div class="col-md-6 order-md-3">
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
                                <div class="col-md-6 order-md-2">
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
                                <div class="col-md-6 order-md-4">
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
                <template x-for="player in guessedPlayers">
                    <div class="card bg-white mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="displayName" class="form-label">Name</label>
                                    <input
                                        type="text"
                                        x-model="player.name.value"
                                        class="form-control fw-bold"
                                        disabled
                                        readonly>
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
    <footer>
        yooooo
        <!-- place footer here -->
    </footer>
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
            showContainer: false,
            correctPlayer: {
                name: "",
                country: "",
                nbrRecords: "",
                nbrCollabs: "",
                firstRecordYear: "",
                lastTracks: [null, null, null]
            },
            guessedPlayers: [],
            async guessName() {
                var guessedPlayerResult = await fetch('php_scripts/guess.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            idGuessedPlayer: this.formData.idPlayer,
                            nbrTries: this.guessedPlayers.length + 1
                        })
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            return response.json().then(error => {
                                throw new Error(error.error);
                            });
                        }
                        return response.json();
                    })
                    .then(function(guessInfos) {
                        return guessInfos;
                    })
                    .catch((e) => {
                        console.error('er : ', e);
                    })

                this.guessedPlayers.push(guessedPlayerResult.guessedPlayer);
                this.analyzeGuess(guessedPlayerResult.guessedPlayer);
            },
            analyzeGuess(guessedPlayer) {
                var guessedPlayerProperties = Object.keys(guessedPlayer);
                // Update correctPlayer with guessedPlayer properties
                guessedPlayerProperties.forEach(property => {
                    this.updateCorrectPlayer(property, guessedPlayer[property]);
                });

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
            }
        }
    }
</script>

<?php
require 'php_includes/footer.php';
?>