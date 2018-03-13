<div class="modal fade" id="<?= $id ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header <?= isset($no_header) && $no_header ? "hidden" : ""?>">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= isset($title) ? $title : "" ?></h4>
      </div>
      <div class="modal-body">
       <?= isset($content) ? $content : "" ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->