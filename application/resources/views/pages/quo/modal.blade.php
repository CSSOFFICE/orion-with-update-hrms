<!--modal-->
<div class="modal card-modal" role="dialog" aria-labelledby="cardModal" id="cardModal">
    <div class="modal-dialog" id="cardModalContainer">
        <div class="modal-content hidden" id="cardModalContent">
            <div class="modal-body row min-h-200" id="cardModalBody">
                <!--close button-->
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="ti-close"></i>
                </button>

                <!--left panel-->
                <div class="col-lg-8 card-left-panel" id="card-left-panel">
                </div>

                <!--right panel-->
                <div class="col-lg-4 card-right-panel" id="card-right-panel">
                </div>
            </div>
        </div>
    </div>
</div>

<!--js dom elements-->
@include('pages.quo.components.js-elements')
