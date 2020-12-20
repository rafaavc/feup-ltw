<?php 
    require_once(dirname(__FILE__)."/../control/api/pet.php");
    $pageTitle = "My Pets";
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
            <p>
                <a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>">
                    <?=htmlentities(API\getPetName($pet['id']))?>
                </a>
                <span class="tagLabel <?=$pet['state']?>" data-pet-id="<?=$pet['id']?>">
                    <?=$pet['state'] == 'adopted' ? 'Adopted' : ($pet['state'] == 'ready' ? 'Ready for Adoption' : ($pet['state'] == 'archived' ? 'Archived' : null)) ?>
                </span>
            </p>
        </div>
    <?php } 
    } ?>        
</section>

<section class="petManagement">
    <h2>Adoption Proposals</h2>
    <?php if ($petsAdoptionProposals == false) { ?>
        <p>Your pets have no adoption proposals.</p>
    <?php } else { 
        foreach($petsAdoptionProposals as $proposal) { ?>
        <div class="petManagementAdoption">
            <p><a href="<?=getRootUrl()?>/user/<?=htmlentities($proposal['propUserUsername'])?>"><?=htmlentities($proposal['propUserName'])?></a> wants to adopt 
            <a href="<?=getRootUrl()?>/pet/<?=$pet['id']?>"><?=htmlentities(API\getPetName($pet['id']))?></a></p>
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
                <div class="image" style="background-image: url('../../images/user_profile_pictures/<?= $comment['creatorId'] ?>.jpg')"></div>
                <div>
                    <p class="notice"><a href="<?=getRootUrl()?>/user/<?=htmlentities($comment['creatorUsername'])?>"><?=htmlentities($comment['creatorName'])?></a> on pet <a href="<?=getRootUrl()?>/pet/<?=$comment['petId']?>"><?=htmlentities(API\getPetName($comment['petId']))?></a>:</p>
                    <p><?=htmlentities($comment['content'])?></p>
                </div>
                <p><?=elapsedTime(strtotime($comment['postDate']))?> ago</p>
                <p><a class="simpleButton contrastButton" href="<?=getRootUrl()?>/pet/<?=$comment['petId']?>#post-<?=$comment['postId']?>">Go to comment</a></p>
            </div>
    <?php } ?>
    <?php } ?>
</section>
<?php } ?>

<?php require_once(dirname(__FILE__)."/../templates/common/footer.php"); ?>

