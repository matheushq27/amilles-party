<?php
function modal_feed(){
    $args = array(
        'post_id' => $_POST['postId'],
    );
    $comments = get_comments( $args );
    $amountOfcomments = count($comments);
?> 
<!-- Modal -->
<div class="modal fade" id="modalComments" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-body-comments">
        <?php if($amountOfcomments > 0): ?>
          <?php foreach($comments as $comment): ?>
              <p class="mb-1">
                  <strong class="me-1 amille-party-color-primary"><?= $comment->comment_author ? $comment->comment_author : 'anônimo' ?></strong>
                  <span><?= $comment->comment_content ?></span>
              </p>
          <?php endforeach;?>
        <?php else: ?>
          <div class="d-flex justify-content-center align-items-center">
            <div class=text-center>
              <h4>Nenhum comentário adicionado.</h4>
              <p class="m-0">Seja o primeiro a comentar!</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
            <input type="text" name="comments" id="comments-<?= $_POST['postId'] ?>" class="w-100 border-0" placeholder="Adicione um comentário">
            <div class="mt-3 text-end">
                <button id="send-comment-<?= $_POST['postId'] ?>" amount-of-comments="<?= $amountOfcomments ?>"  class="btn amille-party-bg-primary amille-party-bg-primary-hover text-white send-comment">Publicar</button>
            </div> 
      </div>
    </div>
  </div>
</div>
<?php
exit;
}

