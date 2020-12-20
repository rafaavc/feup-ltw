<?php
    $pageTitle = "Add a pet";
    require_once(dirname(__FILE__)."/../templates/common/header.php"); 
    require_once(dirname(__FILE__)."/../control/api/pet.php");

    $species = API\getSpecies();
    $sizes = API\getSizes();
    $colors = API\getColors();
?>
    <section>
        <h2>Add a new Pet</h2>

        <p class="notice">All fields marked with * are required.</p>

        <?php 
            $message = Session\popMessage(); 
            if ($message != null) { ?>
                <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
        <?php } ?>
        
        <form method="POST" name="addPet" action="<?=getRootURL()?>/action/addPet" enctype="multipart/form-data">
            <div>
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
                
                <div class="formField">
                    <label for="name">Pet Name</label>
                    <input type="text" id="name" name="name" maxlength="20" placeholder="Pet Name"/>
                </div>

                <div class="formField required">
                    <p>Birthdate</p>
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" min="<?=date("Y-m-d", getYearsAgo(20))?>" max="<?=date('Y-m-d')?>" required/>
                </div>

                <div class="formField required">
                    <label for="location">Location</label>
                    <input type="text" pattern="[a-zA-Z]+( [a-zA-Z]+)*" title="Location may only contain letters and spaces, there can not be 2 spaces in a row nor int the beginning/end." id="location" name="location" minlength="5" maxlength="20" placeholder="Location" required/>
                </div>

                <div class="formField required">
                    <label for="description">Description</label>
                    <textarea placeholder="Description" name="description" id="description" minlength="20" maxlength="300" required></textarea>
                </div>

                <label for="specie">Species</label>
                <div class="simple-2column-grid">
                    <div class="formField required">
                        <select name="specie" id="specie">
                            <option value="-1">- Select a Species -</option>
                            <?php foreach($species as $specie) { ?>
                                <option value="<?=$specie['id']?>"><?=htmlentities($specie['name'])?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <button class="simpleButton" id="addSpeciesButton" data-entity="Specie"><i class="icofont-ui-add"></i>Specie</button>
                    </div>
                </div>

                <label for="race">Race</label>
                <div class="simple-2column-grid">
                    <div class="formField">
                        <select name="race" id="race">
                        </select>
                    </div>
                    <div>
                        <button class="simpleButton" id="addRaceButton" data-entity="Race"><i class="icofont-ui-add"></i>Race</button>
                    </div>
                </div>

                <label for="size">Size</label>
                <div class="simple-2column-grid">
                    <div class="formField required">
                        <select name="size" id="size">
                            <option value="-1">- Select a Size -</option>
                            <?php foreach($sizes as $size) { ?>
                                <option value="<?=$size['id']?>"><?=htmlentities($size['name'])?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <button class="simpleButton" id="addSizeButton" data-entity="Size"><i class="icofont-ui-add"></i>Size</button>
                    </div>
                </div>
                
                <label for="color">Color</label>
                <div class="simple-2column-grid">
                    <div class="formField required">
                        <select name="color" id="color">
                            <option value="-1">- Select a Color -</option>
                            <?php foreach($colors as $color) { ?>
                                <option value="<?=$color['id']?>"><?=htmlentities($color['name'])?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <button class="simpleButton" id="addColorButton" data-entity="Color"><i class="icofont-ui-add"></i>Color</button>
                    </div>
                </div>

                <input type="submit" value="Add Pet" />
            </div>
            <div>
                <input type="hidden" name="profilePhoto" value="" />
                <div class="photos"></div>
                <input type="file" name="photos[]" accept="image/jpeg" />
                <button class="simpleButton" id="addPhotoButton"><i class="icofont-ui-add"></i>Photo</button>
                <p>To select a profile picture, click on one of the pictures you uploaded.</p>
            </div>
        </form>
    </section>
<?php
    require_once(dirname(__FILE__)."/../templates/common/footer.php"); 
?>

