<?php
require 'php_includes/head.php';
require 'php_includes/db_connect.php';
require_once 'php_scripts/db_requests.php';

$dbRequester = new DatabaseRequests($bdd);
?>

<header>
    <!-- place navbar here -->
</header>
<main>
    <?php
    $players = $dbRequester->getAllPlayers();
    ?>

    <div class="container" x-data="gameApp()">
        <div class="row">
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
        <div class="container bg-primary">
            <div class="row">
                <div class="col-md-4">
                    <label for="displayName" class="form-label">Name</label>
                    <input
                        type="text"
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

</main>
<footer>
    yooooo
    <!-- place footer here -->
</footer>

<script>
    var settings = {};
    new TomSelect('#select', settings);



    function gameApp() {
        return {
            formData: {
                idPlayer: ''
            },
            guessName() {
                fetch('php_scripts/guess.php', {
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
                    .then(function(json) {
                        console.log(json);
                    })
                    .catch((e) => {
                        console.error('er : ', e);
                    })
            }
        }
    }
</script>

<?php
require 'php_includes/footer.php';
?>