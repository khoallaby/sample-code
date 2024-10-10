<?php
namespace Everside;


class Modals {
  public function __construct() {
  }


  public static function renderModal($modalId, $modalClass='', $content='', $title='' ) {

    return sprintf( '
      <section id="modal-%s" class="modal fade %s" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              %s
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              %s
            </div>
          </div>
        </div>
      </section>
  ',
      $modalId,
      $modalClass,
      $title ? sprintf( '<h5 class="modal-title">%s</h5>', $title ) : '',
      $content
    );

  }

}

