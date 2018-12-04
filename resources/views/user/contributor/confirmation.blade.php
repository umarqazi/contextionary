<!-- Modal -->
<div class="modal fade" id="pointModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content .modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! t('Confirmation') !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <span class="confirmation-message">Are you sure to want to report these contributions as poor in quality?</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="orangeBtn waves-light align-center">{!! t('No') !!}</button> <a href="{!! lang_route('poor-quality', ['context_id'=>$context_id,'phrase_id'=>$phrase_id,'type'=>$type]) !!}" class="orangeBtn waves-light align-center" form="redeem_form" id="request-redee">{!! t('Yes') !!}</a>
            </div>
        </div>
    </div>
</div>