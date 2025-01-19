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
        
        <?php
    $players = $dbRequester->getAllPlayers();
    ?>

<div class="container" x-data="gameApp()">
        <img src="assets/img/logo.png" alt="logo" class="img-fluid w-50 mx-auto d-block" />
        <div class="row bg-info">
            <div class="col-md-6">
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
                        <button class="btn btn-outline-secondary" type="submit">Button</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="player-to-guess" class="container bg-primary">
            <div class="row">
                <div class="col-md-4">
                    <label for="displayName" class="form-label">Name</label>
                    <input
                        type="text"
                        x-model="correctPlayer.name"
                        class="form-control"
                        id="displayName"
                        disabled
                        readonly>
                </div>
            </div>
            <hr>
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6 order-md-1">
                            <label for="displayCountry" class="form-label">Country</label>
                            <input
                                type="text"
                                x-model="correctPlayer.country"
                                class="form-control"
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
                                id="displayFirstYear"
                                disabled
                                readonly>
                        </div>
                        <div class="col-md-6 order-md-2">
                            <label for="displayNbrTas" class="form-label"># of TAS</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    x-model="correctPlayer.nbrRecords"
                                    class="form-control"
                                    id="displayNbrTas"
                                    disabled
                                    readonly>
                                <span class="input-group-text">▲</span>
                            </div>
                        </div>
                        <div class="col-md-6 order-md-4">
                            <label for="displayNbrCollabs" class="form-label"># of collabs</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    x-model="correctPlayer.nbrCollabs"
                                    class="form-control"
                                    id="displayNbrCollabs"
                                    disabled
                                    readonly>
                                <span class="input-group-text">▲</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last 3 TAS</label>
                    <input
                        type="text"
                        x-model="correctPlayer.lastTracks[0]"
                        class="form-control"
                        id="displayLastTrack1"
                        disabled
                        readonly>
                    <input
                        type="text"
                        x-model="correctPlayer.lastTracks[1]"
                        class="form-control"
                        id="displayLastTrack2"
                        disabled
                        readonly>
                    <input
                        type="text"
                        x-model="correctPlayer.lastTracks[2]"
                        class="form-control"
                        id="displayLastTrack3"
                        disabled
                        readonly>
                </div>
            </div>
        </div>

        <hr>
        <template x-for="player in guessedPlayers">
            <div class="container bg-primary">
                <div class="row">
                    <div class="col-md-4">
                        <label for="displayName" class="form-label">Name</label>
                        <input
                            type="text"
                            x-model="player.name.value"
                            class="form-control"
                            id="displayName"
                            disabled
                            readonly>
                    </div>
                </div>
                <hr>
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6 order-md-1">
                                <label for="displayCountry" class="form-label">Country</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="displayCountry"
                                    disabled
                                    readonly>
                            </div>
                            <div class="col-md-6 order-md-3">
                                <label for="displayFirstYear" class="form-label">First TAS year</label>
                                <input type="text"
                                    class="form-control"
                                    id="displayFirstYear"
                                    disabled
                                    readonly>
                            </div>
                            <div class="col-md-6 order-md-2">
                                <label for="displayNbrTas" class="form-label"># of TAS</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="displayNbrTas"
                                    disabled
                                    readonly>
                            </div>
                            <div class="col-md-6 order-md-4">
                                <label for="displayNbrCollabs" class="form-label"># of collabs</label>
                                <input type="text"
                                    class="form-control"
                                    id="displayNbrCollabs"
                                    disabled
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last 3 TAS</label>
                        <input type="text" class="form-control" id="inputCity">
                        <input type="text" class="form-control" id="inputCity">
                        <input type="text" class="form-control" id="inputCity">
                    </div>
                </div>
            </div>
        </template>
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
                        body: JSON.stringify(this.formData)
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