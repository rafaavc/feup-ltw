<?php 
    require_once(dirname(__FILE__)."/../control/api/pet.php");
    require_once(dirname(__FILE__)."/../templates/common/header.php"); 

    $user = Session\getAuthenticatedUser();
    $pets = API\getUserPets($user['id']);
    $petsAdoptionProposals = API\getUserPetsAdpotionProposals($user['id']);
    $petsComments = API\getUserPetsComments($user['id']);
?>

<section>
    <h2>Your Pets</h2>
    <a href="<?=getRootUrl()?>/pet/add" class="simpleButton contrastButton"><i class="icofont-ui-add"></i> Add Pet</a>
    <table>
    <?php foreach($pets as $pet) { ?>
        <tr>
            <td><?=$pet['name']?></td>
            <td><a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>/edit"><i class="icofont-ui-edit"></i></a></td>
            <td><a><i class="icofont-ui-delete"></i></a></td>
        </tr>
    <?php } ?>
    </table>
</section>

<section>
    <h2>Adoption Proposals</h2>
    <table>
    <?php foreach($petsAdoptionProposals as $pet) { ?>
        <tr>
            <td><?=$pet['name']?></td>
            <td><a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>/edit"><i class="icofont-ui-edit"></i></a></td>
            <td><a><i class="icofont-ui-delete"></i></a></td>
        </tr>
    <?php } ?>
    </table>
</section>

<section>
    <h2>Recent Comments</h2>
    <table>
    <?php foreach($petsComments as $pet) { ?>
        <tr>
            <td><?=$pet['name']?></td>
            <td><a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>/edit"><i class="icofont-ui-edit"></i></a></td>
            <td><a><i class="icofont-ui-delete"></i></a></td>
        </tr>
    <?php } ?>
    </table>
</section>

<?php require_once(dirname(__FILE__)."/../templates/common/footer.php"); ?>

