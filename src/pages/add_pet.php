<?php
    require_once(dirname(__FILE__)."/../templates/common/header.php"); 
    require_once(dirname(__FILE__)."/../control/api/pet.php");

    $species = API\getSpecies();
    $sizes = API\getSizes();
    $colors = API\getColors();
?>
    <section class="authForm">
        <h2>Add a new Pet</h2>
        <?php 
            $message = Session\popMessage(); 
            if ($message != null) { ?>
                <p class="<?=$message['type']?>-message"><?=$message['content']?></p>
        <?php } ?>
        
        <form method="POST" name="addPet" action="<?=getRootURL()?>/control/actions/register.php">
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />

            <label for="name">Pet Name</label>
            <input type="text" id="name" name="name" placeholder="Pet Name"/>

            <label for="birthdate">Birthdate</label>
            <input type="text" id="birthdate" name="birthdate" min="2000-01-01" max="<?=date('Y-m-d')?>" placeholder="Birthdate" required/>

            <label for="location">Location</label>
            <input type="email" id="location" name="location" placeholder="Location" required/>

            <label for="description">Description</label>
            <textarea placeholder="Description" name="description" id="description"></textarea>

            <label for="specie">Species</label>
            <div class="simple-2column-grid">
                <div>
                    <select name="specie" id="specie" required>
                        <option value="-1">- Select a Species -</option>
                        <?php foreach($species as $specie) { ?>
                            <option value="<?=$specie['id']?>"><?=$specie['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <button class="simpleButton" id="addSpeciesButton"><i class="icofont-ui-add"></i>Species</button>
                </div>
            </div>

            <label for="race">Race</label>
            <div class="simple-2column-grid">
                <div>
                    <select name="race" id="race">
                    </select>
                </div>
                <div>
                    <button class="simpleButton" id="addSizeButton"><i class="icofont-ui-add"></i>Race</button>
                </div>
            </div>

            <label for="size">Size</label>
            <div class="simple-2column-grid">
                <div>
                    <select name="size" id="size" required>
                        <option value="-1">- Select a Size -</option>
                        <?php foreach($sizes as $size) { ?>
                            <option value="<?=$size['id']?>"><?=$size['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <button class="simpleButton" id="addSizeButton"><i class="icofont-ui-add"></i>Size</button>
                </div>
            </div>
            
            <label for="color">Color</label>
            <div class="simple-2column-grid">
                <div>
                    <select name="color" id="color" required>
                        <option value="-1">- Select a Color -</option>
                        <?php foreach($colors as $color) { ?>
                            <option value="<?=$color['id']?>"><?=$color['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <button class="simpleButton" id="addColorButton"><i class="icofont-ui-add"></i>Color</button>
                </div>
            </div>

            <input type="submit" value="Add Pet" />
        </form>
    </section>
<?php
    require_once(dirname(__FILE__)."/../templates/common/footer.php"); 
?>

