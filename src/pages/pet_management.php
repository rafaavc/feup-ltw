<?php 
    require_once(dirname(__FILE__)."/../control/api/pet.php");
    require_once(dirname(__FILE__)."/../templates/common/header.php"); 

    $user = Session\getAuthenticatedUser();

    if (!$user) { ?>
        <section>
            <h3>To manage your pets, <a href="<?=getRootUrl()?>/signin">sign in</a>.</h3>
        </section>
    
    <?php } else { 

    $pets = API\getArrayFromSTMT(API\getUserPets($user['id']), true);
    $petsAdoptionProposals = API\getArrayFromSTMT(API\getUserPetsOpenAdoptionProposals($user['id']), true);
    $petsComments = API\getArrayFromSTMT(API\getUserPetsComments($user['id']), 10);
?>

<section class="petManagement">
    <h2>Your Pets</h2>
    <a href="<?=getRootUrl()?>/pet/add" class="simpleButton contrastButton"><i class="icofont-ui-add"></i> Add Pet</a>

    <?php if ($pets == false) { ?>
        <p>You have no pets.</p>
    <?php } else {
        foreach($pets as $pet) { ?>
        <div class="petManagementPets">
            <p><a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>"><?=$pet['name'] == null || $pet['name'] == '' ? $pet['size']." ".$pet['color']." ".$pet['specie'] : $pet['name']?></a><span class="petState <?=$pet['state']?>"><?=$pet['state'] == 'adopted' ? 'Adopted' : ($pet['state'] == 'ready' ? 'Ready for Adoption' : ($pet['state'] == 'archived' ? 'Archived' : null)) ?></span></p>
        </div>
    <?php } 
    } ?>
    </table>        
</section>

<section class="petManagement">
    <h2>Adoption Proposals</h2>
    <?php if ($petsAdoptionProposals == false) { ?>
        <p>Your pets have no adoption proposals.</p>
    <?php } else { 
        foreach($petsAdoptionProposals as $proposal) { ?>
        <div class="petManagementAdoption">
            <p><a href="<?=getRootUrl()?>/user/<?=$proposal['propUserUsername']?>"><?=$proposal['propUserName']?></a> wants to adopt <a href="<?=getRootUrl()?>/pet/<?=$proposal['petId']?>"></a><a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>"><?=$pet['name'] == null || $pet['name'] == '' ? $pet['size']." ".$pet['color']." ".$pet['specie'] : $pet['name']?></a></p>
            <p><button data-pet="<?=$proposal['petId']?>" data-adopter="<?=$proposal['propUserId']?>" class="acceptAdoption"><i class="icofont-ui-check"></i></button></p>
            <p><button data-pet="<?=$proposal['petId']?>" data-adopter="<?=$proposal['propUserId']?>" class="declineAdoption"><i class="icofont-ui-close"></i></button></p>
        </div>
    <?php }
    } ?>

</section>

<section class="petManagement">
    <h2>Recent Comments</h2>
    <?php if ($petsComments == false) { ?>
        <p>Your pets have no recent comments.</p>
    <?php } else { ?>
    <?php foreach($petsComments as $comment) { 
        if ($comment['creatorUsername'] == $user['username']) continue; ?>
            <div class="petManagementComments">
                <div class="image" style="background-image: url('../../images/userProfilePictures/<?= $comment['creatorId'] ?>.jpg')"></div>
                <div>
                    <p><a href="<?=getRootUrl()?>/user/<?=$comment['creatorUsername']?>"><?=$comment['creatorName']?></a> on pet <a href="<?=getRootUrl()?>/pet/<?=$comment['petId']?>"><?=$comment['petName']?></a>:</p>
                    <p><?=$comment['content']?></p>
                </div>
                <p><?=elapsedTime(strtotime($comment['postDate']))?> ago</p>
                <p><a class="simpleButton contrastButton" href="<?=getRootUrl()?>/pet/<?=$comment['petId']?>#post-<?=$comment['postId']?>">Go to comment</a></p>
            </div>
    <?php } ?>
    <?php } ?>
</section>
<?php } ?>

<?php require_once(dirname(__FILE__)."/../templates/common/footer.php"); ?>
